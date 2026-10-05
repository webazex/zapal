/**
 * ZAPAL frontend interactions.
 *
 * Rules for this file:
 * - DOM work and events are handled through jQuery.
 * - Visual state is expressed through classes/data-attributes; CSS owns appearance.
 * - Frontend form validation checks only whether required fields are filled.
 * - Semantic validation belongs to the backend and is returned through AJAX.
 * - Components initialize from their own markup/data attributes where possible.
 */
jQuery(document).ready(function ($) {
    'use strict';

    console.log($.fn.jquery);
    console.log('Zapal inited');

    var config = {
        mobileBreakpoint: 760,
        preloaderDemoDelay: 0,
        railDragThreshold: 7,
        defaultSuccessTitle: 'Дякуємо! Запит отримано.',
        defaultSuccessMessage: 'B2B-менеджер зв’яжеться з вами протягом 1 робочого дня та надасть інформацію щодо зразків і специфікації.',
        defaultErrorMessage: 'Не вдалося відправити запит. Спробуйте ще раз або зв’яжіться з менеджером.'
    };

    /**
     * Returns true when the current viewport uses the mobile navigation/layout.
     * Keeping the breakpoint in one place avoids scattering literal numbers.
     *
     * @returns {boolean}
     */
    function isMobileViewport() {
        return $(window).width() <= config.mobileBreakpoint;
    }

    /**
     * Releases the branded preloader as soon as the page is ready.
     * In production the artificial delay can be set to 0 without changing markup.
     *
     * @param {jQuery} $preloader
     */
    function releasePreloader($preloader) {
        var startedAt = Number($preloader.data('startedAt')) || window.performance.now();
        var elapsed = window.performance.now() - startedAt;
        var wait = Math.max(0, config.preloaderDemoDelay - elapsed);

        window.setTimeout(function () {
            $preloader.addClass('is-leaving');
            $('body').removeClass('is-loading');

            window.setTimeout(function () {
                $preloader.remove();
            }, 760);
        }, wait);
    }

    /**
     * Initializes the optional branded preloader.
     * No page-specific selector is required beyond data-preloader.
     */
    function initPreloader() {
        var $preloader = $('[data-preloader]').first();

        if (!$preloader.length) {
            $('body').removeClass('is-loading');
            return;
        }

        $preloader.data('startedAt', window.performance.now());

        if (document.readyState === 'complete') {
            releasePreloader($preloader);
            return;
        }

        $(window).one('load.zapalPreloader', function () {
            releasePreloader($preloader);
        });
    }

    /**
     * Reads the stored theme. Light is the approved/default design.
     *
     * @returns {'light'|'dark'}
     */
    function getStoredTheme() {
        try {
            return window.localStorage.getItem('zapal-theme') === 'dark' ? 'dark' : 'light';
        } catch (error) {
            return 'light';
        }
    }

    /**
     * Applies theme state only through the data-theme attribute.
     * All actual colors/layout changes are defined in CSS combinations.
     *
     * @param {'light'|'dark'} theme
     * @param {boolean} persist
     */
    function setTheme(theme, persist) {
        var nextTheme = theme === 'dark' ? 'dark' : 'light';
        var dark = nextTheme === 'dark';

        $('html').attr('data-theme', nextTheme);
        $('[data-theme-toggle]')
            .attr('aria-pressed', dark ? 'true' : 'false')
            .attr('aria-label', dark ? 'Увімкнути світлу тему' : 'Увімкнути темну тему')
            .attr('title', dark ? 'Світла тема' : 'Темна тема');

        if (persist) {
            try {
                window.localStorage.setItem('zapal-theme', nextTheme);
            } catch (error) {
                // Theme persistence is optional; visual switching still works.
            }
        }
    }

    /**
     * Binds the static theme button. JS only switches data-theme;
     * icon and visual palette are entirely CSS-driven.
     */
    function initTheme() {
        setTheme(getStoredTheme(), false);

        $(document).on('click.zapalTheme', '[data-theme-toggle]', function () {
            var current = $('html').attr('data-theme') === 'dark' ? 'dark' : 'light';
            setTheme(current === 'dark' ? 'light' : 'dark', true);
        });
    }

    /**
     * Opens or closes a mobile menu belonging to a particular header.
     * The function works from the clicked button's own header, avoiding global coupling.
     *
     * @param {jQuery} $header
     * @param {boolean} open
     */
    function setMobileMenu($header, open) {
        var $button = $header.find('.menu-toggle').first();
        var $nav = $header.find('.nav').first();

        if (!$button.length || !$nav.length) {
            return;
        }

        $nav.toggleClass('is-open', open);
        $('body').toggleClass('menu-open', open);
        $button
            .attr('aria-expanded', open ? 'true' : 'false')
            .attr('aria-label', open ? 'Закрити меню' : 'Меню');
    }

    /**
     * Initializes full-screen mobile navigation and its close conditions.
     */
    function initMobileMenu() {
        $('.site-header').each(function () {
            var $header = $(this);
            var $button = $header.find('.menu-toggle').first();

            if (!$button.length) {
                return;
            }

            $button.attr('aria-expanded', 'false');

            $button.on('click.zapalMenu', function () {
                var open = !$header.find('.nav').first().hasClass('is-open');
                setMobileMenu($header, open);
            });

            $header.on('click.zapalMenu', '.nav a', function () {
                setMobileMenu($header, false);
            });
        });

        $(document).on('keydown.zapalMenu', function (event) {
            if (event.key !== 'Escape') {
                return;
            }

            $('.site-header').each(function () {
                setMobileMenu($(this), false);
            });
        });

        $(window).on('resize.zapalMenu', function () {
            if (!isMobileViewport()) {
                $('.site-header').each(function () {
                    setMobileMenu($(this), false);
                });
            }
        });
    }

    /**
     * Updates header scroll state and contrast state based on sections under the header.
     * CSS decides how .is-scrolled and .on-dark are rendered.
     */
    function updateHeaderState() {
        var $header = $('.site-header').first();

        if (!$header.length) {
            return;
        }

        var probeY = 44;
        var onDark = false;

        $('[data-header="dark"], .product-hero').each(function () {
            var rect = this.getBoundingClientRect();
            if (rect.top <= probeY && rect.bottom > probeY) {
                onDark = true;
                return false;
            }
        });

        $header.toggleClass('is-scrolled', $(window).scrollTop() > 18);
        $header.toggleClass('on-dark', onDark);
    }

    /**
     * Initializes header state updates on scroll and resize.
     */
    function initHeaderState() {
        updateHeaderState();
        $(window).on('scroll.zapalHeader resize.zapalHeader', updateHeaderState);
    }

    /**
     * Reveals elements when they enter the viewport.
     * IntersectionObserver is used for efficiency while class manipulation stays in jQuery.
     */
    function initRevealAnimations() {
        var $items = $('[data-reveal]');

        if (!$items.length) {
            return;
        }

        if (!('IntersectionObserver' in window)) {
            $items.addClass('revealed');
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            $.each(entries, function (_, entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                $(entry.target).addClass('revealed');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12 });

        $items.each(function () {
            observer.observe(this);
        });
    }

    /**
     * Initializes the subtle product/parallax movement on elements marked data-tilt-product.
     * Reduced-motion users keep a static product image.
     */
    function initProductTilt() {
        var $product = $('[data-tilt-product]').first();

        if (!$product.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        var targetX = 0;
        var targetY = 0;
        var currentX = 0;
        var currentY = 0;

        /** Smoothly approaches pointer/scroll targets instead of applying abrupt transforms. */
        function tick() {
            currentX += (targetX - currentX) * 0.08;
            currentY += (targetY - currentY) * 0.08;
            $product.css('transform', 'translate3d(' + currentX + 'px,' + currentY + 'px,0) rotate(' + (currentX * 0.018) + 'deg)');
            window.requestAnimationFrame(tick);
        }

        $(window).on('pointermove.zapalTilt', function (event) {
            targetX = (event.clientX / $(window).width() - 0.5) * 20;
            targetY = (event.clientY / $(window).height() - 0.5) * 10;
        });

        $(window).on('scroll.zapalTilt', function () {
            targetY = Math.min(18, $(window).scrollTop() * 0.018);
        });

        window.requestAnimationFrame(tick);
    }

    /**
     * Returns one visual card step for a specific rail.
     * Width and gap are read from the actual component rather than hardcoded.
     *
     * @param {jQuery} $rail
     * @returns {number}
     */
    function getRailStep($rail) {
        var $card = $rail.children('.product-card').first();
        if (!$card.length) {
            return $rail.innerWidth() * 0.8;
        }

        var gap = parseFloat($rail.css('column-gap')) || parseFloat($rail.css('gap')) || 16;
        return $card.outerWidth() + gap;
    }

    /**
     * Resolves the component boundary for a product rail.
     * Controls currently live next to the rail wrapper, so the nearest section is the
     * natural reusable scope instead of querying the entire document.
     *
     * @param {jQuery} $rail
     * @returns {jQuery}
     */
    function getRailScope($rail) {
        var $section = $rail.closest('section');
        return $section.length ? $section : $rail.parent();
    }

    /**
     * Synchronizes the progress indicator for one product rail.
     * Infinite rails never disable arrows: cloned neighbours are always available.
     *
     * @param {jQuery} $rail
     */
    function updateRailControls($rail) {
        var $scope = getRailScope($rail);
        var $prev = $scope.find('[data-rail-prev]').first();
        var $next = $scope.find('[data-rail-next]').first();
        var $progress = $scope.find('[data-rail-progress]').first();
        var segment = Number($rail.data('railSegment')) || 0;
        var start = Number($rail.data('railStart')) || 0;
        var current = $rail.scrollLeft();
        var ratio = 0;

        if (segment > 0) {
            var relative = (current - start) % segment;
            if (relative < 0) {
                relative += segment;
            }
            ratio = relative / segment;
            $prev.prop('disabled', false);
            $next.prop('disabled', false);
        } else {
            var max = Math.max(1, (Number($rail.prop('scrollWidth')) || 0) - $rail.innerWidth());
            ratio = Math.min(1, Math.max(0, current / max));
            $prev.prop('disabled', current <= 2);
            $next.prop('disabled', current >= max - 2);
        }

        if ($progress.length) {
            $progress.css('width', Math.max(12, ratio * 100) + '%');
        }
    }

    /**
     * Builds a seamless three-part rail: cloned set + originals + cloned set.
     * The viewport starts on the originals and may wrap in either direction without
     * exposing an empty tail on very wide screens.
     *
     * @param {jQuery} $rail
     */
    function buildInfiniteRail($rail) {
        var $originals = $rail.children('.product-card').not('[data-rail-clone]');

        if (!$rail.length || !$originals.length) {
            return;
        }

        $rail.children('[data-rail-clone]').remove();

        var $before = $originals.clone(false, false)
            .attr('data-rail-clone', 'before')
            .attr('aria-hidden', 'true')
            .attr('tabindex', '-1');
        var $after = $originals.clone(false, false)
            .attr('data-rail-clone', 'after')
            .attr('aria-hidden', 'true')
            .attr('tabindex', '-1');

        $rail.prepend($before);
        $rail.append($after);

        var $firstOriginal = $rail.children('.product-card').not('[data-rail-clone]').first();
        var $firstAfter = $rail.children('[data-rail-clone="after"]').first();

        if (!$firstOriginal.length || !$firstAfter.length) {
            return;
        }

        var start = Number($firstOriginal.prop('offsetLeft')) || 0;
        var segment = (Number($firstAfter.prop('offsetLeft')) || 0) - start;

        $rail.data('railStart', start);
        $rail.data('railSegment', segment);
        setRailScrollImmediate($rail, start);
    }

    /**
     * Jumps one rail to an exact position without animation.
     * CSS does not own rail scrolling; visible arrow motion is handled by jQuery.animate(),
     * while clone-boundary normalization must stay immediate.
     *
     * @param {jQuery} $rail
     * @param {number} left
     */
    function setRailScrollImmediate($rail, left) {
        $rail.stop(true, false).scrollLeft(left);
    }

    /**
     * Moves an infinite rail back onto its original segment without any visible jump.
     * Wrapping is calculated in one operation instead of repeatedly mutating scrollLeft.
     *
     * @param {jQuery} $rail
     */
    function normalizeInfiniteRail($rail) {
        var start = Number($rail.data('railStart')) || 0;
        var segment = Number($rail.data('railSegment')) || 0;

        if (!$rail.length || segment <= 0) {
            return;
        }

        var relative = $rail.scrollLeft() - start;

        if (relative >= 0 && relative < segment) {
            return;
        }

        var wrapped = ((relative % segment) + segment) % segment;
        setRailScrollImmediate($rail, start + wrapped);
    }

    /**
     * Initializes one self-contained horizontal product rail.
     * A click remains a click; dragging begins only after a movement threshold.
     *
     * @param {jQuery} $rail
     */
    function initProductRail($rail) {
        var $scope = getRailScope($rail);
        var $prev = $scope.find('[data-rail-prev]').first();
        var $next = $scope.find('[data-rail-next]').first();
        var pointerDown = false;
        var dragging = false;
        var suppressClick = false;
        var activePointerId = null;
        var startX = 0;
        var startScroll = 0;
        var scrollTimer = null;

        /** Stops drag state and suppresses only the click generated by an actual drag. */
        function stopDrag(event) {
            var original = event.originalEvent || event;
            var pointerId = original.pointerId;

            if (!pointerDown || pointerId !== activePointerId) {
                return;
            }

            var wasDragging = dragging;
            var rail = $rail.get(0);

            pointerDown = false;
            dragging = false;
            activePointerId = null;
            $rail.removeClass('is-dragging');

            if (rail.hasPointerCapture && rail.hasPointerCapture(pointerId)) {
                rail.releasePointerCapture(pointerId);
            }

            normalizeInfiniteRail($rail);
            updateRailControls($rail);

            if (wasDragging) {
                suppressClick = true;
                window.setTimeout(function () {
                    suppressClick = false;
                }, 0);
            }
        }

        $prev.on('click.zapalRail', function () {
            $rail.stop(true).animate(
                { scrollLeft: $rail.scrollLeft() - getRailStep($rail) },
                320,
                function () {
                    normalizeInfiniteRail($rail);
                    updateRailControls($rail);
                }
            );
        });

        $next.on('click.zapalRail', function () {
            $rail.stop(true).animate(
                { scrollLeft: $rail.scrollLeft() + getRailStep($rail) },
                320,
                function () {
                    normalizeInfiniteRail($rail);
                    updateRailControls($rail);
                }
            );
        });

        $rail.on('scroll.zapalRail', function () {
            updateRailControls($rail);

            window.clearTimeout(scrollTimer);
            scrollTimer = window.setTimeout(function () {
                if (!pointerDown) {
                    normalizeInfiniteRail($rail);
                    updateRailControls($rail);
                }
            }, 120);
        });

        $rail.on('pointerdown.zapalRail', function (event) {
            var original = event.originalEvent;
            if (original.pointerType === 'touch' || original.button !== 0) {
                return;
            }

            pointerDown = true;
            dragging = false;
            activePointerId = original.pointerId;
            startX = original.clientX;
            startScroll = $rail.scrollLeft();
        });

        $rail.on('pointermove.zapalRail', function (event) {
            var original = event.originalEvent;
            if (!pointerDown || original.pointerId !== activePointerId) {
                return;
            }

            var deltaX = original.clientX - startX;
            var rail = $rail.get(0);

            if (!dragging && Math.abs(deltaX) >= config.railDragThreshold) {
                dragging = true;
                $rail.addClass('is-dragging');
                if (rail.setPointerCapture) {
                    rail.setPointerCapture(original.pointerId);
                }
            }

            if (!dragging) {
                return;
            }

            event.preventDefault();
            $rail.scrollLeft(startScroll - deltaX);
        });

        $rail.on('pointerup.zapalRail pointercancel.zapalRail', stopDrag);
        $rail.on('pointerleave.zapalRail', function (event) {
            if (pointerDown && dragging) {
                stopDrag(event);
                return;
            }
            pointerDown = false;
            activePointerId = null;
        });

        $rail.on('click.zapalRail', 'a', function (event) {
            if (!suppressClick) {
                return;
            }
            event.preventDefault();
            event.stopImmediatePropagation();
        });

        $(window).on('resize.zapalRail', function () {
            var currentStep = getRailStep($rail);
            var logicalIndex = currentStep > 0
                ? Math.round(($rail.scrollLeft() - (Number($rail.data('railStart')) || 0)) / currentStep)
                : 0;

            buildInfiniteRail($rail);
            setRailScrollImmediate(
                $rail,
                (Number($rail.data('railStart')) || 0) + logicalIndex * getRailStep($rail)
            );
            normalizeInfiniteRail($rail);
            updateRailControls($rail);
        });

        buildInfiniteRail($rail);
        updateRailControls($rail);
    }

    /** Initializes all horizontal product rails independently. */
    function initProductRails() {
        $('[data-product-rail]').each(function () {
            initProductRail($(this));
        });
    }

    /**
     * Attaches a reusable fallback handler to images that declare data-fallback.
     * Markup owns the fallback URL; JS only switches the source after a load error.
     */
    function initImageFallbacks() {
        $('img[data-fallback]').each(function () {
            var $image = $(this);
            var fallback = String($image.data('fallback') || '');

            if (!fallback) {
                return;
            }

            $image.off('error.zapalFallback').one('error.zapalFallback', function () {
                if ($image.attr('src') !== fallback) {
                    $image.attr('src', fallback);
                }
            });
        });
    }

    /**
     * Updates one recipe inside one gallery from the clicked recipe control.
     * Data attributes on the control provide image/title/fallback, avoiding page-specific JS.
     *
     * @param {jQuery} $gallery
     * @param {jQuery} $recipeButton
     */
    function setGalleryRecipe($gallery, $recipeButton) {
        if (!$recipeButton.length) {
            return;
        }

        var recipeId = String($recipeButton.data('recipeNav') || '');
        var title = String($recipeButton.data('recipeTitle') || $.trim($recipeButton.text()));
        var source = String($recipeButton.data('recipeImage') || '');
        var fallback = String($recipeButton.data('recipeFallback') || '');
        var $recipeImage = $gallery.find('[data-recipe-hero]').first();
        var $recipeTitle = $gallery.find('[data-recipe-image-title]').first();

        $gallery.find('[data-recipe-nav]').removeClass('active');
        $recipeButton.addClass('active');

        $gallery.find('[data-dish-panel]').each(function () {
            var $panel = $(this);
            var active = String($panel.data('dishPanel')) === recipeId;
            $panel.prop('hidden', !active).toggleClass('active', active);
        });

        if ($recipeImage.length && source) {
            $recipeImage.off('error.zapalRecipe').attr({ src: source, alt: title });
            if (fallback) {
                $recipeImage.one('error.zapalRecipe', function () {
                    $(this).attr('src', fallback);
                });
            }
        }

        $recipeTitle.text(title);
        $gallery.find('.gallery-recipe-select').val(recipeId);
    }

    /**
     * Creates the compact mobile recipe select from existing child recipe buttons.
     * The buttons remain the source of truth; no duplicate list is hardcoded in JS.
     *
     * @param {jQuery} $gallery
     * @returns {jQuery}
     */
    function buildRecipeSelect($gallery) {
        var $subnav = $gallery.find('[data-recipe-subnav]').first();
        var $buttons = $gallery.find('[data-recipe-nav]');

        if (!$subnav.length || !$buttons.length) {
            return $();
        }

        var $wrap = $('<div>', {
            class: 'gallery-recipe-select-wrap',
            hidden: true
        });
        var $label = $('<label>').text('Оберіть рецепт');
        var $select = $('<select>', {
            class: 'gallery-recipe-select',
            'aria-label': 'Оберіть рецепт'
        });

        $buttons.each(function () {
            var $button = $(this);
            $('<option>', {
                value: String($button.data('recipeNav') || ''),
                text: String($button.data('recipeTitle') || $.trim($button.text()))
            }).appendTo($select);
        });

        $wrap.append($label, $select);
        ($subnav.closest('.gallery-nav').length ? $subnav.closest('.gallery-nav') : $subnav.parent()).append($wrap);

        $select.on('change.zapalRecipe', function () {
            var value = String($(this).val());
            var $button = $buttons.filter(function () {
                return String($(this).data('recipeNav')) === value;
            }).first();
            $button.trigger('click');
        });

        return $wrap;
    }

    /**
     * Switches the main gallery tab and reveals recipe controls only in recipe mode.
     *
     * @param {jQuery} $gallery
     * @param {jQuery} $button
     * @param {jQuery} $recipeSelectWrap
     */
    function setGalleryTab($gallery, $button, $recipeSelectWrap) {
        var tabId = String($button.data('galleryButton') || '');
        var recipeMode = tabId === 'recipes';
        var packMode = tabId === 'pack';

        $gallery.find('[data-gallery-button]').removeClass('active');
        $button.addClass('active');

        $gallery.find('[data-gallery-panel]').each(function () {
            $(this).toggleClass('active', String($(this).data('galleryPanel')) === tabId);
        });

        $gallery.toggleClass('is-recipes', recipeMode);
        $gallery.toggleClass('is-pack', packMode);
        $gallery.find('[data-gallery-recipes]').prop('hidden', !recipeMode);
        $gallery.find('[data-recipe-subnav]').prop('hidden', !recipeMode);
        $recipeSelectWrap.prop('hidden', !recipeMode);

        if (recipeMode) {
            var $activeRecipe = $gallery.find('[data-recipe-nav].active').first();
            setGalleryRecipe($gallery, $activeRecipe.length ? $activeRecipe : $gallery.find('[data-recipe-nav]').first());
        }
    }

    /** Initializes one product gallery with tabs, nested recipes and mobile recipe select. */
    function initGallery($gallery) {
        var $recipeSelectWrap = buildRecipeSelect($gallery);

        $gallery.on('click.zapalGallery', '[data-gallery-button]', function () {
            setGalleryTab($gallery, $(this), $recipeSelectWrap);
        });

        $gallery.on('click.zapalGallery', '[data-recipe-nav]', function (event) {
            event.stopPropagation();
            var $recipeButton = $(this);
            var $recipeMain = $gallery.find('[data-gallery-button="recipes"]').first();

            if ($recipeMain.length && !$recipeMain.hasClass('active')) {
                setGalleryTab($gallery, $recipeMain, $recipeSelectWrap);
            }

            setGalleryRecipe($gallery, $recipeButton);
        });
    }

    /** Initializes every product gallery independently. */
    function initGalleries() {
        $('.gallery').each(function () {
            initGallery($(this));
        });
    }

    /**
     * Prefills the nearest product enquiry form when the specification CTA is clicked.
     * Product name comes from the form itself rather than a page-specific constant.
     */
    function initSpecificationRequests() {
        $(document).on('click.zapalSpecification', '[data-spec-request]', function () {
            var target = $(this).attr('href') || '#ask';
            var $form = $(target).find('form').first();

            if (!$form.length) {
                $form = $('#ask form').first();
            }

            if (!$form.length) {
                return;
            }

            var product = $.trim($form.find('[name="product"]').val() || '') || 'продукт';
            var $message = $form.find('[name="message"]').first();

            if ($message.length && !$.trim($message.val() || '')) {
                $message.val('Хочу отримати технічну специфікацію для ' + product + '.');
            }
        });
    }

    /**
     * Updates active catalogue category using current section visibility.
     * Links and sections are discovered from hrefs, not a duplicated JS category list.
     */
    function initCatalogNavigation() {
        var $nav = $('[data-catalog-nav]').first();
        if (!$nav.length || !('IntersectionObserver' in window)) {
            return;
        }

        var $links = $nav.find('a[href^="#"]');
        var sections = [];

        $links.each(function (index) {
            if (index === 0) {
                return;
            }
            var selector = $(this).attr('href');
            var $section = $(selector);
            if ($section.length) {
                sections.push($section.get(0));
            }
        });

        /** Applies active state to the nav link whose href matches the visible section. */
        function setActiveCategory(id) {
            $links.removeClass('active').filter('[href="#' + id + '"]').addClass('active');
        }

        var observer = new IntersectionObserver(function (entries) {
            var visible = $.grep(entries, function (entry) {
                return entry.isIntersecting;
            }).sort(function (a, b) {
                return Math.abs(a.boundingClientRect.top) - Math.abs(b.boundingClientRect.top);
            })[0];

            if (visible && visible.target && visible.target.id) {
                setActiveCategory(visible.target.id);
            }
        }, {
            rootMargin: '-145px 0px -58% 0px',
            threshold: [0, 0.05, 0.2]
        });

        $.each(sections, function (_, section) {
            observer.observe(section);
        });
    }

    /** Initializes smooth scrolling for real in-page anchors. */
    function initSmoothAnchors() {
        $(document).on('click.zapalAnchors', 'a[href^="#"]', function (event) {
            var selector = $(this).attr('href');
            if (!selector || selector === '#') {
                return;
            }

            var $target = $(selector);
            if (!$target.length) {
                return;
            }

            event.preventDefault();
            $('html, body').stop(true).animate({ scrollTop: $target.offset().top }, 420);
        });
    }

    /**
     * Adds lightweight image-copy deterrence.
     * This is presentation friction only; it is not treated as asset security.
     */
    function initImageDeterrence() {
        $('img')
            .attr('draggable', 'false')
            .on('dragstart.zapalImages contextmenu.zapalImages', function (event) {
                event.preventDefault();
            });
    }

    /**
     * Opens a lightbox for a figure and builds its navigation group from the nearest showcase.
     *
     * @param {jQuery} $box
     * @param {jQuery} $figure
     */
    function openLightbox($box, $figure) {
        var $showcase = $figure.closest('.recipe-showcase');
        var $group = $showcase.length ? $showcase.find('.recipe-grid figure') : $figure;
        var currentIndex = $group.index($figure);

        $box.data('group', $group).data('index', Math.max(0, currentIndex));
        renderLightbox($box);
        $box.addClass('is-open');
        $('body').addClass('lightbox-open');
        $box.find('.media-lightbox-close').trigger('focus');
    }

    /**
     * Renders the current lightbox item using data stored on the lightbox element.
     *
     * @param {jQuery} $box
     */
    function renderLightbox($box) {
        var $group = $box.data('group');
        var index = Number($box.data('index')) || 0;

        if (!$group || !$group.length) {
            return;
        }

        var $figure = $group.eq(index);
        var $image = $figure.find('img').first();
        var caption = $.trim($figure.find('figcaption').text()) || $image.attr('alt') || '';

        $box.find('.media-lightbox-stage img').attr({
            src: $image.attr('src'),
            alt: $image.attr('alt') || ''
        });
        $box.find('.media-lightbox-caption').text(caption);
        $box.find('.media-lightbox-nav').prop('hidden', $group.length < 2);
    }

    /**
     * Moves the current lightbox index and re-renders.
     *
     * @param {jQuery} $box
     * @param {number} delta
     */
    function moveLightbox($box, delta) {
        var $group = $box.data('group');
        if (!$group || !$group.length) {
            return;
        }

        var current = Number($box.data('index')) || 0;
        $box.data('index', (current + delta + $group.length) % $group.length);
        renderLightbox($box);
    }

    /** Closes the image lightbox and restores page scrolling. */
    function closeLightbox($box) {
        $box.removeClass('is-open');
        $('body').removeClass('lightbox-open');
    }

    /** Initializes the recipe/reference image lightbox without external plugins. */
    function initLightbox() {
        var $figures = $('.recipe-grid figure');
        if (!$figures.length) {
            return;
        }

        var $box = $(
            '<div class="media-lightbox" role="dialog" aria-modal="true" aria-label="Перегляд зображення">' +
            '<button class="media-lightbox-close" type="button" aria-label="Закрити">×</button>' +
            '<button class="media-lightbox-nav media-lightbox-prev" type="button" aria-label="Попереднє зображення">←</button>' +
            '<div class="media-lightbox-stage"><img alt=""><div class="media-lightbox-caption"></div></div>' +
            '<button class="media-lightbox-nav media-lightbox-next" type="button" aria-label="Наступне зображення">→</button>' +
            '</div>'
        ).appendTo('body');

        $figures.each(function () {
            var $figure = $(this);
            var caption = $.trim($figure.find('figcaption').text()) || 'Зображення';
            $figure.attr({
                tabindex: 0,
                role: 'button',
                'aria-label': caption + ' — відкрити у великому розмірі'
            });
        });

        $figures.on('click.zapalLightbox', function () {
            openLightbox($box, $(this));
        });

        $figures.on('keydown.zapalLightbox', function (event) {
            if (event.key !== 'Enter' && event.key !== ' ') {
                return;
            }
            event.preventDefault();
            openLightbox($box, $(this));
        });

        $box.on('click.zapalLightbox', '.media-lightbox-close', function () {
            closeLightbox($box);
        });
        $box.on('click.zapalLightbox', '.media-lightbox-prev', function () {
            moveLightbox($box, -1);
        });
        $box.on('click.zapalLightbox', '.media-lightbox-next', function () {
            moveLightbox($box, 1);
        });
        $box.on('click.zapalLightbox', function (event) {
            if (event.target === this) {
                closeLightbox($box);
            }
        });

        $(document).on('keydown.zapalLightbox', function (event) {
            if (!$box.hasClass('is-open')) {
                return;
            }
            if (event.key === 'Escape') {
                closeLightbox($box);
            } else if (event.key === 'ArrowLeft') {
                moveLightbox($box, -1);
            } else if (event.key === 'ArrowRight') {
                moveLightbox($box, 1);
            }
        });
    }

    /**
     * Clears field-level error styling in a form.
     *
     * @param {jQuery} $form
     */
    function clearFormErrors($form) {
        $form.find('.is-error').removeClass('is-error').removeAttr('aria-invalid');
    }

    /**
     * Marks backend-reported fields using input names.
     * Expected backend format can be an object such as {email: '...', phone: '...'}.
     * Unknown keys are ignored safely.
     *
     * @param {jQuery} $form
     * @param {Object|Array|null} errors
     */
    function applyBackendFieldErrors($form, errors) {
        if (!errors) {
            return;
        }

        if ($.isArray(errors)) {
            $.each(errors, function (_, fieldName) {
                $form.find('[name="' + String(fieldName).replace(/"/g, '\\"') + '"]').addClass('is-error').attr('aria-invalid', 'true');
            });
            return;
        }

        $.each(errors, function (fieldName) {
            $form.find('[name="' + String(fieldName).replace(/"/g, '\\"') + '"]').addClass('is-error').attr('aria-invalid', 'true');
        });
    }

    /**
     * Checks only whether required fields contain a value.
     * Format, business rules and semantic validity are intentionally left to the backend.
     *
     * @param {jQuery} $form
     * @returns {boolean}
     */
    function validateRequiredFields($form) {
        var valid = true;
        clearFormErrors($form);

        $form.find('[required]').each(function () {
            var $field = $(this);
            var type = String($field.attr('type') || '').toLowerCase();
            var empty;

            if (type === 'checkbox' || type === 'radio') {
                var name = $field.attr('name');
                empty = name ? !$form.find('[name="' + name + '"]:checked').length : !$field.is(':checked');
            } else {
                empty = !$.trim(String($field.val() || ''));
            }

            if (!empty) {
                return;
            }

            valid = false;
            $field.addClass('is-error').attr('aria-invalid', 'true');
        });

        return valid;
    }

    /**
     * Shows the persistent backend/frontend error panel placed directly after <body>.
     *
     * @param {string} message
     */
    function showResponseError(message) {
        var $error = $('[data-response-error]').first();
        if (!$error.length) {
            return;
        }

        $error.find('[data-response-error-text]').text(message || config.defaultErrorMessage);
        $error.prop('hidden', false).addClass('is-open');
        $error.find('[data-response-error-close]').trigger('focus');
    }

    /** Hides the response error panel without modifying any form state. */
    function hideResponseError() {
        $('[data-response-error]').removeClass('is-open').prop('hidden', true);
    }

    /**
     * Opens the success feedback modal. Server-provided text may replace defaults.
     *
     * @param {string} title
     * @param {string} message
     */
    function showFormSuccess(title, message) {
        var $modal = $('[data-form-feedback]').first();
        if (!$modal.length) {
            return;
        }

        $modal.attr('data-state', 'success').addClass('is-open');
        $modal.find('[data-form-feedback-mark]').text('✓');
        $modal.find('[data-form-feedback-title]').text(title || config.defaultSuccessTitle);
        $modal.find('[data-form-feedback-text]').text(message || config.defaultSuccessMessage);
        $('body').addClass('form-feedback-open');
        $modal.find('[data-form-feedback-close]').trigger('focus');
    }

    /** Closes the success feedback modal. */
    function closeFormFeedback() {
        $('[data-form-feedback]').removeClass('is-open');
        $('body').removeClass('form-feedback-open');
    }

    /**
     * Normalizes common backend response shapes, including WordPress-style {success,data}.
     *
     * @param {*} payload
     * @returns {{success:boolean,message:string,title:string,errors:*}}
     */
    function normalizeBackendResponse(payload) {
        var root = payload && typeof payload === 'object' ? payload : {};
        var data = root.data && typeof root.data === 'object' ? root.data : {};
        var success = root.success !== false && data.success !== false;

        return {
            success: success,
            title: root.title || data.title || '',
            message: root.message || data.message || '',
            errors: root.errors || data.errors || null
        };
    }

    /**
     * Resolves the AJAX endpoint from form markup.
     * Backend templates may use action="..." or data-ajax-url="..." without JS changes.
     *
     * @param {jQuery} $form
     * @returns {string}
     */
    function getFormEndpoint($form) {
        return $.trim(String($form.data('ajaxUrl') || $form.attr('action') || ''));
    }

    /**
     * Applies/removes the sending state without assuming a particular button label.
     * Original text is stored on the button itself and restored afterwards.
     *
     * @param {jQuery} $form
     * @param {boolean} sending
     */
    function setFormSending($form, sending) {
        var $button = $form.find('[type="submit"]').first();
        $form.toggleClass('is-sending', sending);

        if (!$button.length) {
            return;
        }

        if (sending) {
            $button.data('originalText', $button.text()).prop('disabled', true).text('Надсилаємо…');
            return;
        }

        $button.prop('disabled', false).text($button.data('originalText') || $button.text());
    }

    /**
     * Submits a valid form to the endpoint supplied by markup.
     * The static prototype has no backend endpoint; in that case it keeps the approved
     * success demo so visual review remains possible. Once action/data-ajax-url exists,
     * this function automatically switches to real AJAX.
     *
     * @param {jQuery} $form
     */
    function submitFormAjax($form) {
        var endpoint = getFormEndpoint($form);
        var method = String($form.attr('method') || 'POST').toUpperCase();

        hideResponseError();
        setFormSending($form, true);

        if (!endpoint || endpoint === '#') {
            window.setTimeout(function () {
                setFormSending($form, false);
                showFormSuccess(config.defaultSuccessTitle, config.defaultSuccessMessage);
            }, 420);
            return;
        }

        $.ajax({
            url: endpoint,
            type: method,
            data: $form.serialize(),
            dataType: 'json'
        })
            .done(function (payload) {
                var response = normalizeBackendResponse(payload);
                setFormSending($form, false);

                if (!response.success) {
                    applyBackendFieldErrors($form, response.errors);
                    showResponseError(response.message || config.defaultErrorMessage);
                    return;
                }

                showFormSuccess(response.title || config.defaultSuccessTitle, response.message || config.defaultSuccessMessage);
            })
            .fail(function (xhr) {
                var payload = xhr.responseJSON || {};
                var response = normalizeBackendResponse(payload);

                setFormSending($form, false);
                applyBackendFieldErrors($form, response.errors);
                showResponseError(response.message || config.defaultErrorMessage);
            });
    }

    /**
     * Initializes form validation, AJAX submission and server/frontend error UI.
     */
    function initForms() {
        $(document).on('click.zapalResponseError', '[data-response-error-close]', hideResponseError);
        $(document).on('click.zapalFeedback', '[data-form-feedback-close]', closeFormFeedback);

        $(document).on('click.zapalFeedback', '[data-form-feedback]', function (event) {
            if (event.target === this) {
                closeFormFeedback();
            }
        });

        $(document).on('keydown.zapalForms', function (event) {
            if (event.key !== 'Escape') {
                return;
            }
            hideResponseError();
            closeFormFeedback();
        });

        $(document).on('input.zapalForms change.zapalForms', 'form [required], form .is-error', function () {
            $(this).removeClass('is-error').removeAttr('aria-invalid');
        });

        $('form').on('submit.zapalForms', function (event) {
            event.preventDefault();
            var $form = $(this);

            if (!validateRequiredFields($form)) {
                showResponseError('Заповніть, будь ласка, усі обов’язкові поля.');
                return;
            }

            submitFormAjax($form);
        });
    }

    // Component bootstrap. Each initializer is independent and exits when markup is absent.
    initPreloader();
    initTheme();
    initMobileMenu();
    initHeaderState();
    initRevealAnimations();
    initProductTilt();
    initProductRails();
    initImageFallbacks();
    initGalleries();
    initSpecificationRequests();
    initCatalogNavigation();
    initSmoothAnchors();
    initImageDeterrence();
    initLightbox();
    initForms();
});
