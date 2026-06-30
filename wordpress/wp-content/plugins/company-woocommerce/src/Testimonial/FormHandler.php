<?php
namespace Company\WooCommerce\Testimonial;

defined('ABSPATH') || exit;

class FormHandler
{
    private static $message = '';
    private static $type    = 'error';

    public static function init()
    {
        add_action('init', [__CLASS__, 'process']);
    }

    public static function get_message()
    {
        return self::$message;
    }

    public static function get_type()
    {
        return self::$type;
    }

    public static function process()
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['_submit_testimonial_nonce'])) {
            return;
        }

        if (!wp_verify_nonce(wp_unslash($_POST['_submit_testimonial_nonce']), 'submit_testimonial_action')) {
            self::$message = _t('Security check failed. Please try again.', 'Xác thực thất bại. Vui lòng thử lại.');
        } elseif (isset($_POST['_hp_comment']) && wp_unslash($_POST['_hp_comment']) !== '') {
            self::$message = _t('Your submission looks like spam.', 'Bài gửi của bạn có vẻ là spam.');
        } else {
            $name    = isset($_POST['t_name']) ? trim(sanitize_text_field(wp_unslash($_POST['t_name']))) : '';
            $email   = isset($_POST['t_email']) ? trim(sanitize_email(wp_unslash($_POST['t_email']))) : '';
            $content = isset($_POST['t_content']) ? trim(sanitize_textarea_field(wp_unslash($_POST['t_content']))) : '';
            $role    = isset($_POST['t_role']) ? trim(sanitize_text_field(wp_unslash($_POST['t_role']))) : '';

            if (!$name || !$content) {
                self::$message = _t('Please fill in your name and testimonial.', 'Vui lòng điền tên và nội dung đánh giá.');
            } elseif ($email && !is_email($email)) {
                self::$message = _t('Please enter a valid email address.', 'Vui lòng nhập địa chỉ email hợp lệ.');
            } else {
                if (!is_user_logged_in()) {
                    add_filter('user_has_cap', [__CLASS__, 'grant_cap_filter'], 10, 2);
                }

                $cat = get_term_by('slug', 'testimonial', 'category');
                if (!$cat) {
                    $result = wp_insert_term('Testimonial', 'category', [
                        'slug' => 'testimonial',
                        'description' => _t('Customer testimonials submitted via front-end form.', 'Đánh giá của khách hàng gửi qua biểu mẫu.'),
                    ]);
                    $cat_id = is_wp_error($result) ? $result : $result['term_id'];
                } else {
                    $cat_id = $cat->term_id;
                }

                if (is_wp_error($cat_id)) {
                    self::$message = _t('Could not create testimonial category. Please contact support.', 'Không thể tạo danh mục đánh giá. Vui lòng liên hệ hỗ trợ.');
                } else {
                    $post_data = [
                        'post_title'    => $name,
                        'post_content'  => $content,
                        'post_excerpt'  => $content,
                        'post_status'   => 'pending',
                        'post_type'     => 'post',
                        'post_category' => [$cat_id],
                        'meta_input'    => [
                            '_testimonial_role' => $role ?: 'Verified Reader',
                        ],
                    ];

                    if (!is_user_logged_in()) {
                        $admin = get_users(['role' => 'administrator', 'number' => 1]);
                        if (!empty($admin)) {
                            $post_data['post_author'] = $admin[0]->ID;
                        }
                    }

                    $post_id = wp_insert_post($post_data);

                    if (is_wp_error($post_id)) {
                        self::$message = _t('Something went wrong. Please try again later.', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
                    } else {
                        if ($email) {
                            update_post_meta($post_id, '_testimonial_email', $email);
                        }
                        self::$message = _t('Thank you! Your testimonial has been submitted and is awaiting approval.', 'Cảm ơn! Đánh giá của bạn đã được gửi và đang chờ phê duyệt.');
                        self::$type    = 'success';
                    }
                }

                if (!is_user_logged_in()) {
                    remove_filter('user_has_cap', [__CLASS__, 'grant_cap_filter'], 10, 2);
                }
            }
        }
    }

    private static function grant_cap_filter($allcaps, $caps)
    {
        foreach ($caps as $cap) {
            $allcaps[$cap] = true;
        }
        return $allcaps;
    }
}
