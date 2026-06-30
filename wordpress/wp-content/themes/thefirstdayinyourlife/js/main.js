/**
 * MyTheme main script
 */

// Back to Top
(function () {
  var btn = document.getElementById('back-to-top');
  if (!btn) return;

  var onScroll = function () {
    if (window.scrollY > 400) {
      btn.classList.add('visible');
    } else {
      btn.classList.remove('visible');
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });

  btn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();

// Recommended Carousel
(function () {
  var track = document.getElementById('recommendedTrack');
  if (!track) return;

  var container = track.closest('.recommended');
  if (!container) return;

  var prevBtn = container.querySelector('.scroll-btn--prev');
  var nextBtn = container.querySelector('.scroll-btn--next');
  var dotsEl = document.getElementById('recommendedDots');
  if (!prevBtn || !nextBtn) return;

  var cardGap = 20;

  function getCardWidth() {
    var card = track.querySelector('.recommended-card');
    return card ? card.offsetWidth + cardGap : 0;
  }

  function getScrollState() {
    var maxScroll = track.scrollWidth - track.clientWidth;
    var left = track.scrollLeft;
    return { left: left, maxScroll: maxScroll };
  }

  function updateButtons() {
    var state = getScrollState();
    prevBtn.disabled = state.left <= 1;
    nextBtn.disabled = state.left >= state.maxScroll - 1;
  }

  function updateDots() {
    if (!dotsEl) return;
    var cardW = getCardWidth();
    if (cardW <= 0) return;
    var visible = Math.round(track.clientWidth / cardW);
    var total = track.querySelectorAll('.recommended-card').length;
    var count = Math.max(1, total - visible + 1);
    var current = Math.round(track.scrollLeft / cardW);
    if (current >= count) current = count - 1;
    if (current < 0) current = 0;

    dotsEl.innerHTML = '';
    for (var i = 0; i < count; i++) {
      var dot = document.createElement('span');
      if (i === current) dot.className = 'active';
      dotsEl.appendChild(dot);
    }
  }

  function syncUI() {
    updateButtons();
    updateDots();
  }

  var scrollAmount = function () {
    return getCardWidth() * 2;
  };

  prevBtn.addEventListener('click', function () {
    track.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
  });

  nextBtn.addEventListener('click', function () {
    track.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
  });

  track.addEventListener('scroll', syncUI, { passive: true });

  var resizeTimer;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(syncUI, 150);
  });

  syncUI();
})();

// Search Overlay
(function () {
  var overlay = document.getElementById('searchOverlay');
  var toggle = document.querySelector('.search-toggle');
  if (!overlay || !toggle) return;

  var close = overlay.querySelector('.search-overlay-close');
  var input = overlay.querySelector('input[type="search"]');

  function show() {
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(function () { input.focus(); }, 120);
  }

  function hide() {
    overlay.classList.remove('active');
    document.body.style.overflow = '';
    if (input === document.activeElement) input.blur();
  }

  toggle.addEventListener('click', show);
  close.addEventListener('click', hide);

  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) hide();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('active')) hide();
  });
})();

// Cart Toast
(function () {
  var toast = document.createElement('div');
  toast.className = 'cart-toast';
  toast.setAttribute('role', 'alert');
  toast.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span class="cart-toast-text">Added to cart!</span>';
  document.body.appendChild(toast);

  var timer;
  var count = 0;

  function show() {
    count++;
    toast.querySelector('.cart-toast-text').textContent = 'Added to cart \u00d7' + count;
    clearTimeout(timer);
    toast.classList.add('show');
    timer = setTimeout(function () {
      toast.classList.remove('show');
    }, 2500);
  }

  document.addEventListener('click', function (e) {
    if (e.target.closest('.add_to_cart_button, .ajax_add_to_cart, .single_add_to_cart_button, a[href*="add-to-cart"]')) {
      show();
    }
  });
})();

// Scroll Reveal + Hero Parallax
(function () {
  var revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length === 0) return;

  var heroVisual = document.querySelector('.hero-visual');
  var heroSection = document.querySelector('.hero');

  // Intersection Observer — add .in-view when element enters viewport
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  revealEls.forEach(function (el) {
    observer.observe(el);
  });

  // Hero parallax — move hero-visual slightly on scroll
  if (heroVisual && heroSection) {
    var ticking = false;

    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          var rect = heroSection.getBoundingClientRect();
          var heroTop = rect.top;
          var heroHeight = rect.height;
          var vh = window.innerHeight;

          // Only animate while hero is visible
          if (heroTop < vh && heroTop > -heroHeight) {
            var progress = 1 - (heroTop + heroHeight) / (vh + heroHeight);
            // Clamp 0..1
            progress = Math.max(0, Math.min(1, progress));
            var offset = progress * -20; // 0 → -20px
            heroVisual.style.transform = 'translateY(' + offset + 'px)';
          }
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }
})();

// Product Tabs
(function () {
  var nav = document.querySelector('.product-tabs-nav');
  if (!nav) return;

  var btns = nav.querySelectorAll('.product-tab-btn');
  btns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var tab = btn.getAttribute('data-tab');
      var panel = document.getElementById('tab-' + tab);
      if (!panel) return;

      btns.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');

      var parent = nav.closest('.product-tabs');
      if (parent) {
        parent.querySelectorAll('.product-tab-panel').forEach(function (p) {
          p.classList.remove('active');
        });
      }
      panel.classList.add('active');
    });
  });
})();

// Login / Register tab toggle
(function () {
  var tabs = document.querySelector('.myaccount-login-tabs');
  if (!tabs) return;

  var wrap = tabs.closest('.myaccount-login-wrap');
  if (!wrap) return;

  tabs.addEventListener('click', function (e) {
    var btn = e.target.closest('.login-tab');
    if (!btn || btn.classList.contains('active')) return;

    tabs.querySelectorAll('.login-tab').forEach(function (b) { b.classList.remove('active'); });
    btn.classList.add('active');

    wrap.querySelectorAll('.woocommerce-form-login, .woocommerce-form-register').forEach(function (f) {
      f.classList.remove('active');
    });

    var form = document.getElementById('form-' + btn.getAttribute('data-tab'));
    if (form) form.classList.add('active');
  });
})();

// Mobile Menu Toggle
(function () {
  var toggle = document.querySelector('.menu-toggle');
  var nav = document.getElementById('mobileNav');
  var backdrop = document.getElementById('mobileNavBackdrop');
  var closeBtn = document.querySelector('.mobile-nav-close');
  if (!toggle || !nav) return;

  function open() {
    nav.classList.add('mobile-nav--open');
    document.body.style.overflow = 'hidden';
  }

  function closeNav() {
    nav.classList.remove('mobile-nav--open');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', open);
  if (closeBtn) closeBtn.addEventListener('click', closeNav);

  nav.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', closeNav);
  });

  if (backdrop) {
    backdrop.addEventListener('click', closeNav);
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && nav.classList.contains('mobile-nav--open')) closeNav();
  });

  nav.addEventListener('click', function (e) {
    if (e.target === nav) closeNav();
  });
})();

// Mobile Menu Toggle — float when header is scrolled past
(function () {
  var toggle = document.querySelector('.menu-toggle');
  if (!toggle) return;

  var header = document.querySelector('header');
  if (!header) return;

  function onScroll() {
    var headerBottom = header.getBoundingClientRect().bottom;
    if (headerBottom < 0) {
      toggle.classList.add('menu-toggle--float');
    } else {
      toggle.classList.remove('menu-toggle--float');
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

// Product Gallery — thumbnail swap
(function ($) {
  var $wrap = $('.product-gallery');
  if (!$wrap.length) return;

  var $mainContainer = $wrap.find('.gallery-main-container');
  var $thumbs = $wrap.find('.thumb-rail-item');
  if (!$mainContainer.length || !$thumbs.length) return;

  $wrap.on('click', '.thumb-rail-item', function (e) {
    e.preventDefault();
    var $t = $(this);
    var imgUrl = $t.data('image') || $t.attr('href');

    if ($mainContainer.find('img').attr('src') === imgUrl) return;

    $thumbs.removeClass('active');
    $t.addClass('active');

    $mainContainer.html(
      '<img src="' + imgUrl + '" alt="">'
    );
  });
})(jQuery);

// Product Gallery — hover zoom
(function ($) {
  var $wrap = $('.product-gallery');
  if (!$wrap.length) return;

  $wrap.on('mousemove', '.gallery-main-container', function (e) {
    var rect = this.getBoundingClientRect();
    var x = (e.clientX - rect.left) / rect.width * 100;
    var y = (e.clientY - rect.top) / rect.height * 100;
    $(this).find('img').css({
      transformOrigin: x + '% ' + y + '%',
      transform: 'scale(1.8)'
    });
  });

  $wrap.on('mouseleave', '.gallery-main-container', function () {
    $(this).find('img').css({
      transformOrigin: 'center center',
      transform: 'scale(1)'
    });
  });
})(jQuery);

// Dark Mode Toggle
(function () {
  var toggles = document.querySelectorAll('.theme-toggle');
  if (toggles.length === 0) return;

  var sunSVG = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
  var moonSVG = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';

  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('theme-dark');
    toggles.forEach(function (btn) {
      btn.innerHTML = sunSVG;
    });
    var span = document.querySelector('.mobile-theme-toggle span');
    if (span) span.textContent = 'Light Mode';
  }

  toggles.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var isDark = document.body.classList.toggle('theme-dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      toggles.forEach(function (b) {
        b.innerHTML = isDark ? sunSVG : moonSVG;
      });
      var span = document.querySelector('.mobile-theme-toggle span');
      if (span) span.textContent = isDark ? 'Light Mode' : 'Dark Mode';
    });
  });
})();

// Cart Quantity +/- with AJAX auto-update + Coupon AJAX
(function () {
  var form = document.querySelector('.cart-layout');
  if (!form) return;

  var debounceTimer = null;

  function updateCartFromHTML(html) {
    var parser = new DOMParser();
    var doc = parser.parseFromString(html, 'text/html');

    function replaceContent(selector, container) {
      var el = container || document;
      var newEl = doc.querySelector(selector);
      var oldEl = el.querySelector(selector);
      if (newEl && oldEl) {
        oldEl.innerHTML = newEl.innerHTML;
      }
    }

    replaceContent('.woocommerce-notices-wrapper', document);
    replaceContent('.cart-items', form);
    replaceContent('.cart-summary', document);
    replaceContent('.cart-mobile-checkout', document);

    if (typeof jQuery !== 'undefined') {
      jQuery(document.body).trigger('wc_fragment_refresh');
    }
  }

  function autoUpdateCart() {
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function () {
      form.classList.add('cart-updating');

      var formData = new FormData(form);
      formData.append('update_cart', 'Update Cart');

      fetch(form.action, {
        method: 'POST',
        body: formData,
      })
      .then(function (response) { return response.text(); })
      .then(function (html) {
        updateCartFromHTML(html);
        form.classList.remove('cart-updating');
      })
      .catch(function () {
        form.classList.remove('cart-updating');
      });
    }, 300);
  }

  form.addEventListener('click', function (e) {
    var btn = e.target.closest('.qty-btn');
    if (btn) {
      var wrapper = btn.closest('.cart-item-qty');
      if (!wrapper) return;
      var input = wrapper.querySelector('input[type="number"]');
      if (!input) return;

      var step = parseInt(input.getAttribute('step')) || 1;
      var min = parseInt(input.getAttribute('min')) || 0;
      var max = parseInt(input.getAttribute('max')) || 99999;
      var val = parseInt(input.value) || 0;

      if (btn.classList.contains('qty-btn--plus')) {
        if (val < max) {
          input.value = val + step;
          autoUpdateCart();
        }
      } else {
        if (val > min) {
          input.value = val - step;
          autoUpdateCart();
        }
      }
      return;
    }

    var applyBtn = e.target.closest('.cart-voucher-apply');
    if (applyBtn) {
      e.preventDefault();
      form.classList.add('cart-updating');
      var fd = new FormData(form);
      fd.append('apply_coupon', applyBtn.value || 'Apply coupon');
      fetch(form.action, { method: 'POST', body: fd })
        .then(function (r) { return r.text(); })
        .then(function (html) {
          updateCartFromHTML(html);
          form.classList.remove('cart-updating');
        })
        .catch(function () {
          form.classList.remove('cart-updating');
        });
      return;
    }

    var couponRemove = e.target.closest('.woocommerce-remove-coupon');
    if (couponRemove) {
      e.preventDefault();
      form.classList.add('cart-updating');
      fetch(couponRemove.href)
        .then(function (response) { return response.text(); })
        .then(function (html) {
          updateCartFromHTML(html);
          form.classList.remove('cart-updating');
        })
        .catch(function () {
          form.classList.remove('cart-updating');
        });
      return;
    }

    var toggle = e.target.closest('.cart-summary-toggle');
    if (toggle) {
      e.stopPropagation();
      var card = toggle.closest('.cart-summary-card');
      if (card) {
        card.classList.toggle('collapsed');
        var collapsed = card.classList.contains('collapsed');
        toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
      }
    }
  });

  form.addEventListener('change', function (e) {
    if (e.target.matches('.cart-item-qty input[type="number"]')) {
      autoUpdateCart();
    }
  });

  form.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && e.target.closest('.cart-item-qty input')) {
      e.preventDefault();
      autoUpdateCart();
      return;
    }

    if (e.key === 'Enter' && e.target.closest('.cart-voucher-input')) {
      e.preventDefault();
      form.classList.add('cart-updating');
      var fd = new FormData(form);
      fd.append('apply_coupon', 'Apply coupon');
      fetch(form.action, { method: 'POST', body: fd })
        .then(function (r) { return r.text(); })
        .then(function (html) {
          updateCartFromHTML(html);
          form.classList.remove('cart-updating');
        })
        .catch(function () {
          form.classList.remove('cart-updating');
        });
    }
  });
})();

// Single Product Quantity +/-
(function () {
  var wrappers = document.querySelectorAll('.product-add-to-cart .qty-wrapper');
  if (!wrappers.length) return;

  wrappers.forEach(function (wrapper) {
    wrapper.addEventListener('click', function (e) {
      var btn = e.target.closest('.qty-btn');
      if (!btn) return;

      var input = this.querySelector('input[type="number"]');
      if (!input) return;

      var step = parseInt(input.getAttribute('step')) || 1;
      var min = parseInt(input.getAttribute('min')) || 0;
      var max = parseInt(input.getAttribute('max')) || 99999;
      var val = parseInt(input.value) || 0;

      if (btn.classList.contains('qty-btn--plus')) {
        if (val < max) {
          input.value = val + step;
        }
      } else {
        if (val > min) {
          input.value = val - step;
        }
      }

      var evt = new Event('change', { bubbles: true });
      input.dispatchEvent(evt);
    });
  });
})();

// Cart Summary Toggle — delegated on body (works after AJAX reload)
(function () {
  document.body.addEventListener('click', function (e) {
    var toggle = e.target.closest('.cart-summary-toggle');
    if (!toggle) return;
    var card = toggle.closest('.cart-summary-card');
    if (card) {
      card.classList.toggle('collapsed');
      var collapsed = card.classList.contains('collapsed');
      toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
    }
  });
})();

// Address Card Edit Toggle (checkout billing)
(function () {
  var editBtns = document.querySelectorAll('.address-card-edit');
  if (!editBtns.length) return;

  editBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var card = document.getElementById('billing-address-card');
      var fields = document.getElementById('billing-address-fields');
      if (card && fields) {
        card.style.display = 'none';
        fields.style.display = '';
      }
    });
  });
})();

// Clickable star rating selector for product reviews
(function () {
  var starSvg = '<svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';

  // Watch for WooCommerce's star anchor UI and remove it immediately
  function removeWcStars() {
    var els = document.querySelectorAll('#respond p.n, #respond p.stars');
    if (els.length) {
      var ratingSelect = document.getElementById('rating');
      els.forEach(function (el) { el.remove(); });
      if (ratingSelect) ratingSelect.style.display = '';
    }
  }
  var respondEl = document.getElementById('respond') || document.documentElement;
  var wcObserver = new MutationObserver(removeWcStars);
  wcObserver.observe(respondEl, { childList: true, subtree: true });

  function initStarRating() {
    removeWcStars();

    var selects = document.querySelectorAll('.stars-rating-select');
    if (!selects.length) return;

    selects.forEach(function (container) {
      var native = container.querySelector('.stars-rating-select-native');
      var ui = container.querySelector('[data-rating-select-ui]');
      if (!native || !ui) return;

      function renderStars(value) {
        ui.innerHTML = '';
        for (var i = 5; i >= 1; i--) {
          var star = document.createElement('span');
          star.className = 'star' + (i <= value ? ' selected' : '');
          star.dataset.value = i;
          star.innerHTML = starSvg;
          ui.appendChild(star);
        }
      }

      renderStars(0);
      container.classList.add('is-initialized');

      ui.addEventListener('mouseover', function (e) {
        var star = e.target.closest('.star');
        if (!star) return;
        var val = parseInt(star.dataset.value);
        var stars = ui.querySelectorAll('.star');
        stars.forEach(function (s) {
          var sv = parseInt(s.dataset.value);
          s.classList.remove('selected');
          s.classList.toggle('hover', sv <= val);
        });
      });

      ui.addEventListener('mouseout', function () {
        var selectedVal = parseInt(native.value) || 0;
        ui.querySelectorAll('.star').forEach(function (s) {
          s.classList.remove('hover');
          var sv = parseInt(s.dataset.value);
          s.classList.toggle('selected', sv <= selectedVal);
        });
      });

      ui.addEventListener('click', function (e) {
        var star = e.target.closest('.star');
        if (!star) return;
        var val = parseInt(star.dataset.value);
        native.value = val;
        ui.querySelectorAll('.star').forEach(function (s) {
          var sv = parseInt(s.dataset.value);
          s.classList.toggle('selected', sv <= val);
          s.classList.remove('hover');
        });
      });
    });
  }

  // Run after DOM is ready (WooCommerce's star UI is created in DOM ready)
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initStarRating);
  } else {
    initStarRating();
  }
})();

// Single Product AJAX Add to Cart
(function () {
  var form = document.querySelector('form.cart');
  if (!form) return;

  // Only handle simple products (no variation selects)
  var hasVariations = form.querySelector('table.variations, select[name^="attribute_"]');
  if (hasVariations) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    var btn = form.querySelector('.single_add_to_cart_button');
    if (!btn) return;

    var isBuyNow = form.querySelector('[name="buy_now"]') && e.submitter && e.submitter.name === 'buy_now';

    // Disable button
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.style.pointerEvents = 'none';

    var formData = new FormData(form);
    formData.append('action', 'woocommerce_ajax_add_to_cart');
    formData.set('add-to-cart', formData.get('add-to-cart') || '');

    var productId = formData.get('add-to-cart');
    var quantity = formData.get('quantity') || 1;

    // Build query string
    var params = new URLSearchParams();
    params.append('product_id', productId);
    params.append('quantity', quantity);
    params.append('wc-ajax', 'add_to_cart');

    fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: params.toString()
    })
    .then(function (res) {
      if (!res.ok) throw new Error('Network error');
      return res.json();
    })
    .then(function (data) {
      if (data.error) {
        throw new Error(data.error + (data.product_url ? ' <a href="' + data.product_url + '">View product</a>' : ''));
      }

      // Update cart fragments
      if (data.fragments) {
        Object.keys(data.fragments).forEach(function (selector) {
          var el = document.querySelector(selector);
          if (el) el.innerHTML = data.fragments[selector];
        });
      }

      // Update cart count in header if present
      if (typeof data.cart_hash !== 'undefined') {
        document.body.classList.add('wc-cart-loaded');
      }

      // Trigger cart toast
      var toast = document.querySelector('.cart-toast');
      if (toast) {
        var countEl = toast.querySelector('.cart-toast-text');
        if (countEl) {
          countEl.textContent = isBuyNow ? 'Added! Redirecting...' : 'Added to cart!';
        }
        toast.classList.add('show');
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () {
          toast.classList.remove('show');
        }, 2500);
      }

      // Buy Now → redirect to checkout
      if (isBuyNow) {
        setTimeout(function () {
          window.location.href = wc_add_to_cart_params.cart_url.replace('cart', 'checkout');
        }, 600);
      }
    })
    .catch(function (err) {
      console.error('Add to cart error:', err);
      // Fallback: submit the form normally
      btn.disabled = false;
      btn.style.opacity = '1';
      btn.style.pointerEvents = '';
      form.submit();
    })
    .finally(function () {
      btn.disabled = false;
      btn.style.opacity = '1';
      btn.style.pointerEvents = '';
    });
  });
})();

