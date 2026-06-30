<?php
defined('ABSPATH') || exit;

$categories = isset($categories) ? $categories : [];
$tags       = isset($tags) ? $tags : [];
$brands     = isset($brands) ? $brands : [];
$attributes = isset($attributes) ? $attributes : [];
$prices     = isset($prices) ? $prices : [];
$ratings    = isset($ratings) ? $ratings : [];
$current    = isset($current) ? $current : [];

$has_active = \Company\WooCommerce\Filter\DataProvider::has_active_filters();
?>
<div class="filter-bar" data-filter-bar data-filter-collapsed="<?php echo !$has_active ? '1' : '0'; ?>">
    <div class="filter-bar-header">
        <span class="filter-bar-header-title">
            <svg class="filter-bar-header-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="20" y2="12"/><line x1="12" y1="18" x2="20" y2="18"/>
            </svg>
            <?php echo esc_html(_t('Filters', 'Bộ lọc')); ?>
            <?php if ($has_active) : ?>
                <span class="filter-bar-count"><?php echo count(\Company\WooCommerce\Filter\DataProvider::get_active_filters($current)); ?></span>
            <?php endif; ?>
        </span>
        <button class="filter-bar-toggle" type="button" aria-label="<?php echo esc_attr(_t('Toggle filters', 'Hiện/ẩn bộ lọc')); ?>" data-filter-toggle aria-expanded="<?php echo !$has_active ? 'false' : 'true'; ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>
    </div>

    <div class="filter-bar-body" data-filter-body>
        <div class="filter-bar-sections">
            <?php if (!empty($categories)) : ?>
                <div class="filter-bar-section">
                    <span class="filter-bar-label"><?php echo esc_html(_t('Categories', 'Danh mục')); ?></span>
                    <?php
                    wc_get_template('filter-pills.php', [
                        'items'         => $categories,
                        'current_value' => $current['filter_cat'] ?? '',
                        'param_name'    => 'filter_cat',
                        'type'          => 'category',
                    ], '', COMPANY_WOO_PATH . 'templates/');
                    ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($tags)) : ?>
                <div class="filter-bar-section">
                    <span class="filter-bar-label"><?php echo esc_html(_t('Tags', 'Thẻ')); ?></span>
                    <?php
                    wc_get_template('filter-pills.php', [
                        'items'         => $tags,
                        'current_value' => $current['filter_tag'] ?? '',
                        'param_name'    => 'filter_tag',
                        'type'          => 'tag',
                    ], '', COMPANY_WOO_PATH . 'templates/');
                    ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($brands)) : ?>
                <div class="filter-bar-section">
                    <span class="filter-bar-label"><?php echo esc_html(_t('Brands', 'Thương hiệu')); ?></span>
                    <?php
                    wc_get_template('filter-pills.php', [
                        'items'         => $brands,
                        'current_value' => $current['filter_brand'] ?? '',
                        'param_name'    => 'filter_brand',
                        'type'          => 'brand',
                    ], '', COMPANY_WOO_PATH . 'templates/');
                    ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($attributes)) :
                foreach ($attributes as $tax => $terms) : ?>
                <div class="filter-bar-section">
                    <span class="filter-bar-label"><?php echo esc_html(wc_attribute_label($tax)); ?></span>
                    <?php
                    wc_get_template('filter-pills.php', [
                        'items'         => $terms,
                        'current_value' => $current['filter_attributes'][$tax] ?? '',
                        'param_name'    => 'filter_' . $tax,
                        'type'          => $tax,
                    ], '', COMPANY_WOO_PATH . 'templates/');
                    ?>
                </div>
                <?php endforeach;
            endif; ?>

            <div class="filter-bar-section">
                <span class="filter-bar-label"><?php echo esc_html(_t('Price', 'Giá')); ?></span>
                <?php
                wc_get_template('filter-pills.php', [
                    'items'         => $prices,
                    'current_value' => [
                        'min' => $current['min_price'] ?? '',
                        'max' => $current['max_price'] ?? '',
                    ],
                    'param_name' => 'price',
                    'type'       => 'price',
                ], '', COMPANY_WOO_PATH . 'templates/');
                ?>
            </div>

            <div class="filter-bar-section">
                <span class="filter-bar-label"><?php echo esc_html(_t('Rating', 'Đánh giá')); ?></span>
                <?php
                wc_get_template('filter-pills.php', [
                    'items'         => $ratings,
                    'current_value' => $current['rating'] ?? '',
                    'param_name'    => 'rating',
                    'type'          => 'rating',
                ], '', COMPANY_WOO_PATH . 'templates/');
                ?>
            </div>
        </div>
    </div>

    <?php
    wc_get_template('filter-active.php', [
        'current' => $current,
    ], '', COMPANY_WOO_PATH . 'templates/');
    ?>
</div>
<?php
