<?php
defined('ABSPATH') || exit;

global $product;
?>
<div id="reviews" class="reviews-wrap">
    <div id="comments" class="reviews-list">
        <h2 class="reviews-title">
            <?php
            $count = $product->get_review_count();
            if ($count && wc_review_ratings_enabled()) {
                printf(_n('%s review', '%s reviews', $count, 'woocommerce'), $count);
            } else {
                esc_html_e('Reviews', 'woocommerce');
            }
            ?>
        </h2>

        <?php if (have_comments()) : ?>
            <div class="reviews-items">
                <?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', [
                    'callback' => 'woocommerce_comments',
                ])); ?>
            </div>

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                <nav class="woocommerce-pagination reviews-pagination">
                    <?php paginate_comments_links(apply_filters('woocommerce_comment_pagination_args', [
                        'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                        'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                        'type'      => 'list',
                    ])); ?>
                </nav>
            <?php endif; ?>
        <?php else : ?>
            <p class="reviews-none"><?php esc_html_e('There are no reviews yet.', 'woocommerce'); ?></p>
        <?php endif; ?>
    </div>

    <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
        <div id="review_form_wrapper" class="review-form-wrap">
            <div id="review_form">
                <?php
                $commenter    = wp_get_current_commenter();
                $comment_form = [
                    'title_reply'         => have_comments() ? esc_html__('Add a review', 'woocommerce') : sprintf(esc_html__('Be the first to review &ldquo;%s&rdquo;', 'woocommerce'), get_the_title()),
                    'title_reply_to'      => esc_html__('Leave a Reply to %s', 'woocommerce'),
                    'title_reply_before'  => '<span id="reply-title" class="review-form-title" role="heading" aria-level="3">',
                    'title_reply_after'   => '</span>',
                    'comment_notes_before' => '<p class="review-form-notes">' . esc_html__('Your email address will not be published.', 'woocommerce') . '</p>',
                    'comment_notes_after' => '',
                    'label_submit'        => esc_html__('Submit', 'woocommerce'),
                    'logged_in_as'        => '',
                    'comment_field'       => '',
                    'class_form'          => 'review-form',
                    'class_submit'        => 'review-form-submit',
                ];

                $name_email_required = (bool) get_option('require_name_email', 1);
                $fields              = [
                    'author' => [
                        'label'        => __('Name', 'woocommerce'),
                        'type'         => 'text',
                        'value'        => $commenter['comment_author'],
                        'required'     => $name_email_required,
                        'autocomplete' => 'name',
                    ],
                    'email'  => [
                        'label'        => __('Email', 'woocommerce'),
                        'type'         => 'email',
                        'value'        => $commenter['comment_author_email'],
                        'required'     => $name_email_required,
                        'autocomplete' => 'email',
                    ],
                ];

                $comment_form['fields'] = [];

                foreach ($fields as $key => $field) {
                    $field_html  = '<p class="review-form-field comment-form-' . esc_attr($key) . '">';
                    $field_html .= '<label for="' . esc_attr($key) . '">' . esc_html($field['label']);
                    if ($field['required']) {
                        $field_html .= ' <span class="required">*</span>';
                    }
                    $field_html .= '</label>';
                    $field_html .= '<input id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" type="' . esc_attr($field['type']) . '" autocomplete="' . esc_attr($field['autocomplete']) . '" value="' . esc_attr($field['value']) . '" size="30" ' . ($field['required'] ? 'required' : '') . ' />';
                    $field_html .= '</p>';
                    $comment_form['fields'][$key] = $field_html;
                }

                $account_page_url = wc_get_page_permalink('myaccount');
                if ($account_page_url) {
                    $comment_form['must_log_in'] = '<p class="review-form-must-login">' . sprintf(esc_html__('You must be %1$slogged in%2$s to post a review.', 'woocommerce'), '<a href="' . esc_url($account_page_url) . '">', '</a>') . '</p>';
                }

                if (wc_review_ratings_enabled()) {
                    $comment_form['comment_field'] = '<div class="review-form-rating">';
                    $comment_form['comment_field'] .= '<label for="rating">' . esc_html__('Your rating', 'woocommerce') . (wc_review_ratings_required() ? ' <span class="required">*</span>' : '') . '</label>';
                    $comment_form['comment_field'] .= '<div class="stars-rating-select" data-rating-select>';
                    $comment_form['comment_field'] .= '<select name="rating" id="rating" class="stars-rating-select-native" required>';
                    $comment_form['comment_field'] .= '<option value="">' . esc_html__('Rate&hellip;', 'woocommerce') . '</option>';
                    $comment_form['comment_field'] .= '<option value="5">' . esc_html__('Perfect', 'woocommerce') . '</option>';
                    $comment_form['comment_field'] .= '<option value="4">' . esc_html__('Good', 'woocommerce') . '</option>';
                    $comment_form['comment_field'] .= '<option value="3">' . esc_html__('Average', 'woocommerce') . '</option>';
                    $comment_form['comment_field'] .= '<option value="2">' . esc_html__('Not that bad', 'woocommerce') . '</option>';
                    $comment_form['comment_field'] .= '<option value="1">' . esc_html__('Very poor', 'woocommerce') . '</option>';
                    $comment_form['comment_field'] .= '</select>';
                    $comment_form['comment_field'] .= '<div class="stars-rating-select-ui" data-rating-select-ui></div>';
                    $comment_form['comment_field'] .= '</div></div>';
                }

                $comment_form['comment_field'] .= '<p class="review-form-field comment-form-comment">';
                $comment_form['comment_field'] .= '<label for="comment">' . esc_html__('Your review', 'woocommerce') . ' <span class="required">*</span></label>';
                $comment_form['comment_field'] .= '<textarea id="comment" name="comment" cols="45" rows="6" required></textarea>';
                $comment_form['comment_field'] .= '</p>';

                comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
                ?>
            </div>
        </div>
    <?php else : ?>
        <p class="review-form-verification-required"><?php esc_html_e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?></p>
    <?php endif; ?>
</div>
<?php
