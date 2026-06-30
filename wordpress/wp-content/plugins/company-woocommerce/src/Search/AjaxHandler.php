<?php
namespace Company\WooCommerce\Search;

defined('ABSPATH') || exit;

class AjaxHandler
{
    public static function init()
    {
        add_action('wp_ajax_company_search_products', [self::class, 'handle']);
        add_action('wp_ajax_nopriv_company_search_products', [self::class, 'handle']);
    }

    public static function handle()
    {
        check_ajax_referer('company_search_nonce', 'nonce');

        $term = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : '';

        if (strlen($term) < 1) {
            wp_send_json(['success' => true, 'products' => []]);
        }

        $query = new \WP_Query([
            'post_type'      => 'product',
            's'              => $term,
            'posts_per_page' => 8,
            'post_status'    => 'publish',
            'meta_query'     => [],
            'tax_query'      => [],
        ]);

        $products = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product = wc_get_product(get_the_ID());
                if (!$product) {
                    continue;
                }

                $image_id  = $product->get_image_id();
                $image_url = $image_id
                    ? wp_get_attachment_image_url($image_id, 'thumbnail')
                    : wc_placeholder_img_src('thumbnail');

                $products[] = [
                    'id'    => $product->get_id(),
                    'name'  => $product->get_name(),
                    'price' => $product->get_price_html(),
                    'url'   => $product->get_permalink(),
                    'image' => $image_url,
                ];
            }
            wp_reset_postdata();
        }

        wp_send_json([
            'success'  => true,
            'products' => $products,
            'term'     => $term,
        ]);
    }
}
