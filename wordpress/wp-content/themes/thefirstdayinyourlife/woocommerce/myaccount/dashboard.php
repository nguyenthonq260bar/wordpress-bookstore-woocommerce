<?php
defined('ABSPATH') || exit;

$current_user = wp_get_current_user();

// Stats
$total_orders = wc_get_customer_order_count($current_user->ID);
$total_spent = wc_get_customer_total_spent($current_user->ID);
$order_ids = wc_get_orders([
    'customer' => $current_user->ID,
    'limit' => -1,
    'return' => 'ids',
]);
$total_books = 0;
foreach ($order_ids as $oid) {
    $order = wc_get_order($oid);
    if ($order) {
        $total_books += $order->get_item_count();
    }
}
$reading_hours = $total_books * 12;

// Recent orders
$recent_orders = wc_get_orders([
    'customer' => $current_user->ID,
    'limit' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
]);

// Purchased products from recent orders
$purchased_ids = [];
foreach ($recent_orders as $order) {
    foreach ($order->get_items() as $item) {
        $pid = $item->get_product_id();
        if (!in_array($pid, $purchased_ids)) {
            $purchased_ids[] = $pid;
        }
    }
}
$purchased_products = [];
if (!empty($purchased_ids)) {
    $purchased_products = wc_get_products([
        'include' => array_slice($purchased_ids, 0, 4),
        'limit' => 4,
    ]);
}
?>

<div class="myaccount-hero">
    <div class="myaccount-hero-info">
        <div class="myaccount-hero-avatar">
            <?php echo get_avatar($current_user->ID, 56, '', $current_user->display_name, ['class' => 'avatar']); ?>
        </div>
        <div>
            <h1 class="myaccount-hero-title"><?php echo esc_html(sprintf(_t('Welcome back, %s', 'Chào mừng trở lại, %s'), $current_user->display_name)); ?></h1>
            <p class="myaccount-hero-sub"><?php echo esc_html(_t("Here's what's happening with your book collection today.", "Đây là những gì đang diễn ra với bộ sưu tập sách của bạn hôm nay.")); ?></p>
        </div>
    </div>
    <div class="myaccount-hero-date">
        <?php echo date_i18n('l, F j, Y'); ?>
    </div>
</div>

<div class="myaccount-stats">
    <div class="myaccount-stat-card">
        <div class="myaccount-stat-icon stat-icon-orders">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
        </div>
        <div class="myaccount-stat-body">
            <span class="myaccount-stat-value"><?php echo esc_html($total_orders); ?></span>
            <span class="myaccount-stat-label"><?php echo esc_html(_t('Total Orders', 'Tổng Đơn Hàng')); ?></span>
        </div>
    </div>
    <div class="myaccount-stat-card">
        <div class="myaccount-stat-icon stat-icon-books">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        </div>
        <div class="myaccount-stat-body">
            <span class="myaccount-stat-value"><?php echo esc_html($total_books); ?></span>
            <span class="myaccount-stat-label"><?php echo esc_html(_t('Books Purchased', 'Sách Đã Mua')); ?></span>
        </div>
    </div>
    <div class="myaccount-stat-card">
        <div class="myaccount-stat-icon stat-icon-spent">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="myaccount-stat-body">
            <span class="myaccount-stat-value"><?php echo wp_kses_post(wc_price($total_spent)); ?></span>
            <span class="myaccount-stat-label"><?php echo esc_html(_t('Total Spent', 'Tổng Chi Tiêu')); ?></span>
        </div>
    </div>
    <div class="myaccount-stat-card">
        <div class="myaccount-stat-icon stat-icon-hours">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="myaccount-stat-body">
            <span class="myaccount-stat-value"><?php echo esc_html($reading_hours); ?>h</span>
            <span class="myaccount-stat-label"><?php echo esc_html(_t('Reading Time', 'Thời Gian Đọc')); ?></span>
        </div>
    </div>
</div>

<div class="myaccount-section">
    <div class="myaccount-section-header">
        <h2><?php echo esc_html(_t('Recent Orders', 'Đơn Hàng Gần Đây')); ?></h2>
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="myaccount-section-link"><?php echo esc_html(_t('View All', 'Xem Tất Cả')); ?></a>
    </div>
    <?php if (!empty($recent_orders)) : ?>
        <div class="myaccount-orders-list">
            <?php foreach ($recent_orders as $order) : ?>
                <?php $item_count = $order->get_item_count(); ?>
                <div class="myaccount-order-item">
                    <div class="myaccount-order-info">
                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="myaccount-order-number">Order #<?php echo esc_html($order->get_order_number()); ?></a>
                        <span class="myaccount-order-date"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?> &middot; <?php echo esc_html($item_count); ?> <?php echo esc_html($item_count > 1 ? _t('items', 'sản phẩm') : _t('item', 'sản phẩm')); ?></span>
                    </div>
                    <div class="myaccount-order-meta">
                        <span class="myaccount-order-status status-<?php echo esc_attr($order->get_status()); ?>"><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></span>
                        <span class="myaccount-order-total"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="myaccount-empty">
            <p><?php echo wp_kses_post(sprintf(_t('No orders yet. <a href="%s">Start shopping</a>', 'Chưa có đơn hàng nào. <a href="%s">Bắt đầu mua sắm</a>'), esc_url(get_permalink(wc_get_page_id('shop'))))); ?></p>
        </div>
    <?php endif; ?>
</div>

<div class="myaccount-section">
    <div class="myaccount-section-header">
        <h2><?php echo esc_html(_t('Purchased Books', 'Sách Đã Mua')); ?></h2>
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('downloads')); ?>" class="myaccount-section-link"><?php echo esc_html(_t('My Library', 'Thư Viện Của Tôi')); ?></a>
    </div>
    <?php if (!empty($purchased_products)) : ?>
        <div class="myaccount-books-grid">
            <?php foreach ($purchased_products as $product) : ?>
                <div class="myaccount-book-card">
                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="myaccount-book-cover" style="--cover-bg: <?php echo mytheme_product_color($product->get_id()); ?>;">
                        <?php if (has_post_thumbnail($product->get_id())) : ?>
                            <?php echo get_the_post_thumbnail($product->get_id(), 'thumbnail', ['class' => 'myaccount-book-cover-img']); ?>
                        <?php endif; ?>
                    </a>
                    <div class="myaccount-book-info">
                        <span class="myaccount-book-title"><?php echo esc_html($product->get_name()); ?></span>
                        <span class="myaccount-book-price"><?php echo $product->get_price_html(); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="myaccount-empty">
            <p><?php echo esc_html(_t('No books purchased yet.', 'Chưa có sách nào được mua.')); ?></p>
        </div>
    <?php endif; ?>
</div>
