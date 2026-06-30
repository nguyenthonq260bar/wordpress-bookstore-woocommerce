<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ============================================
     HEADER
============================================ -->
<?php if (!is_singular() || !get_post_meta(get_the_ID(), '_hide_header', true)) : ?>
<header data-section="Header">
  <div class="container">
    <div class="header-inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
        <?php
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) :
          echo wp_get_attachment_image($custom_logo_id, 'thumbnail', false, ['class' => 'logo-image']);
        else :
        ?>
          <span class="logo-icon">P</span>
        <?php endif; ?>
        <span class="logo-text"><?php bloginfo('name'); ?></span>
      </a>

      <nav>
        <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'fallback_cb'    => false,
        ]);
        ?>
      </nav>

        <div class="header-actions">
          <button class="search-toggle" aria-label="<?php echo esc_attr(_t('Search', 'Tìm kiếm')); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
          </button>
          <?php if (!is_user_logged_in()) : ?>
          <a href="<?php echo esc_url(add_query_arg('action', 'register', get_permalink(get_option('woocommerce_myaccount_page_id')))); ?>" class="register-btn"><?php echo esc_html(_t('Register', 'Đăng ký')); ?></a>
          <?php endif; ?>
          <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="account-link" aria-label="<?php echo esc_attr(_t('Account', 'Tài khoản')); ?>">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/>
          </svg>
        </a>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-btn" aria-label="<?php echo esc_attr(_t('Cart', 'Giỏ hàng')); ?>">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"/><circle cx="21" cy="21" r="1"/><path d="M3.2 3h2.5l1.6 9.8a2 2 0 0 0 2 1.6h9a2 2 0 0 0 2-1.6L23 7H6"/>
          </svg>
          <span class="cart-count"><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : '0'; ?></span>
        </a>
        <button class="theme-toggle" aria-label="<?php echo esc_attr(_t('Toggle theme', 'Chuyển giao diện')); ?>">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
          </svg>
        </button>
        <button class="menu-toggle" aria-label="<?php echo esc_attr(_t('Menu', 'Menu')); ?>">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>

    <div class="mobile-nav-backdrop" id="mobileNavBackdrop"></div>
    <div class="mobile-nav" id="mobileNav">
      <div class="mobile-nav-header">
        <span class="mobile-nav-title"><?php echo _t('Menu', 'Menu'); ?></span>
        <button class="mobile-nav-close" aria-label="<?php echo esc_attr(_t('Close menu', 'Đóng menu')); ?>">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
          </svg>
        </button>
      </div>
      <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'mobile-nav-menu',
        'fallback_cb'    => false,
      ]);
      ?>
      <div class="mobile-nav-actions">
        <div class="mobile-lang-switcher"><?php do_action('company_language_switcher'); ?></div>
        <button class="theme-toggle mobile-theme-toggle">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
          </svg>
          <span><?php echo _t('Dark Mode', 'Chế độ tối'); ?></span>
        </button>
        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>"><?php echo _t('My Account', 'Tài khoản'); ?></a>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>"><?php echo _t('Cart', 'Giỏ hàng'); ?> (<span class="cart-count-mobile"><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : '0'; ?></span>)</a>
        <?php if (!is_user_logged_in()) : ?>
        <a href="<?php echo esc_url(add_query_arg('action', 'register', get_permalink(get_option('woocommerce_myaccount_page_id')))); ?>"><?php echo _t('Register', 'Đăng ký'); ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Search Overlay -->
  <div class="search-overlay" id="searchOverlay">
    <div class="search-overlay-content">
      <button class="search-overlay-close" aria-label="<?php echo esc_attr(_t('Close search', 'Đóng tìm kiếm')); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
        </svg>
      </button>
      <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <div class="search-overlay-input-wrap">
          <svg class="search-overlay-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
          </svg>
          <input type="search" placeholder="<?php echo esc_attr(_t('Search products...', 'Tìm sản phẩm...')); ?>" name="s" autocomplete="off" />
          <input type="hidden" name="post_type" value="product" />
          <button type="submit" class="search-overlay-submit"><?php echo _t('Search', 'Tìm kiếm'); ?></button>
        </div>
      </form>
    </div>
  </div>
</header>
<?php endif; ?>
