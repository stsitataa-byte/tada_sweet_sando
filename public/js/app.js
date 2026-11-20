/* =========================================================
   TADA SWEETSANDO - app.js
   Header, keranjang (cart drawer), FAQ, tab cerita, showcase.
   Semua perhitungan harga dilakukan di server (Laravel).
   ========================================================= */
(function () {
    'use strict';

    var T = window.TADA || {};
    var body = document.body;

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    /* ---------- Request ke backend Laravel ---------- */
    function api(url, method, payload) {
        return fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': T.csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: payload ? JSON.stringify(payload) : undefined
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (data) {
                if (!res.ok) {
                    var msg = data.message;
                    if (data.errors) {
                        var first = Object.keys(data.errors)[0];
                        msg = data.errors[first][0];
                    }
                    throw new Error(msg || 'Terjadi kesalahan. Coba lagi.');
                }
                return data;
            });
        });
    }

    /* =====================================================
       KERANJANG
       ===================================================== */
    var drawer = document.getElementById('cartDrawer');
    var cartBody = document.querySelector('[data-cart-body]');
    var cartFoot = document.querySelector('[data-cart-foot]');
    var cartSub = document.querySelector('[data-cart-sub]');
    var state = T.cart || { items: [], count: 0, total: 0, total_text: 'Rp0' };

    function itemHtml(it) {
        var thumb = it.image_url
            ? '<img src="' + esc(it.image_url) + '" alt="' + esc(it.name) + '">'
            : '<small>foto belum ada</small>';
        return '' +
            '<div class="cart-item" data-key="' + esc(it.key) + '">' +
                '<div class="cart-item__thumb" style="background:' + esc(it.color) + '">' + thumb + '</div>' +
                '<div class="cart-item__info">' +
                    '<b>' + esc(it.name) + '</b>' +
                    '<span>Ukuran: ' + esc(it.size_label) + '</span>' +
                    '<span>' + esc(it.unit_text) + '</span>' +
                    '<div class="qty">' +
                        '<button type="button" data-act="dec" aria-label="Kurangi"><svg width="12" height="12"><use href="#i-minus"/></svg></button>' +
                        '<span>' + it.qty + '</span>' +
                        '<button type="button" data-act="inc" aria-label="Tambah"><svg width="12" height="12"><use href="#i-plus"/></svg></button>' +
                    '</div>' +
                '</div>' +
                '<div class="cart-item__side">' +
                    '<button type="button" class="cart-item__remove" data-act="remove" aria-label="Hapus"><svg width="18" height="18"><use href="#i-trash"/></svg></button>' +
                    '<span class="cart-item__sub">' + esc(it.subtotal_text) + '</span>' +
                '</div>' +
            '</div>';
    }

    function renderCart(prevCount) {
        if (!cartBody) return;

        var n = state.count;

        document.querySelectorAll('[data-cart-count]').forEach(function (el) {
            el.textContent = n;
            if (typeof prevCount === 'number' && prevCount !== n) {
                el.classList.remove('bump');
                void el.offsetWidth;
                el.classList.add('bump');
            }
        });

        if (cartSub) cartSub.textContent = n + (n === 1 ? ' item' : ' items');

        if (!state.items.length) {
            cartBody.innerHTML =
                '<div class="cart-empty">' +
                    '<svg width="72" height="72"><use href="#i-strawberry"/></svg>' +
                    '<h3>Cart kosong</h3>' +
                    '<p>Belum ada fruit sando di keranjangmu.</p>' +
                    '<a class="btn btn--pink" href="' + esc(T.routes.shop) + '">Mulai belanja</a>' +
                '</div>';
            if (cartFoot) cartFoot.hidden = true;
            return;
        }

        cartBody.innerHTML = state.items.map(itemHtml).join('');
        if (cartFoot) cartFoot.hidden = false;

        var sub = document.querySelector('[data-cart-subtotal]');
        var tot = document.querySelector('[data-cart-total]');
        if (sub) sub.textContent = state.total_text;
        if (tot) tot.textContent = state.total_text;
    }

    function setState(data) {
        var prev = state.count;
        state = data;
        renderCart(prev);
    }

    function openCart() {
        if (!drawer) return;
        body.classList.add('cart-open');
        drawer.setAttribute('aria-hidden', 'false');
    }

    function closeCart() {
        if (!drawer) return;
        body.classList.remove('cart-open');
        drawer.setAttribute('aria-hidden', 'true');
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-cart-open]')) { openCart(); return; }
        if (e.target.closest('[data-cart-close]')) { closeCart(); return; }

        var btn = e.target.closest('[data-act]');
        if (!btn || !btn.closest('.cart-item')) return;

        var row = btn.closest('.cart-item');
        var key = row.getAttribute('data-key');
        var item = state.items.filter(function (i) { return i.key === key; })[0];
        if (!item) return;

        var act = btn.getAttribute('data-act');
        var req;

        if (act === 'remove') {
            req = api(T.routes.remove, 'DELETE', { key: key });
        } else {
            var qty = item.qty + (act === 'inc' ? 1 : -1);
            req = api(T.routes.update, 'PATCH', { key: key, qty: qty });
        }

        req.then(setState).catch(function (err) { alert(err.message); });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeCart();
            closeShop();
        }
    });

    /* API publik untuk halaman lain (menu.js) */
    window.TadaCart = {
        add: function (slug, size, qty) {
            return api(T.routes.add, 'POST', { slug: slug, size: size, qty: qty }).then(function (data) {
                setState(data);
                openCart();
                return data;
            });
        },
        open: openCart,
        close: closeCart
    };

    renderCart();

    /* =====================================================
       DROPDOWN SHOP
       ===================================================== */
    var shopBtn = document.querySelector('[data-shop-toggle]');
    var shopMenu = document.getElementById('shopMenu');

    function closeShop() {
        if (!shopMenu || shopMenu.hidden) return;
        shopMenu.hidden = true;
        shopBtn.setAttribute('aria-expanded', 'false');
    }

    if (shopBtn && shopMenu) {
        shopBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = shopMenu.hidden;
            shopMenu.hidden = !open;
            shopBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.shop-wrap')) closeShop();
        });
    }

    /* =====================================================
       FAQ ACCORDION
       ===================================================== */
    document.querySelectorAll('.faq__q').forEach(function (q) {
        q.addEventListener('click', function () {
            var open = q.getAttribute('aria-expanded') === 'true';
            q.setAttribute('aria-expanded', open ? 'false' : 'true');
        });
    });

    /* =====================================================
       TAB CERITA (home)
       ===================================================== */
    document.querySelectorAll('.tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            var id = tab.getAttribute('data-tab');
            document.querySelectorAll('.tab').forEach(function (t) { t.classList.toggle('is-active', t === tab); });
            document.querySelectorAll('.story__panel').forEach(function (p) {
                p.classList.toggle('is-active', p.getAttribute('data-panel') === id);
            });
        });
    });

    /* =====================================================
       SHOWCASE (home)
       ===================================================== */
    var showcase = document.getElementById('showcase');
    if (showcase && T.catalog) {
        var photo = showcase.querySelector('[data-show-photo]');
        var nameEl = showcase.querySelector('[data-show-name]');
        var priceEl = showcase.querySelector('[data-show-price]');
        var linkEl = showcase.querySelector('[data-show-link]');
        var base = linkEl ? linkEl.getAttribute('href') : '';

        showcase.querySelectorAll('[data-show-slug]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var p = T.catalog[btn.getAttribute('data-show-slug')];
                if (!p) return;

                showcase.querySelectorAll('[data-show-slug]').forEach(function (b) { b.classList.toggle('is-active', b === btn); });
                showcase.style.background = p.color;
                nameEl.textContent = p.name;
                priceEl.textContent = p.price_from ? 'Mulai ' + p.price_from : 'Harga belum diisi';
                linkEl.setAttribute('href', base.replace(/[^/]+$/, '') + p.slug);

                photo.innerHTML = p.image_url
                    ? '<img class="showcase__img" src="' + esc(p.image_url) + '" alt="' + esc(p.name) + '">'
                    : '<div class="img-placeholder"><span>Taruh gambar di<br><code>public/' + esc(p.image) + '</code></span></div>';
            });
        });
    }

})();


/* =========================================================
   EFEK BAGIAN MISI: tilt 3D, glare, titik halftone, spotlight kartu
   Hanya aktif untuk mouse, mati jika "reduce motion".
   ========================================================= */
(function () {
    'use strict';

    var root = document.querySelector('[data-fx-mission]');
    if (!root) return;

    var mq = function (q) { return window.matchMedia && window.matchMedia(q).matches; };
    var reduce = mq('(prefers-reduced-motion: reduce)');
    var fine = mq('(hover: hover) and (pointer: fine)');
    var points = root.querySelectorAll('.mission__point');

    /* 1. Kartu muncul bertahap saat di-scroll */
    if (!reduce && 'IntersectionObserver' in window) {
        root.classList.add('fx-ready');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
            });
        }, { threshold: 0.2 });
        points.forEach(function (p) { io.observe(p); });
    }

    if (reduce || !fine) return;

    /* 2. Cahaya yang mengikuti kursor di dalam kartu */
    points.forEach(function (p) {
        p.addEventListener('pointermove', function (e) {
            var r = p.getBoundingClientRect();
            p.style.setProperty('--mx', (e.clientX - r.left) + 'px');
            p.style.setProperty('--my', (e.clientY - r.top) + 'px');
        });
    });

    var zone = root.querySelector('.mission__art');
    var frame = root.querySelector('.mission__frame');
    var cv = root.querySelector('.mission__dots');
    if (!zone || !frame || !cv) return;

    /* 3. Tilt 3D + glare */
    var evt = null, tiltRaf = 0;

    function tilt() {
        tiltRaf = 0;
        if (!evt) return;
        var r = zone.getBoundingClientRect();
        var x = (evt.clientX - r.left) / r.width;
        var y = (evt.clientY - r.top) / r.height;
        var cx = Math.max(-1, Math.min(1, (x - 0.5) * 2));
        var cy = Math.max(-1, Math.min(1, (y - 0.5) * 2));
        frame.style.setProperty('--ry', (cx * 12).toFixed(2) + 'deg');
        frame.style.setProperty('--rx', (-cy * 10).toFixed(2) + 'deg');
        frame.style.setProperty('--gx', (x * 100).toFixed(1) + '%');
        frame.style.setProperty('--gy', (y * 100).toFixed(1) + '%');
    }

    /* 4. Medan titik halftone yang bereaksi pada kursor */
    var ctx = cv.getContext('2d');
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    var w = 0, h = 0, mx = -999, my = -999, tx = -999, ty = -999;
    var strength = 0, active = false, dotRaf = 0;
    var GAP = 26, RADIUS = 170;

    function resize() {
        var r = zone.getBoundingClientRect();
        w = r.width; h = r.height;
        cv.width = Math.round(w * dpr);
        cv.height = Math.round(h * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        draw();
    }

    function draw() {
        ctx.clearRect(0, 0, w, h);
        for (var y = GAP / 2; y < h; y += GAP) {
            for (var x = GAP / 2; x < w; x += GAP) {
                var dx = x - mx, dy = y - my;
                var d = Math.sqrt(dx * dx + dy * dy) || 1;
                var k = Math.max(0, 1 - d / RADIUS);
                k = k * k * (3 - 2 * k) * strength;
                var px = x + (dx / d) * k * 7;
                var py = y + (dy / d) * k * 7;
                ctx.fillStyle = k > 0.02
                    ? 'rgba(238,95,147,' + (0.3 + k * 0.6).toFixed(2) + ')'
                    : 'rgba(43,26,20,0.2)';
                ctx.beginPath();
                ctx.arc(px, py, 1.4 + k * 8, 0, 6.2832);
                ctx.fill();
            }
        }
    }

    function dotLoop() {
        mx += (tx - mx) * 0.2;
        my += (ty - my) * 0.2;
        strength += ((active ? 1 : 0) - strength) * 0.12;
        draw();
        if (active || strength > 0.01) { dotRaf = requestAnimationFrame(dotLoop); }
        else { dotRaf = 0; strength = 0; draw(); }
    }

    zone.addEventListener('pointerenter', function (e) {
        if (e.pointerType === 'touch') return;
        var r = zone.getBoundingClientRect();
        mx = tx = e.clientX - r.left;
        my = ty = e.clientY - r.top;
        active = true;
        frame.classList.add('is-active');
        if (!dotRaf) dotRaf = requestAnimationFrame(dotLoop);
    });

    zone.addEventListener('pointermove', function (e) {
        if (e.pointerType === 'touch') return;
        var r = zone.getBoundingClientRect();
        tx = e.clientX - r.left;
        ty = e.clientY - r.top;
        evt = e;
        if (!tiltRaf) tiltRaf = requestAnimationFrame(tilt);
    });

    zone.addEventListener('pointerleave', function () {
        active = false;
        evt = null;
        frame.classList.remove('is-active');
        frame.style.setProperty('--rx', '0deg');
        frame.style.setProperty('--ry', '0deg');
        if (!dotRaf) dotRaf = requestAnimationFrame(dotLoop);
    });

    window.addEventListener('resize', resize);
    resize();
})();
