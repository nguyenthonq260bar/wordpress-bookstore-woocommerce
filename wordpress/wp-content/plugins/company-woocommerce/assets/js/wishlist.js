(function ($) {
    'use strict';

    $(function () {
        $('.book-card .company-wishlist-btn').each(function () {
            var $btn = $(this);
            var $cover = $btn.closest('.book-card').find('.book-cover');
            if ($cover.length) {
                $btn.appendTo($cover);
            }
        });
    });

    $(document.body).on('click', '.company-wishlist-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        var productId = $btn.data('product-id');
        var isWishlistPage = $btn.closest('.company-wishlist-grid').length > 0;

        $btn.prop('disabled', true);

        $.post(company_wishlist.ajax_url, {
            action: 'company_wishlist_toggle',
            nonce: company_wishlist.nonce,
            product_id: productId,
        }, function (response) {
            if (response.success) {
                if (isWishlistPage) {
                    $btn.closest('.company-wishlist-card').fadeOut(300, function () { $(this).remove(); });
                } else {
                    $btn.toggleClass('active', response.data.added);
                    var $icon = $btn.find('.company-wishlist-btn-icon');
                    if ($icon.length) {
                        var filled = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
                        var outline = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>';
                        $icon.html(response.data.added ? filled : outline);
                    } else {
                        $btn.html(response.data.added ? '\u2665' : '\u2661');
                    }
                }
            } else {
                if (response.data && response.data.message) {
                    alert(response.data.message);
                }
            }
        }).always(function () {
            $btn.prop('disabled', false);
        });
    });
})(jQuery);
