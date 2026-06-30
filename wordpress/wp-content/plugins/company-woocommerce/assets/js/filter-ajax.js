/**
 * Company WooCommerce — Filter AJAX
 */
(function ($) {
    'use strict';

    var cache = {};

    function getFilterParams() {
        var params = {};

        $('.filter-checkbox input:checked').each(function () {
            var $input = $(this);
            var type = $input.data('filter-type');
            var param = $input.data('filter-param');

            if (type === 'price') {
                var min = $input.data('min-price');
                var max = $input.data('max-price');
                if (typeof min !== 'undefined' && min !== '') params.min_price = min;
                if (typeof max !== 'undefined' && max !== '') params.max_price = max;
            } else if (type === 'rating') {
                params.rating = $input.val();
            } else {
                if (!params[param]) {
                    params[param] = [];
                }
                params[param].push($input.val());
            }
        });

        for (var key in params) {
            if (Array.isArray(params[key])) {
                params[key] = params[key].join(',');
            }
        }

        return params;
    }

    function updateURL(params) {
        var url = window.location.pathname;
        var query = $.param(params);
        if (query) {
            url += '?' + query;
        }
        window.history.pushState({ filterParams: params }, '', url);
    }

    function loadProducts(params, $content, callback) {
        var cacheKey = JSON.stringify(params);

        if (cache[cacheKey]) {
            $content.html(cache[cacheKey].html);
            $content.prepend(cache[cacheKey].filter_bar);
            $content.removeClass('loading');
            if (typeof callback === 'function') callback();
            return;
        }

        params.action = 'company_filter_products';
        params.filter_nonce = company_filter.filter_nonce;

        $.post(company_filter.ajax_url, params, function (response) {
            if (response.success && response.html) {
                cache[cacheKey] = {
                    html: response.html,
                    filter_bar: response.filter_bar,
                };
                $content.html(response.html);
                $content.prepend(response.filter_bar);
            }
            $content.removeClass('loading');
            if (typeof callback === 'function') callback();
        });
    }

    $(document.body).on('change', '.filter-checkbox input', function () {
        var $content = $('.shop-content');
        var params = getFilterParams();

        $content.addClass('loading');
        updateURL(params);
        loadProducts(params, $content, function () {
            $(window).trigger('resize');
        });
    });

    $(window).on('popstate', function (e) {
        if (e.originalEvent.state && e.originalEvent.state.filterParams) {
            var params = e.originalEvent.state.filterParams;
            var $content = $('.shop-content');

            $('.filter-checkbox input').prop('checked', false);

            for (var paramName in params) {
                if (paramName === 'min_price' || paramName === 'max_price' || paramName === 'rating') {
                    continue;
                }
                var values = params[paramName].split(',');
                $.each(values, function (i, val) {
                    $('.filter-checkbox input[data-filter-param="' + paramName + '"][value="' + val + '"]').prop('checked', true);
                });
            }

            if (typeof params.min_price !== 'undefined' || typeof params.max_price !== 'undefined') {
                var min = params.min_price || '';
                var max = params.max_price || '';
                $('.filter-checkbox input[data-filter-type="price"]').each(function () {
                    if ($(this).data('min-price') == min && $(this).data('max-price') == max) {
                        $(this).prop('checked', true);
                    }
                });
            }

            if (params.rating) {
                $('.filter-checkbox input[data-filter-type="rating"][value="' + params.rating + '"]').prop('checked', true);
            }

            $content.addClass('loading');
            loadProducts(params, $content);
        }
    });

    /* =================================
       Filter bar toggle (collapse/expand)
    ================================= */
    function initFilterToggle() {
        var $bar = $('[data-filter-bar]');
        if (!$bar.length) return;
        var $toggle = $bar.find('[data-filter-toggle]');
        var $body = $bar.find('[data-filter-body]');
        var key = 'company_filter_collapsed';

        function apply(state) {
            if (state === 'collapsed') {
                $body.hide();
                $toggle.addClass('collapsed');
            } else {
                $body.show();
                $toggle.removeClass('collapsed');
            }
        }

        try {
            var saved = localStorage.getItem(key);
            if (saved === 'collapsed') {
                apply('collapsed');
            } else {
                apply('expanded');
            }
        } catch(e) {
            apply('expanded');
        }

        $bar.find('.filter-bar-header').off('click.filterToggle').on('click.filterToggle', function () {
            var isCollapsed = $toggle.hasClass('collapsed');
            if (isCollapsed) {
                apply('expanded');
                try { localStorage.setItem(key, 'expanded'); } catch(e) {}
            } else {
                apply('collapsed');
                try { localStorage.setItem(key, 'collapsed'); } catch(e) {}
            }
        });
    }

    $(document).ready(initFilterToggle);

    // Re-init after AJAX load
    var _origLoad = loadProducts;
    loadProducts = function (params, $content, callback) {
        var cb = callback;
        _origLoad(params, $content, function () {
            initFilterToggle();
            if (typeof cb === 'function') cb();
        });
    };
})(jQuery);
