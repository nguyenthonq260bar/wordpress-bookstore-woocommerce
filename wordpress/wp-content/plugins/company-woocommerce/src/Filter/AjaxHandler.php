<?php
namespace Company\WooCommerce\Filter;

defined('ABSPATH') || exit;

class AjaxHandler
{
    public static function init()
    {
        add_action('wp_ajax_company_filter_products', [self::class, 'handle']);
        add_action('wp_ajax_nopriv_company_filter_products', [self::class, 'handle']);
    }

    public static function handle()
    {
        check_ajax_referer('company_filter_nonce', 'filter_nonce');

        $paged = isset($_POST['paged']) ? max(1, (int) $_POST['paged']) : 1;

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => apply_filters('loop_shop_per_page', 12),
            'paged'          => $paged,
            'meta_query'     => [],
            'tax_query'      => [],
        ];

        // Parse taxonomy filters
        $filter_cat   = self::parse_post_filter('filter_cat');
        $filter_tag   = self::parse_post_filter('filter_tag');
        $filter_brand = self::parse_post_filter('filter_brand');

        // Parse dynamic attribute filters
        $filter_attributes = [];
        $attr_settings = AdminPage::get_settings()['enabled_attributes'] ?? [];
        foreach ($attr_settings as $taxonomy => $enabled) {
            if (empty($enabled)) continue;
            $param = 'filter_' . $taxonomy;
            $values = self::parse_post_filter($param);
            if (!empty($values)) {
                $filter_attributes[$taxonomy] = $values;
            }
        }

        $raw_min   = isset($_POST['min_price']) ? $_POST['min_price'] : '';
        $raw_max   = isset($_POST['max_price']) ? $_POST['max_price'] : '';
        $min_price = ($raw_min !== '') ? (int) $raw_min : '';
        $max_price = ($raw_max !== '') ? (int) $raw_max : '';

        $raw_rating = isset($_POST['rating']) ? $_POST['rating'] : '';
        $rating     = ($raw_rating !== '') ? (int) $raw_rating : '';

        // Build tax_query for all taxonomy filters
        foreach ($filter_cat as $slug) {
            $args['tax_query'][] = ['taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $slug];
        }
        foreach ($filter_tag as $slug) {
            $args['tax_query'][] = ['taxonomy' => 'product_tag', 'field' => 'slug', 'terms' => $slug];
        }
        foreach ($filter_brand as $slug) {
            $args['tax_query'][] = ['taxonomy' => 'product_brand', 'field' => 'slug', 'terms' => $slug];
        }
        foreach ($filter_attributes as $taxonomy => $slugs) {
            foreach ($slugs as $slug) {
                $args['tax_query'][] = ['taxonomy' => $taxonomy, 'field' => 'slug', 'terms' => $slug];
            }
        }

        if ($min_price !== '' || $max_price !== '') {
            $price_meta = ['key' => '_price', 'type' => 'DECIMAL'];

            if ($min_price !== '' && $max_price !== '') {
                $price_meta['value']   = [$min_price, $max_price];
                $price_meta['compare'] = 'BETWEEN';
            } elseif ($min_price !== '') {
                $price_meta['value']   = $min_price;
                $price_meta['compare'] = '>=';
            } elseif ($max_price !== '') {
                $price_meta['value']   = $max_price;
                $price_meta['compare'] = '<=';
            }

            $args['meta_query'][] = $price_meta;
        }

        if ($rating !== '') {
            $args['meta_query'][] = [
                'key'     => '_wc_average_rating',
                'value'   => (float) $rating,
                'compare' => '>=',
                'type'    => 'DECIMAL',
            ];
        }

        $query = new \WP_Query($args);

        ob_start();

        if ($query->have_posts()) {
            echo '<ul class="products books-grid">';
            while ($query->have_posts()) {
                $query->the_post();
                wc_get_template_part('content', 'product');
            }
            echo '</ul>';

            $total_pages = $query->max_num_pages;
            if ($total_pages > 1) {
                $add_args = [];
                if (!empty($filter_cat))   $add_args['filter_cat']   = implode(',', $filter_cat);
                if (!empty($filter_tag))   $add_args['filter_tag']   = implode(',', $filter_tag);
                if (!empty($filter_brand)) $add_args['filter_brand'] = implode(',', $filter_brand);
                foreach ($filter_attributes as $taxonomy => $slugs) {
                    $add_args['filter_' . $taxonomy] = implode(',', $slugs);
                }
                if ($min_price !== '')   $add_args['min_price']  = $min_price;
                if ($max_price !== '')   $add_args['max_price']  = $max_price;
                if ($rating !== '')      $add_args['rating']     = $rating;

                echo '<nav class="woocommerce-pagination">';
                echo paginate_links([
                    'base'      => '%_%',
                    'format'    => '?paged=%#%',
                    'current'   => $paged,
                    'total'     => $total_pages,
                    'add_args'  => $add_args,
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                    'type'      => 'list',
                ]);
                echo '</nav>';
            }
        } else {
            do_action('woocommerce_no_products_found');
        }

        $html = ob_get_clean();
        wp_reset_postdata();

        // Render filter bar with current AJAX params
        $orig_get = $_GET;
        self::rebuild_get_for_render([
            'filter_cat'   => $filter_cat,
            'filter_tag'   => $filter_tag,
            'filter_brand' => $filter_brand,
            'filter_attributes' => $filter_attributes,
            'min_price'    => $min_price,
            'max_price'    => $max_price,
            'rating'       => $rating,
        ]);

        ob_start();
        $data = DataProvider::get_all();
        wc_get_template('filter-sidebar.php', $data, '', COMPANY_WOO_PATH . 'templates/');
        $filter_bar = ob_get_clean();

        $_GET = $orig_get;

        wp_send_json([
            'success'    => true,
            'html'       => $html,
            'filter_bar' => $filter_bar,
            'count'      => (int) $query->found_posts,
            'paged'      => $paged,
        ]);
    }

    private static function parse_post_filter($key)
    {
        $raw = isset($_POST[$key]) ? wp_unslash($_POST[$key]) : '';
        return $raw !== '' ? array_map('sanitize_text_field', explode(',', $raw)) : [];
    }

    private static function rebuild_get_for_render($filters)
    {
        $filter_keys = ['filter_cat', 'filter_tag', 'filter_brand', 'min_price', 'max_price', 'rating'];
        foreach ($filter_keys as $key) {
            unset($_GET[$key]);
        }

        if (!empty($filters['filter_cat']))   $_GET['filter_cat']   = implode(',', $filters['filter_cat']);
        if (!empty($filters['filter_tag']))   $_GET['filter_tag']   = implode(',', $filters['filter_tag']);
        if (!empty($filters['filter_brand'])) $_GET['filter_brand'] = implode(',', $filters['filter_brand']);
        if ($filters['min_price'] !== '')     $_GET['min_price']    = $filters['min_price'];
        if ($filters['max_price'] !== '')     $_GET['max_price']    = $filters['max_price'];
        if ($filters['rating'] !== '')        $_GET['rating']       = $filters['rating'];

        // Dynamic attribute params
        foreach (($filters['filter_attributes'] ?? []) as $taxonomy => $slugs) {
            $param = 'filter_' . $taxonomy;
            unset($_GET[$param]);
            if (!empty($slugs)) {
                $_GET[$param] = implode(',', $slugs);
            }
        }
    }
}
