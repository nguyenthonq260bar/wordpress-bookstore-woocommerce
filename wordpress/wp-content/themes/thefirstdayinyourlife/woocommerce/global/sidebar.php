<?php
defined('ABSPATH') || exit;
if (is_active_sidebar('shop-sidebar')) :
?>
<aside class="shop-sidebar">
    <div class="container">
        <div class="sidebar-inner shop-sidebar-grid">
            <?php dynamic_sidebar('shop-sidebar'); ?>
        </div>
    </div>
</aside>
<?php endif; ?>
