<?php
use Company\WooCommerce\Filter\DataProvider;

defined('ABSPATH') || exit;

$current = isset($current) ? $current : [];
$active = DataProvider::get_active_filters($current);

if (empty($active)) {
    return;
}
$base = get_permalink(wc_get_page_id('shop'));
?>
<div class="filter-bar-active">
    <h4><?php echo esc_html(_t('Active Filters', 'Bộ lọc đang áp dụng')); ?></h4>
    <div class="sidebar-pills">
        <?php foreach ($active as $filter) : ?>
            <a href="<?php echo esc_url($filter['url']); ?>"
               class="sidebar-pill sidebar-pill--active">
                <?php echo esc_html($filter['label']); ?>
                <span style="margin-left:4px;">&times;</span>
            </a>
        <?php endforeach; ?>
        <a href="<?php echo esc_url($base); ?>"
           class="sidebar-pill sidebar-pill--no-ajax">
            <?php echo esc_html(_t('Clear All', 'Xoá tất cả')); ?>
        </a>
    </div>
</div>
<?php
