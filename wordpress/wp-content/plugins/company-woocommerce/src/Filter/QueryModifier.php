<?php
namespace Company\WooCommerce\Filter;

defined('ABSPATH') || exit;

class QueryModifier
{
    public static function init()
    {
        add_action('pre_get_posts', [self::class, 'modify_query'], 20);
    }

    public static function modify_query($q)
    {
        if (!$q->is_main_query() || is_admin()) {
            return;
        }

        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $current = DataProvider::get_current_filters();

        if (empty($current['filter_cat']) && empty($current['filter_tag'])
            && empty($current['filter_brand'])
            && self::has_no_active_attributes($current)
            && $current['min_price'] === '' && $current['max_price'] === ''
            && $current['rating'] === '') {
            return;
        }

        $meta_query = $q->get('meta_query', []);
        $tax_query  = $q->get('tax_query', []);

        if (!empty($current['filter_cat'])) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array_values($current['filter_cat']),
                'operator' => 'AND',
            ];
        }

        if (!empty($current['filter_tag'])) {
            $tax_query[] = [
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => array_values($current['filter_tag']),
                'operator' => 'AND',
            ];
        }

        if (!empty($current['filter_brand'])) {
            $tax_query[] = [
                'taxonomy' => 'product_brand',
                'field'    => 'slug',
                'terms'    => array_values($current['filter_brand']),
                'operator' => 'AND',
            ];
        }

        foreach (($current['filter_attributes'] ?? []) as $taxonomy => $slugs) {
            if (!empty($slugs)) {
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => array_values($slugs),
                    'operator' => 'AND',
                ];
            }
        }

        if ($current['min_price'] !== '' || $current['max_price'] !== '') {
            $price_meta = ['key' => '_price', 'type' => 'DECIMAL'];

            if ($current['min_price'] !== '' && $current['max_price'] !== '') {
                $price_meta['value']   = [(int) $current['min_price'], (int) $current['max_price']];
                $price_meta['compare'] = 'BETWEEN';
            } elseif ($current['min_price'] !== '') {
                $price_meta['value']   = (int) $current['min_price'];
                $price_meta['compare'] = '>=';
            } elseif ($current['max_price'] !== '') {
                $price_meta['value']   = (int) $current['max_price'];
                $price_meta['compare'] = '<=';
            }

            $meta_query[] = $price_meta;
        }

        if ($current['rating'] !== '') {
            $meta_query[] = [
                'key'     => '_wc_average_rating',
                'value'   => (float) $current['rating'],
                'compare' => '>=',
                'type'    => 'DECIMAL',
            ];
        }

        $q->set('meta_query', $meta_query);
        $q->set('tax_query', $tax_query);
    }

    private static function has_no_active_attributes($current)
    {
        foreach (($current['filter_attributes'] ?? []) as $slugs) {
            if (!empty($slugs)) {
                return false;
            }
        }
        return true;
    }
}
