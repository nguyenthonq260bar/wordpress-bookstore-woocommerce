<?php
namespace Company\WooCommerce\Filter;

defined('ABSPATH') || exit;

class DataProvider
{
    private static $cache = [];
    private static $current_filters = null;
    const CACHE_TTL = HOUR_IN_SECONDS * 6;

    private static function get_cache_key($method, $param = '')
    {
        return 'company_filter_' . $method . ($param ? '_' . $param : '');
    }

    private static function get_cached($key)
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        $value = get_transient($key);
        if ($value !== false) {
            self::$cache[$key] = $value;
        }
        return $value;
    }

    private static function set_cached($key, $value)
    {
        self::$cache[$key] = $value;
        set_transient($key, $value, self::CACHE_TTL);
    }

    public static function clear_cache()
    {
        $keys = [
            self::get_cache_key('categories'),
            self::get_cache_key('tags'),
            self::get_cache_key('brands'),
            self::get_cache_key('attributes'),
            self::get_cache_key('price_ranges'),
            self::get_cache_key('ratings'),
        ];

        foreach ($keys as $key) {
            delete_transient($key);
        }

        self::$cache = [];
        self::$current_filters = null;
    }

    public static function get_all()
    {
        $settings = AdminPage::get_settings();
        $enabled = $settings['enabled_filters'];

        $data = ['current' => self::get_current_filters()];

        if (!empty($enabled['categories'])) $data['categories'] = self::get_categories();
        if (!empty($enabled['tags']))       $data['tags']       = self::get_tags();
        if (!empty($enabled['brands']))     $data['brands']     = self::get_brands();
        if (!empty($enabled['attributes'])) $data['attributes'] = self::get_enabled_attributes();
        if (!empty($enabled['price']))      $data['prices']     = self::get_price_ranges();
        if (!empty($enabled['rating']))     $data['ratings']    = self::get_ratings();

        return $data;
    }

    public static function get_categories()
    {
        $key = self::get_cache_key('categories');
        $cached = self::get_cached($key);
        if ($cached !== false) return $cached;

        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        ]);
        $result = !empty($terms) && !is_wp_error($terms) ? $terms : [];
        self::set_cached($key, $result);
        return $result;
    }

    public static function get_tags()
    {
        $key = self::get_cache_key('tags');
        $cached = self::get_cached($key);
        if ($cached !== false) return $cached;

        $terms = get_terms([
            'taxonomy'   => 'product_tag',
            'hide_empty' => true,
        ]);
        $result = !empty($terms) && !is_wp_error($terms) ? $terms : [];
        self::set_cached($key, $result);
        return $result;
    }

    public static function get_brands()
    {
        $key = self::get_cache_key('brands');
        $cached = self::get_cached($key);
        if ($cached !== false) return $cached;

        if (!taxonomy_exists('product_brand')) {
            return [];
        }
        $terms = get_terms([
            'taxonomy'   => 'product_brand',
            'hide_empty' => true,
        ]);
        $result = !empty($terms) && !is_wp_error($terms) ? $terms : [];
        self::set_cached($key, $result);
        return $result;
    }

    public static function get_enabled_attributes()
    {
        $key = self::get_cache_key('attributes');
        $cached = self::get_cached($key);
        if ($cached !== false) return $cached;

        $settings = AdminPage::get_settings();
        $enabled_attrs = $settings['enabled_attributes'] ?? [];
        $result = [];

        foreach ($enabled_attrs as $taxonomy => $enabled) {
            if (empty($enabled) || !taxonomy_exists($taxonomy)) continue;
            $terms = get_terms([
                'taxonomy'   => $taxonomy,
                'hide_empty' => true,
            ]);
            if (!empty($terms) && !is_wp_error($terms)) {
                $result[$taxonomy] = $terms;
            }
        }

        self::set_cached($key, $result);
        return $result;
    }

    public static function get_price_ranges()
    {
        $key = self::get_cache_key('price_ranges');
        $cached = self::get_cached($key);
        if ($cached !== false) return $cached;

        $result = [
            ['label' => _t('All Prices', 'Tất cả giá'),  'min' => '',    'max' => ''],
            ['label' => _t('Under 100k', 'Dưới 100k'),  'min' => '',    'max' => 100000],
            ['label' => _t('100k - 200k', '100k - 200k'), 'min' => 100000, 'max' => 200000],
            ['label' => _t('200k - 300k', '200k - 300k'), 'min' => 200000, 'max' => 300000],
            ['label' => _t('Over 300k', 'Trên 300k'),   'min' => 300000, 'max' => ''],
        ];

        self::set_cached($key, $result);
        return $result;
    }

    public static function get_ratings()
    {
        $key = self::get_cache_key('ratings');
        $cached = self::get_cached($key);
        if ($cached !== false) return $cached;

        $result = [
            ['label' => _t('All Ratings', 'Tất cả đánh giá'), 'value' => ''],
            ['label' => _t('5 stars', '5 sao'),     'value' => 5],
            ['label' => _t('4 stars & up', '4 sao trở lên'),'value' => 4],
            ['label' => _t('3 stars & up', '3 sao trở lên'),'value' => 3],
            ['label' => _t('2 stars & up', '2 sao trở lên'),'value' => 2],
            ['label' => _t('1 star & up', '1 sao trở lên'), 'value' => 1],
        ];

        self::set_cached($key, $result);
        return $result;
    }

    public static function get_current_filters()
    {
        if (self::$current_filters !== null) {
            return self::$current_filters;
        }

        $raw_cat = isset($_GET['filter_cat']) ? sanitize_text_field(wp_unslash($_GET['filter_cat'])) : '';
        $raw_tag = isset($_GET['filter_tag']) ? sanitize_text_field(wp_unslash($_GET['filter_tag'])) : '';
        $raw_brand = isset($_GET['filter_brand']) ? sanitize_text_field(wp_unslash($_GET['filter_brand'])) : '';

        $filter_cat   = $raw_cat   !== '' ? array_map('sanitize_text_field', explode(',', $raw_cat))   : [];
        $filter_tag   = $raw_tag   !== '' ? array_map('sanitize_text_field', explode(',', $raw_tag))   : [];
        $filter_brand = $raw_brand !== '' ? array_map('sanitize_text_field', explode(',', $raw_brand)) : [];

        $filter_attributes = [];
        $attr_settings = AdminPage::get_settings()['enabled_attributes'] ?? [];
        foreach ($attr_settings as $taxonomy => $enabled) {
            if (empty($enabled)) continue;
            $param = 'filter_' . $taxonomy;
            if (isset($_GET[$param])) {
                $raw = sanitize_text_field(wp_unslash($_GET[$param]));
                $filter_attributes[$taxonomy] = $raw !== ''
                    ? array_map('sanitize_text_field', explode(',', $raw))
                    : [];
            }
        }

        $rating_raw = isset($_GET['rating']) ? sanitize_text_field(wp_unslash($_GET['rating'])) : '';
        $rating = $rating_raw !== '' ? (int) $rating_raw : '';

        self::$current_filters = [
            'filter_cat'         => $filter_cat,
            'filter_tag'         => $filter_tag,
            'filter_brand'       => $filter_brand,
            'filter_attributes'  => $filter_attributes,
            'min_price'          => isset($_GET['min_price']) ? (int) wp_unslash($_GET['min_price']) : '',
            'max_price'          => isset($_GET['max_price']) ? (int) wp_unslash($_GET['max_price']) : '',
            'rating'             => $rating,
        ];

        return self::$current_filters;
    }

    public static function has_active_filters()
    {
        $c = self::get_current_filters();
        if (!empty($c['filter_cat'])) return true;
        if (!empty($c['filter_tag'])) return true;
        if (!empty($c['filter_brand'])) return true;
        foreach (($c['filter_attributes'] ?? []) as $slugs) {
            if (!empty($slugs)) return true;
        }
        if ($c['min_price'] !== '' || $c['max_price'] !== '') return true;
        if ($c['rating'] !== '') return true;
        return false;
    }

    public static function is_category_active($slug)
    {
        static $current = null;
        if ($current === null) $current = self::get_current_filters();
        return in_array($slug, $current['filter_cat'], true);
    }

    public static function is_tag_active($slug)
    {
        static $current = null;
        if ($current === null) $current = self::get_current_filters();
        return in_array($slug, $current['filter_tag'], true);
    }

    public static function is_brand_active($slug)
    {
        static $current = null;
        if ($current === null) $current = self::get_current_filters();
        return in_array($slug, $current['filter_brand'], true);
    }

    public static function is_attribute_active($taxonomy, $slug)
    {
        static $current = null;
        if ($current === null) $current = self::get_current_filters();
        $slugs = $current['filter_attributes'][$taxonomy] ?? [];
        return in_array($slug, $slugs, true);
    }

    public static function is_price_active($min, $max)
    {
        static $current = null;
        if ($current === null) $current = self::get_current_filters();
        return $current['min_price'] === $min && $current['max_price'] === $max;
    }

    public static function is_rating_active($value)
    {
        static $current = null;
        if ($current === null) $current = self::get_current_filters();
        return $value === '' ? $current['rating'] === '' : $current['rating'] === $value;
    }

    public static function get_rating_label($value)
    {
        $ratings = self::get_ratings();
        foreach ($ratings as $r) {
            if ($r['value'] === $value) {
                return $r['label'];
            }
        }
        return '';
    }

    public static function map_param_to_taxonomy($param)
    {
        $map = [
            'cat'   => 'product_cat',
            'tag'   => 'product_tag',
            'brand' => 'product_brand',
        ];
        return isset($map[$param]) ? $map[$param] : $param;
    }

    public static function taxonomy_to_filter_param($taxonomy)
    {
        $map = [
            'product_cat'   => 'filter_cat',
            'product_tag'   => 'filter_tag',
            'product_brand' => 'filter_brand',
        ];
        return isset($map[$taxonomy]) ? $map[$taxonomy] : 'filter_' . $taxonomy;
    }

    public static function build_filter_args($current, $remove_type, $remaining_values = [])
    {
        $args = [];

        if ($remove_type !== 'cat' && !empty($current['filter_cat'])) {
            $args['filter_cat'] = implode(',', $current['filter_cat']);
        } elseif ($remove_type === 'cat' && !empty($remaining_values)) {
            $args['filter_cat'] = implode(',', $remaining_values);
        }

        if ($remove_type !== 'tag' && !empty($current['filter_tag'])) {
            $args['filter_tag'] = implode(',', $current['filter_tag']);
        } elseif ($remove_type === 'tag' && !empty($remaining_values)) {
            $args['filter_tag'] = implode(',', $remaining_values);
        }

        if ($remove_type !== 'brand' && !empty($current['filter_brand'])) {
            $args['filter_brand'] = implode(',', $current['filter_brand']);
        } elseif ($remove_type === 'brand' && !empty($remaining_values)) {
            $args['filter_brand'] = implode(',', $remaining_values);
        }

        foreach (($current['filter_attributes'] ?? []) as $taxonomy => $slugs) {
            if ($remove_type === $taxonomy) {
                if (!empty($remaining_values)) {
                    $args['filter_' . $taxonomy] = implode(',', $remaining_values);
                }
            } elseif (!empty($slugs)) {
                $args['filter_' . $taxonomy] = implode(',', $slugs);
            }
        }

        if ($remove_type !== 'price') {
            if ($current['min_price'] !== '') $args['min_price'] = $current['min_price'];
            if ($current['max_price'] !== '') $args['max_price'] = $current['max_price'];
        }

        if ($remove_type !== 'rating' && $current['rating'] !== '') {
            $args['rating'] = $current['rating'];
        }

        return $args;
    }

    public static function get_active_filters($current)
    {
        $active = [];
        $base = get_permalink(wc_get_page_id('shop'));
        $all_slugs = [];

        foreach (($current['filter_cat'] ?? []) as $slug) $all_slugs['product_cat'][] = $slug;
        foreach (($current['filter_tag'] ?? []) as $slug) $all_slugs['product_tag'][] = $slug;
        foreach (($current['filter_brand'] ?? []) as $slug) $all_slugs['product_brand'][] = $slug;
        foreach (($current['filter_attributes'] ?? []) as $taxonomy => $slugs) {
            foreach ($slugs as $slug) $all_slugs[$taxonomy][] = $slug;
        }

        $term_map = [];
        if (!empty($all_slugs)) {
            $terms = get_terms([
                'taxonomy' => array_keys($all_slugs),
                'slug'     => array_merge(...array_values($all_slugs)),
                'hide_empty' => false,
            ]);
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $term_map[$term->taxonomy . '::' . $term->slug] = $term;
                }
            }
        }

        foreach (($current['filter_cat'] ?? []) as $slug) {
            $key = 'product_cat::' . $slug;
            if (!isset($term_map[$key])) continue;
            $remaining = array_values(array_diff($current['filter_cat'], [$slug]));
            $args = self::build_filter_args($current, 'cat', $remaining);
            $active[] = ['label' => $term_map[$key]->name, 'url' => add_query_arg($args, $base)];
        }

        foreach (($current['filter_tag'] ?? []) as $slug) {
            $key = 'product_tag::' . $slug;
            if (!isset($term_map[$key])) continue;
            $remaining = array_values(array_diff($current['filter_tag'], [$slug]));
            $args = self::build_filter_args($current, 'tag', $remaining);
            $active[] = ['label' => $term_map[$key]->name, 'url' => add_query_arg($args, $base)];
        }

        foreach (($current['filter_brand'] ?? []) as $slug) {
            $key = 'product_brand::' . $slug;
            if (!isset($term_map[$key])) continue;
            $remaining = array_values(array_diff($current['filter_brand'], [$slug]));
            $args = self::build_filter_args($current, 'brand', $remaining);
            $active[] = ['label' => $term_map[$key]->name, 'url' => add_query_arg($args, $base)];
        }

        foreach (($current['filter_attributes'] ?? []) as $taxonomy => $slugs) {
            foreach ($slugs as $slug) {
                $key = $taxonomy . '::' . $slug;
                if (!isset($term_map[$key])) continue;
                $remaining = array_values(array_diff($slugs, [$slug]));
                $args = self::build_filter_args($current, $taxonomy, $remaining);
                $active[] = ['label' => $term_map[$key]->name, 'url' => add_query_arg($args, $base)];
            }
        }

        if ($current['min_price'] !== '' || $current['max_price'] !== '') {
            $label = '';
            if ($current['min_price'] !== '' && $current['max_price'] !== '') {
                $label = number_format((int) $current['min_price']) . ' - ' . number_format((int) $current['max_price']);
            } elseif ($current['min_price'] !== '') {
                $label = _t('From ', 'Từ ') . number_format((int) $current['min_price']);
            } else {
                $label = _t('Up to ', 'Đến ') . number_format((int) $current['max_price']);
            }
            $args = self::build_filter_args($current, 'price');
            $active[] = ['label' => $label, 'url' => add_query_arg($args, $base)];
        }

        if ($current['rating'] !== '') {
            $label = self::get_rating_label($current['rating']);
            $args = self::build_filter_args($current, 'rating');
            $active[] = ['label' => $label, 'url' => add_query_arg($args, $base)];
        }

        return $active;
    }
}
