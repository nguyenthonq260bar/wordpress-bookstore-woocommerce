<?php
namespace Company\WooCommerce\Testimonial;

defined('ABSPATH') || exit;

class MetaBox
{
    public static function init()
    {
        add_action('add_meta_boxes', [__CLASS__, 'register']);
        add_action('save_post', [__CLASS__, 'save']);
    }

    public static function register()
    {
        if (!term_exists('testimonial', 'category')) {
            return;
        }
        add_meta_box('testimonial_role_meta', _t('Testimonial Details', 'Chi tiết đánh giá'), [__CLASS__, 'render'], 'post', 'normal', 'default');
    }

    public static function render($post)
    {
        wp_nonce_field('testimonial_role_nonce', 'testimonial_role_nonce_field');
        $role = get_post_meta($post->ID, '_testimonial_role', true);
        echo '<p><label for="testimonial_role">' . _t('Role (e.g. Verified Reader, Book Club Member):', 'Vai trò (VD: Người đọc đã xác thực, Thành viên CLB Sách):') . '</label></p>';
        echo '<p><input type="text" id="testimonial_role" name="_testimonial_role" value="' . esc_attr($role) . '" style="width:100%;max-width:400px;" placeholder="' . esc_attr(_t('Verified Reader', 'Người đọc đã xác thực')) . '"></p>';
    }

    public static function save($post_id)
    {
        if (!isset($_POST['testimonial_role_nonce_field']) || !wp_verify_nonce($_POST['testimonial_role_nonce_field'], 'testimonial_role_nonce')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (has_term('testimonial', 'category', $post_id)) {
            update_post_meta($post_id, '_testimonial_role', sanitize_text_field($_POST['_testimonial_role'] ?? ''));
        }
    }
}
