<?php
if (!is_active_sidebar('blog-sidebar')) {
	return;
}
?>
<aside class="blog-sidebar">
	<div class="sidebar-inner">
		<?php dynamic_sidebar('blog-sidebar'); ?>
	</div>
</aside>
