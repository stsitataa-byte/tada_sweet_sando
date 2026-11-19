<<<<<<< HEAD
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
=======
(function () {
  const D = window.SANDO;
  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
  const rp = amount => 'Rp ' + Number(amount).toLocaleString('id-ID');
  const toast = message => {
    const element = $('#toast');
    element.textContent = message;
    element.classList.add('on');
    clearTimeout(toast.timer);
    toast.timer = setTimeout(() => element.classList.remove('on'), 2600);
  };

  art.render();

  const header = $('#hd');
  if (header) {
    addEventListener('scroll', () => header.classList.toggle('sc', scrollY > 10), { passive: true });
  }

  const drawer = $('#drawer');
  const overlay = $('#ov');
  const cartSummary = $('#cartSummary');
  const checkoutForm = $('#checkoutForm');
  let cart = [];

  const openCart = isOpen => {
    drawer.classList.toggle('on', isOpen);
    overlay.classList.toggle('on', isOpen);
    drawer.setAttribute('aria-hidden', String(!isOpen));
    document.body.classList.toggle('lock', isOpen);
  };

  const total = () => cart.reduce((sum, item) => sum + item.qty * D.products[item.slug].price, 0);
  const count = () => cart.reduce((sum, item) => sum + item.qty, 0);
  const csrf = $('meta[name="csrf-token"]').content;

  async function cartRequest(url, method, data) {
    const response = await fetch(url, {
      method,
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
      },
      body: data ? JSON.stringify(data) : undefined,
    });
    const result = await response.json();
    if (!response.ok) {
      const validationMessage = result.errors && Object.values(result.errors).flat()[0];
      throw new Error(validationMessage || result.message || 'Keranjang gagal diperbarui.');
    }
    return result;
  }

  function renderCart() {
    const subtotal = total();
    $('#cartCount').textContent = count();
    $('#cartN').textContent = count();
    const left = D.freeShipping - subtotal;
    $('#shipTxt').textContent = left > 0
      ? `Gratis ongkir di atas ${rp(D.freeShipping)} — kurang ${rp(left)}`
      : 'Yay! Kamu dapat gratis ongkir 🎉';
    $('#shipBar').style.width = Math.min(100, subtotal / D.freeShipping * 100) + '%';

    if (checkoutForm) checkoutForm.style.display = 'none';
    if (!cart.length) {
      cartSummary.innerHTML = '<div class="empty"><h3>Oh no! Your cart is super empty :(</h3></div><button class="btn full" data-act="close">LANJUTKAN BELANJA</button>';
      return;
    }

    const rows = cart.map(item => {
      const product = D.products[item.slug];
      return `<div class="ci"><div class="ci-i" style="background:${product.bg}">${art.scene(product.slug, 0)}</div>
        <div class="ci-t"><b>${product.name}</b><small>Varian: ${product.short}</small><span>${rp(product.price)}</span>
          <div class="qty sm"><button data-act="dec" data-s="${product.slug}" aria-label="Kurangi">−</button><em>${item.qty}</em><button data-act="inc" data-s="${product.slug}" aria-label="Tambah">+</button></div></div>
        <button class="rm" data-act="rm" data-s="${product.slug}" aria-label="Hapus">✕</button></div>`;
    }).join('');

    cartSummary.innerHTML = `<div>${rows}</div><div class="sub"><span>SUBTOTAL</span><b>${rp(subtotal)}</b></div>
      <button class="btn full ac" data-act="checkout">CHECKOUT</button>`;
  }

  async function refreshCart() {
    const response = await fetch(D.cartDataUrl, { headers: { Accept: 'application/json' } });
    const result = await response.json();
    if (!response.ok) throw new Error(result.message || 'Keranjang gagal dimuat.');
    cart = result.items.filter(item => D.products[item.slug] && item.qty > 0);
    renderCart();
  }

  async function changeQuantity(slug, delta) {
    const item = cart.find(entry => entry.slug === slug);
    const product = D.products[slug];
    if (!item || !product) return;
    const quantity = item.qty + delta;
    try {
      const result = quantity < 1
        ? await cartRequest(`${D.cartUrl}/${product.id}`, 'DELETE')
        : await cartRequest(`${D.cartUrl}/${product.id}`, 'PATCH', { quantity });
      cart = result.items.filter(entry => D.products[entry.slug] && entry.qty > 0);
      renderCart();
    } catch (error) {
      toast(error.message);
    }
  }

  async function add(slug, quantity = 1) {
    const product = D.products[slug];
    if (!product || product.stock < 1) {
      toast('Maaf, produk sedang habis.');
      return;
    }
    try {
      const result = await cartRequest(`${D.cartUrl}/${product.id}`, 'POST', { quantity });
      cart = result.items.filter(item => D.products[item.slug] && item.qty > 0);
      renderCart();
      toast('Produk ditambahkan ke keranjang.');
    } catch (error) {
      toast(error.message);
    }
  }

  drawer.addEventListener('click', event => {
    const button = event.target.closest('[data-act]');
    if (!button) return;
    const action = button.dataset.act;
    const slug = button.dataset.s;
    if (action === 'close') openCart(false);
    if (action === 'inc') changeQuantity(slug, 1);
    if (action === 'dec') changeQuantity(slug, -1);
    if (action === 'rm') changeQuantity(slug, -999);
    if (action === 'checkout') {
      if (!D.isAuthenticated) {
        openLogin(true);
        toast('Silakan log in untuk melanjutkan checkout.');
      } else if (checkoutForm) {
        checkoutForm.style.display = checkoutForm.style.display === 'none' ? 'grid' : 'none';
      }
    }
  });
  $('#cartBtn').addEventListener('click', () => openCart(true));
  $('#cartX').addEventListener('click', () => openCart(false));
  overlay.addEventListener('click', () => openCart(false));
  if (location.hash === '#cart') openCart(true);
  refreshCart().catch(error => toast(error.message));

  const loginModal = $('#loginM');
  const registerModal = $('#registerM');
  const openLogin = isOpen => {
    loginModal.classList.toggle('on', isOpen);
    loginModal.setAttribute('aria-hidden', String(!isOpen));
  };
  const openRegister = isOpen => {
    registerModal.classList.toggle('on', isOpen);
    registerModal.setAttribute('aria-hidden', String(!isOpen));
  };

  const loginButton = $('#loginBtn');
  if (loginButton) loginButton.addEventListener('click', () => openLogin(true));
  const detailAddButton = $('#detailAdd');
  if (detailAddButton) {
    detailAddButton.addEventListener('click', () => {
      add(detailAddButton.dataset.s);
      openCart(true);
    });
  }
  loginModal.addEventListener('click', event => {
    if (event.target === loginModal || event.target.closest('[data-close]')) openLogin(false);
  });
  registerModal.addEventListener('click', event => {
    if (event.target === registerModal || event.target.closest('[data-close]')) openRegister(false);
  });
  $$('[data-show-register]').forEach(link => link.addEventListener('click', event => {
    event.preventDefault();
    openLogin(false);
    openRegister(true);
  }));
  $$('[data-show-login]').forEach(link => link.addEventListener('click', event => {
    event.preventDefault();
    openRegister(false);
    openLogin(true);
  }));

  if (D.openLogin || location.hash === '#login') openLogin(true);
  if (D.openRegister || location.hash === '#register') openRegister(true);
  if (D.loginError) $('#loginErr').textContent = D.loginError;

  addEventListener('keydown', event => {
    if (event.key === 'Escape') {
      openCart(false);
      openLogin(false);
      openRegister(false);
    }
  });

  $$('.fq').forEach(item => {
    const question = $('.fq-q', item);
    const answer = $('.fq-a', item);
    question.onclick = () => {
      const isOpen = item.classList.toggle('open');
      question.setAttribute('aria-expanded', String(isOpen));
      $('i', question).textContent = isOpen ? '−' : '+';
      answer.style.maxHeight = isOpen ? answer.scrollHeight + 'px' : 0;
    };
  });

  const newsletter = $('#nl');
  if (newsletter) newsletter.onsubmit = event => {
    event.preventDefault();
    newsletter.reset();
    toast('Terima kasih!');
  };

  const observer = new IntersectionObserver(entries => entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in');
      observer.unobserve(entry.target);
    }
  }), { threshold: .12 });
  $$('.rv').forEach(element => observer.observe(element));

  const scroller = $('#scroller');
  if (scroller) {
    let dragging = false, startX = 0, scrollLeft = 0;
    scroller.onpointerdown = event => {
      if (event.pointerType !== 'mouse') return;
      dragging = true;
      startX = event.clientX;
      scrollLeft = scroller.scrollLeft;
      scroller.classList.add('drag');
    };
    addEventListener('pointerup', () => {
      dragging = false;
      scroller.classList.remove('drag');
    });
    addEventListener('pointermove', event => {
      if (dragging) scroller.scrollLeft = scrollLeft - (event.clientX - startX);
    });
  }

  const subnav = $('#subnav');
  if (subnav) {
    const links = $$('a', subnav);
    const sections = new IntersectionObserver(entries => entries.forEach(entry => {
      if (entry.isIntersecting) {
        links.forEach(link => link.classList.toggle('on', link.getAttribute('href') === '#' + entry.target.id));
      }
    }), { rootMargin: '-40% 0px -55% 0px' });
    links.forEach(link => {
      const target = $(link.getAttribute('href'));
      if (target) sections.observe(target);
    });
  }

  const productPage = $('#pdp');
  if (productPage && D.current && D.products[D.current]) {
    const left = $('#colL');
    const center = $('#colC');
    const right = $('#colR');
    const state = { slug: D.current, tab: D.products[D.current].tab, qty: 1, image: 0 };
    const escapeHtml = value => String(value).replace(/[&<>"]/g, character => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;',
    }[character]));
    const product = () => D.products[state.slug];
    const multiline = value => String(value).split('\n').map(escapeHtml).join('<br>');

    function paint() {
      const selected = product();
      productPage.className = 'pdp';
      left.className = 'col col-l';
      center.className = 'col col-c';
      right.className = 'col col-r';
      productPage.style.setProperty('--bg', selected.bg);
      productPage.style.setProperty('--ac', selected.accent);
      productPage.style.setProperty('--head', selected.head);
      document.title = selected.name + ' — tada sweetsando';
      left.innerHTML = `<h1 class="pn">${escapeHtml(selected.name)}</h1>
        <div class="rate"><span class="stars" aria-hidden="true">☆☆☆☆☆</span> Belum ada ulasan</div>
        <p>${escapeHtml(selected.blurb)}</p><p class="mono"><b>RASA:</b> ${escapeHtml(selected.taste)}</p>
        <div class="bn">${art.banner(selected.slug)}</div>
        <h3 class="mono">INGREDIENTS</h3>
        <div class="ig">${selected.ingredients.map(([key, name]) => `<div><span>${art.icon(key)}</span><small>${escapeHtml(name)}</small></div>`).join('')}</div>
        <p class="mono al">Mengandung susu &amp; gandum/gluten. Alergen lain tergantung komposisi roti.</p>`;
      center.innerHTML = `<div class="stage" id="stage"></div>
        <div class="dots">${[0, 1, 2, 3, 4].map(index => `<button data-i="${index}" aria-label="Gambar ${index + 1}"></button>`).join('')}</div>
        <p class="mono cap" id="cap"></p>`;
      paintRight();
      showImage(0);
    }

    const captions = ['Foto utama', 'Close-up bagian dalam', 'Kemasan produk', 'Disajikan', 'Kumpulan bahan'];
    function showImage(index) {
      state.image = index;
      const stage = $('#stage');
      stage.classList.remove('in');
      setTimeout(() => {
        stage.innerHTML = art.scene(product().slug, index);
        stage.classList.add('in');
      }, 120);
      $$('.dots button', center).forEach((button, current) => button.classList.toggle('on', current === index));
      $('#cap').textContent = captions[index];
    }

    function paintRight() {
      const variants = Object.values(D.products).filter(item => item.tab === state.tab);
      right.innerHTML = `<div class="tabs"><button data-t="single" class="${state.tab === 'single' ? 'on' : ''}">SINGLE FLAVORS</button>
        <button data-t="mix" class="${state.tab === 'mix' ? 'on' : ''}">MIX FLAVORS</button></div>
        <div class="plist">${variants.map(item => `<button class="pi ${item.slug === state.slug ? 'on' : ''}" data-s="${item.slug}" style="--pb:${item.bg}">
          <span>${art.scene(item.slug, 0)}</span><b>${escapeHtml(item.name)}</b></button>`).join('')}</div>
        <div class="buy"><div class="pr">${rp(product().price)}</div>
          <div class="qty"><button data-q="-1" aria-label="Kurangi">−</button><em id="q">${state.qty}</em><button data-q="1" aria-label="Tambah">+</button></div>
          <button class="btn full ac" id="addBtn" ${product().stock < 1 ? 'disabled' : ''}>TAMBAHKAN KE KERANJANG</button></div>`;
    }

    function selectProduct(slug) {
      state.slug = slug;
      state.tab = D.products[slug].tab;
      state.qty = 1;
      history.replaceState(null, '', D.productUrl + '/' + slug);
      paint();
    }

    center.addEventListener('click', event => {
      const button = event.target.closest('[data-i]');
      if (button) showImage(Number(button.dataset.i));
    });
    right.addEventListener('click', event => {
      const tabButton = event.target.closest('[data-t]');
      const productButton = event.target.closest('[data-s]');
      const quantityButton = event.target.closest('[data-q]');
      if (tabButton) {
        const first = Object.values(D.products).find(item => item.tab === tabButton.dataset.t);
        if (first) selectProduct(first.slug);
      } else if (productButton) {
        selectProduct(productButton.dataset.s);
      } else if (quantityButton) {
        state.qty = Math.max(1, Math.min(20, state.qty + Number(quantityButton.dataset.q)));
        $('#q').textContent = state.qty;
      } else if (event.target.id === 'addBtn') {
        add(state.slug, state.qty);
        openCart(true);
      }
    });
    paint();
  }
})();
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
