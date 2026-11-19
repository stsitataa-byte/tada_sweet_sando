/* =========================================================
   TADA SWEETSANDO - menu.js
   Menu tiga kolom (video kedua). Satu halaman, banyak varian.
   Foto berubah HANYA saat varian berubah (bukan saat ukuran).
   ========================================================= */
(function () {
    'use strict';

    var root = document.getElementById('menu');
    if (!root || !window.TADA) return;

    var catalog = window.TADA.catalog;
    var slug = root.getAttribute('data-slug');
    var urlPattern = root.getAttribute('data-url');
    var size = 'small';
    var qty = 1;

    var NUTRI = [
        ['energi', 'Energi'],
        ['karbohidrat', 'Karbohidrat'],
        ['protein', 'Protein'],
        ['lemak', 'Lemak'],
        ['serat', 'Serat'],
        ['gula', 'Gula']
    ];

    var $ = function (id) { return document.getElementById(id); };
    var el = {
        name: $('pName'), stars: $('pStars'), desc: $('pDesc'),
        img: $('pImg'), ph: $('pPlaceholder'), path: $('pPath'),
        nutri: $('pNutri'), ingr: $('pIngr'), note: $('pNote'),
        price: $('pPrice'), qty: $('pQty'), add: $('pAdd'), msg: $('pMsg')
    };

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function message(text, isError) {
        el.msg.textContent = text || '';
        el.msg.classList.toggle('is-error', !!isError);
    }

    /* Bagian yang berubah saat VARIAN berubah */
    function renderVariant() {
        var p = catalog[slug];

        root.style.setProperty('--bg', p.color);
        root.style.setProperty('--bg-right', p.color_right || '#ffffff');
        root.style.setProperty('--accent', p.accent || '#2b1a14');

        el.name.textContent = p.title;

        var r = parseInt(p.rating, 10);
        if (r >= 1 && r <= 5) {
            el.stars.hidden = false;
            el.stars.textContent = '★★★★★'.slice(0, r) + '☆☆☆☆☆'.slice(0, 5 - r);
        } else {
            el.stars.hidden = true;
            el.stars.textContent = '';
        }

        el.desc.textContent = p.description || 'Deskripsi produk belum diisi.';

        /* foto: sama untuk Small dan Regular */
        el.img.style.animation = 'none';
        void el.img.offsetWidth;
        el.img.style.animation = '';
        if (p.image_url) {
            el.img.src = p.image_url;
            el.img.alt = 'Fruit sando ' + p.name;
            el.img.hidden = false;
            el.ph.hidden = true;
        } else {
            el.img.hidden = true;
            el.ph.hidden = false;
            el.path.textContent = 'public/' + p.image;
        }

        /* tabel gizi */
        var rows = '';
        NUTRI.forEach(function (n) {
            var v = p.nutrition ? p.nutrition[n[0]] : null;
            rows += '<tr><td>' + n[1] + '</td>' +
                (v ? '<td>' + esc(v) + '</td>' : '<td class="is-empty">belum diisi</td>') + '</tr>';
        });
        el.nutri.innerHTML = rows;

        /* daftar bahan */
        if (p.ingredients && p.ingredients.length) {
            el.ingr.innerHTML = p.ingredients.map(function (i) { return '<p>' + esc(i) + '</p>'; }).join('');
        } else {
            el.ingr.innerHTML = '<p class="is-empty" style="opacity:.55;font-style:italic">Bahan belum diisi</p>';
        }

        el.note.textContent = p.note || '';

        document.querySelectorAll('.variant-tab').forEach(function (t) {
            t.classList.toggle('is-active', t.getAttribute('data-variant') === slug);
        });

        document.title = p.name + ' | TADA SWEETSANDO';
        renderBuy();
    }

    /* Bagian yang berubah saat UKURAN / JUMLAH berubah (foto TIDAK berubah) */
    function renderBuy() {
        var p = catalog[slug];
        var priceText = p.prices_text ? p.prices_text[size] : null;

        document.querySelectorAll('.size-btn').forEach(function (b) {
            b.classList.toggle('is-active', b.getAttribute('data-size') === size);
        });

        el.qty.textContent = qty;
        el.price.textContent = priceText || 'Harga belum diisi';

        var canBuy = p.available && priceText;
        el.add.disabled = !canBuy;
        el.add.textContent = !p.available ? 'TIDAK TERSEDIA' : (priceText ? 'ADD TO CART' : 'HARGA BELUM DIISI');
    }

    function chooseVariant(newSlug) {
        if (!catalog[newSlug] || newSlug === slug) return;
        slug = newSlug;
        message('');
        renderVariant();
        if (urlPattern && window.history && history.replaceState) {
            history.replaceState(null, '', urlPattern.replace('__slug__', newSlug));
        }
    }

    document.querySelectorAll('.variant-tab').forEach(function (t) {
        t.addEventListener('click', function () { chooseVariant(t.getAttribute('data-variant')); });
    });

    document.querySelectorAll('.size-btn').forEach(function (b) {
        b.addEventListener('click', function () {
            size = b.getAttribute('data-size');
            message('');
            renderBuy();
        });
    });

    document.querySelectorAll('[data-qty]').forEach(function (b) {
        b.addEventListener('click', function () {
            qty = Math.min(99, Math.max(1, qty + parseInt(b.getAttribute('data-qty'), 10)));
            renderBuy();
        });
    });

    el.img.addEventListener('error', function () {
        el.img.hidden = true;
        el.ph.hidden = false;
        el.path.textContent = 'public/' + catalog[slug].image;
    });

    el.add.addEventListener('click', function () {
        el.add.disabled = true;
        message('Menambahkan...');
        window.TadaCart.add(slug, size, qty)
            .then(function () {
                message('Ditambahkan ke keranjang.');
                qty = 1;
            })
            .catch(function (err) { message(err.message, true); })
            .then(function () { renderBuy(); });
    });

    renderVariant();
})();
