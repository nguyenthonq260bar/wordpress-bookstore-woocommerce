<?php
defined('ABSPATH') || exit;
?>
<li <?php comment_class('review-item'); ?> id="li-comment-<?php comment_ID(); ?>">
    <div id="comment-<?php comment_ID(); ?>" class="review-card">
        <div class="review-card-avatar">
            <?php echo get_avatar($comment, apply_filters('woocommerce_review_gravatar_size', '50'), ''); ?>
        </div>
        <div class="review-card-body">
            <div class="review-card-meta">
                <strong class="review-card-author"><?php comment_author(); ?></strong>
                <?php if ('yes' === get_option('woocommerce_review_rating_verification_label') && wc_review_is_from_verified_owner($comment->comment_ID)) : ?>
                    <span class="review-card-verified"><?php esc_html_e('verified owner', 'woocommerce'); ?></span>
                <?php endif; ?>
                <time class="review-card-date" datetime="<?php echo esc_attr(get_comment_date('c')); ?>"><?php echo esc_html(get_comment_date(wc_date_format())); ?></time>
            </div>

            <?php wc_get_template('single-product/review-rating.php'); ?>

            <?php if ('0' === $comment->comment_approved) : ?>
                <em class="review-card-awaiting"><?php esc_html_e('Your review is awaiting approval', 'woocommerce'); ?></em>
            <?php endif; ?>

            <div class="review-card-text">
                <?php comment_text(); ?>
            </div>
        </div>
    </div>
</li>
<?php
