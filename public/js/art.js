/* Ilustrasi vektor orisinal tada sweetsando (ganti dengan foto asli bila sudah ada) */
(function () {
 
  /* ---- bahan ---- */
  const ING = {
    bread: `<path d="M-38 30V-6C-52-14-48-38-26-40Q0-52 26-40C48-38 52-14 38-6V30Z" fill="#ffe9b0" ${S}/><path d="M-26 28V-4M26 28V-4" stroke="#e5b76b" stroke-width="3" opacity=".6"/>`,
    cream: `<path d="M-36 6H36C34 30 20 40 0 40S-34 30-36 6Z" fill="#fff" ${S}/><path d="M-22 6C-26-10-8-12-10-22-4-34 14-30 12-18 24-14 24 0 22 6Z" fill="#fffdf5" ${S}/>`,
    sugar: `<rect x="-26" y="-14" width="52" height="46" rx="8" fill="#fff" ${S}/><rect x="-30" y="-26" width="60" height="14" rx="6" fill="#ffb3c7" ${S}/><path d="M-12 4H12M-12 16H6" stroke="${INK}" stroke-width="3" stroke-linecap="round"/>`,
    vanilla: `<path d="M-40 28C-20-30 20-40 40-34 22-10 4 30-40 28Z" fill="#7a4b2a" ${S}/><circle cx="26" cy="-30" r="8" fill="#fff" ${S}/><circle cx="26" cy="-30" r="3" fill="#ffe27a"/>`,
    milk: `<path d="M-24-10L0-30 24-10V40H-24Z" fill="#fff" ${S}/><rect x="-24" y="8" width="48" height="16" fill="#8fd0ff" ${S}/>`,
  };
  const icon = k => F[k] ? svg('-62 -62 124 124', F[k]) : svg('-62 -62 124 124', ING[k] || '');

  /* ---- adegan galeri (0 utama, 1 close-up, 2 kemasan, 3 disajikan, 4 bahan) ---- */
  function scene(slug, i) {
    const P = (window.SANDO && SANDO.products[slug]) || { accent: '#ffb627', fruits: [slug], head: '#ffe27a' };
    const fruits = P.fruits;
    let c = '';
    if (i === 0) {
      c = sando(slug) + sparkle(60, 70, 1.2) + sparkle(345, 60, .9) + sparkle(330, 290, .7);
    } else if (i === 1) {
      c = T(sando(slug), -190, -250, 1.9) + sparkle(350, 60, 1);
    } else if (i === 2) {
      c = `<rect x="64" y="104" width="272" height="196" rx="16" fill="${P.accent}" ${S}/>
           <rect x="64" y="104" width="272" height="44" rx="14" fill="#fff" ${S}/>
           <text x="200" y="134" text-anchor="middle" font-family="Lilita One,cursive" font-size="24" fill="${INK}">tada sweetsando</text>
           <rect x="98" y="164" width="204" height="110" rx="12" fill="#fffdf5" ${S}/>
           ${T(sando(slug), 136, 164, .32)}
           <path d="M196 104V300" stroke="${INK}" stroke-width="3" opacity=".25"/>
           <text x="200" y="292" text-anchor="middle" font-family="Space Mono,monospace" font-size="12" font-weight="700" fill="${INK}">FRESH FRUIT SANDO</text>`;
    } else if (i === 3) {
      c = `<circle cx="200" cy="190" r="150" fill="#fff" ${S}/><circle cx="200" cy="190" r="118" fill="none" stroke="#e8dff0" stroke-width="3"/>
           ${T(sando(slug), 56, 56, .72)}
           ${fruits.map((f, k) => T(F[f], 78 + k * 54, 296 - (k % 2) * 8, .42, k * 20)).join('')}
           <path d="M340 90L372 40M350 100L384 56" ${S} fill="none" stroke-width="4"/>`;
    } else {
      const items = ['milk', 'bread', 'cream', ...fruits];
      c = items.map((k, n) => T(F[k] || ING[k], 76 + (n % 3) * 124, 100 + Math.floor(n / 3) * 120, .95, n % 2 ? 6 : -6)).join('') + sparkle(350, 40);
    }
    return svg('0 0 400 340', c, 'preserveAspectRatio="xMidYMid meet"');
  }

  /* ---- banner kiri halaman produk ---- */
  function banner(slug) {
    const P = SANDO.products[slug];
    const f = P.fruits;
    let c = `<rect width="400" height="120" fill="${P.head}"/><path d="M0 90Q50 60 100 90T200 90 300 90 400 90V120H0Z" fill="${P.accent}" opacity=".5"/>`;
    for (let n = 0; n < 6; n++) c += T(F[f[n % f.length]], 40 + n * 64, 54 + (n % 2) * 18, .55, n * 15 - 20);
    return svg('0 0 400 120', c, 'preserveAspectRatio="xMidYMid slice"');
  }

  /* ---- klaster besar (Hello World) ---- */
  function cluster() {
    const c = `<ellipse cx="400" cy="372" rx="300" ry="18" fill="${INK}" opacity=".12"/>
      ${T(F.grape, 150, 250, 2.3, -8)} ${T(F.mango, 640, 250, 2.4, 10)} ${T(F.strawberry, 400, 220, 3.1, -4)}
      <g transform="translate(250 280) scale(.9)">${sando('mix')}</g>
      ${T(ING.cream, 90, 80, 1.4)} ${T(`<path d="M0-40C34-40 46-6 18 8 0 40-30 30-30 0-40-24-20-40 0-40Z" fill="${LEAF}" ${S}/>`, 710, 80, 1.2, 20)}
      ${sparkle(250, 60, 1.6)} ${sparkle(560, 50, 1.3)} ${sparkle(740, 330, 1.1)} ${sparkle(50, 330, 1)}`;
    return svg('0 0 800 420', c);
  }

  window.art = {
    scene, banner, icon, sando,
    render(root = document) {
      root.querySelectorAll('[data-art]:not([data-done])').forEach(el => {
        const [t, a, b] = el.dataset.art.split(':');
        let h = '';
        if (t === 'scene') h = scene(a, +b || 0);
        else if (t === 'fruit') h = svg('-62 -62 124 124', F[a]);
        else if (t === 'ing') h = icon(a);
        else if (t === 'banner') h = banner(a);
        else if (t === 'cluster') h = cluster();
        el.innerHTML = h; el.dataset.done = 1;
      });
    },
  };
})();
