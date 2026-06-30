/**
 * Company WooCommerce — Live Search Dropdown
 */
(function ($) {
    'use strict';

    var cache = {};
    var DEBOUNCE_MS = 300;
    var timer = null;

    function highlight(text, term) {
        if (!term) return text;
        var re = new RegExp('(' + term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
        return text.replace(re, '<mark>$1</mark>');
    }

    function buildDropdown(products, term) {
        var html = '<div class="company-search-dropdown">';

        if (products.length === 0) {
            html += '<div class="company-search-empty">No products found</div>';
        } else {
            $.each(products, function (i, p) {
                html += '<a href="' + p.url + '" class="company-search-item">';
                html += '<img src="' + p.image + '" alt="" class="company-search-item-img" />';
                html += '<div class="company-search-item-info">';
                html += '<span class="company-search-item-name">' + highlight(p.name, term) + '</span>';
                html += '<span class="company-search-item-price">' + p.price + '</span>';
                html += '</div></a>';
            });

            html += '<a href="' + company_search.home_url + '?s=' + encodeURIComponent(term) + '&post_type=product" class="company-search-view-all">View all results &rarr;</a>';
        }

        html += '</div>';
        return html;
    }

    function closeDropdown($overlay) {
        var $dd = $overlay.find('.company-search-dropdown');
        if ($dd.length) {
            $dd.remove();
        }
    }

    function fetchProducts(term, $overlay, $input) {
        if (cache[term]) {
            var html = buildDropdown(cache[term], term);
            closeDropdown($overlay);
            $overlay.find('.search-overlay-content').append(html);
            return;
        }

        var $dd = $overlay.find('.company-search-dropdown');
        if (!$dd.length) {
            $dd = $('<div class="company-search-dropdown"><div class="company-search-loading">Searching...</div></div>');
            $overlay.find('.search-overlay-content').append($dd);
        } else {
            $dd.html('<div class="company-search-loading">Searching...</div>');
        }

        $.post(company_search.ajax_url, {
            action: 'company_search_products',
            nonce: company_search.nonce,
            s: term,
        }, function (response) {
            if (response.success) {
                cache[term] = response.products;
                var html = buildDropdown(response.products, term);
                closeDropdown($overlay);
                $overlay.find('.search-overlay-content').append(html);
            }
        });
    }

    $(document).on('input', '#searchOverlay input[type="search"]', function () {
        var $input = $(this);
        var $overlay = $('#searchOverlay');
        var term = $input.val().trim();

        if (timer) clearTimeout(timer);

        if (term.length < 1) {
            closeDropdown($overlay);
            return;
        }

        timer = setTimeout(function () {
            fetchProducts(term, $overlay, $input);
        }, DEBOUNCE_MS);
    });

    $(document).on('keydown', '#searchOverlay input[type="search"]', function (e) {
        if (e.key === 'Escape') {
            closeDropdown($('#searchOverlay'));
        }
    });

    $(document).on('click', function (e) {
        var $dd = $('.company-search-dropdown');
        if (!$dd.length) return;
        if (!$(e.target).closest('.search-overlay-content').length) {
            $dd.remove();
        }
    });

    $(document).on('submit', '#searchOverlay form', function () {
        closeDropdown($('#searchOverlay'));
    });

    $(document).on('click', '.search-overlay-close', function () {
        closeDropdown($('#searchOverlay'));
    });

    $(document).on('click', '.search-overlay', function (e) {
        if (e.target === this) {
            closeDropdown($(this));
        }
    });

    $(document).on('click', '.company-search-item', function () {
        $('#searchOverlay').removeClass('active');
        $('body').css('overflow', '');
    });
})(jQuery);
