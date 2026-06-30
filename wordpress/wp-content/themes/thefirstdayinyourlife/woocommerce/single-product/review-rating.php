<?php
defined('ABSPATH') || exit;

global $comment;
$rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));

if (!$rating || !wc_review_ratings_enabled()) {
    return;
}

$starSvg = '<svg viewBox="0 0 24 24" width="14" height="14"><path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
?>
<div class="review-card-stars" role="img" aria-label="<?php printf(esc_attr__('Rated %d out of 5', 'woocommerce'), $rating); ?>">
    <?php for ($i = 1; $i <= 5; $i++) : ?>
        <span class="review-star<?php echo $i <= $rating ? ' review-star--filled' : ' review-star--empty'; ?>"><?php echo $starSvg; ?></span>
    <?php endfor; ?>
</div>
<?php
