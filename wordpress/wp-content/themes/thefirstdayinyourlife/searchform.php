<?php
/**
 * Search form
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
	<svg class="search-form-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
	</svg>
	<input type="search" name="s" placeholder="<?php esc_attr_e('Search...', 'mytheme'); ?>" value="<?php echo get_search_query(); ?>" />
	<button type="submit"><?php esc_html_e('Search', 'mytheme'); ?></button>
</form>
