<?php
namespace Company\WooCommerce\Testimonial;

defined('ABSPATH') || exit;

class Module
{
    public function init()
    {
        FormHandler::init();
        MetaBox::init();
        Shortcode::init();
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets()
    {
        $post = get_post();
        if (!is_front_page() && !is_page() && (!$post || !has_shortcode($post->post_content ?? '', 'submit_testimonial_form'))) {
            return;
        }

        wp_enqueue_style(
            'company-testimonial',
            COMPANY_WOO_URL . 'assets/css/testimonial.css',
            [],
            COMPANY_WOO_VERSION
        );
    }
}
