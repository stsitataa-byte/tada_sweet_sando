/* =========================================================
   TADA SWEETSANDO - fx.js
   Hero berganti tema + parallax, dan dropdown SHOP beranimasi.
   ========================================================= */
(function () {
    'use strict';

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var fine = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    /* ---------- DROPDOWN SHOP ---------- */
    var btn = document.querySelector('[data-shop-btn]');
    var panel = document.getElementById('shopPanel');

    function setShop(open) {
        if (!btn || !panel) return;
        panel.classList.toggle('is-open', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    if (btn && panel) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            setShop(!panel.classList.contains('is-open'));
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.shop-wrap')) setShop(false);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') setShop(false);
        });
    }

    /* ---------- HERO ---------- */
    var hero = document.querySelector('[data-fx-hero]');
    if (!hero) return;

    var picks = [].slice.call(hero.querySelectorAll('[data-pick]'));
    var slides = [].slice.call(hero.querySelectorAll('[data-slide]'));
    var link = hero.querySelector('[data-hero-link]');
    var card = hero.querySelector('[data-hero-card]');
    var slugs = picks.map(function (p) { return p.getAttribute('data-pick'); });
    var linkBase = link ? link.getAttribute('href').replace(/[^/]+$/, '') : '';
    var current = hero.getAttribute('data-theme');
    var timer = null, swapTimer = null;

    function setTheme(slug, animate) {
        if (slugs.indexOf(slug) === -1) return;
        current = slug;
        hero.setAttribute('data-theme', slug);

        picks.forEach(function (p) {
            var on = p.getAttribute('data-pick') === slug;
            p.classList.toggle('is-active', on);
            p.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        slides.forEach(function (s) {
            s.classList.toggle('is-active', s.getAttribute('data-slide') === slug);
        });
        if (link) link.setAttribute('href', linkBase + slug);

        if (animate && !reduce) {
            hero.classList.remove('is-swapping');
            void hero.offsetWidth;
            hero.classList.add('is-swapping');
            clearTimeout(swapTimer);
            swapTimer = setTimeout(function () { hero.classList.remove('is-swapping'); }, 1200);
        }
    }

    function next() {
        var i = slugs.indexOf(current);
        setTheme(slugs[(i + 1) % slugs.length], true);
    }

    function startAuto() {
        if (reduce || timer || slugs.length < 2) return;
        timer = setInterval(function () {
            if (!document.hidden) next();
        }, 5500); // ganti 5500 untuk mengubah kecepatan (milidetik)
    }
    function stopAuto() { clearInterval(timer); timer = null; }

    picks.forEach(function (p) {
        p.addEventListener('click', function () {
            setTheme(p.getAttribute('data-pick'), true);
            stopAuto();
            startAuto();
        });
    });

    if (card) {
        card.addEventListener('pointerenter', stopAuto);
        card.addEventListener('pointerleave', startAuto);
    }

    /* latar bergeser mengikuti kursor */
    if (!reduce && fine) {
        var raf = 0, ev = null;
        hero.addEventListener('pointermove', function (e) {
            ev = e;
            if (raf) return;
            raf = requestAnimationFrame(function () {
                raf = 0;
                var r = hero.getBoundingClientRect();
                hero.style.setProperty('--px', (((ev.clientX - r.left) / r.width - 0.5) * 2).toFixed(3));
                hero.style.setProperty('--py', (((ev.clientY - r.top) / r.height - 0.5) * 2).toFixed(3));
            });
        });
        hero.addEventListener('pointerleave', function () {
            hero.style.setProperty('--px', 0);
            hero.style.setProperty('--py', 0);
        });
    }

    hero.classList.add('is-swapping'); // animasi masuk saat pertama dibuka
    setTimeout(function () { hero.classList.remove('is-swapping'); }, 1200);
    startAuto();
})();