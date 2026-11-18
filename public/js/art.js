(function () {
  const imagePaths = {
    strawberry: '/assets/strawberry-sando.png',
    mango: '/assets/mangga-sando.png',
    grape: '/assets/grape-sando.png',
  };
  const colors = {
    strawberry: '#ff5a7e',
    mango: '#ffb627',
    grape: '#8e5bb5',
    bread: '#ffe9b0',
    cream: '#fffdf5',
    sugar: '#ffb3c7',
    vanilla: '#7a4b2a',
    milk: '#8fd0ff',
  };
  const escapeAttribute = value => String(value).replace(/[&<>"']/g, character => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
  }[character]));

  function svg(viewBox, contents, attributes = '') {
    return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="${viewBox}" ${attributes}>${contents}</svg>`;
  }

  function fruit(kind) {
    if (kind === 'strawberry') {
      return `<path d="M0-34C-15-51-36-37-29-19-24 0-12 21 0 34 12 21 24 0 29-19 36-37 15-51 0-34Z" fill="${colors.strawberry}" stroke="#2b1b2e" stroke-width="3"/><path d="M0-32-20-45-10-28-30-28-8-18 0-25 8-18 30-28 10-28 20-45Z" fill="#4cb35e" stroke="#2b1b2e" stroke-width="2"/><circle cx="-10" cy="-7" r="2" fill="#fff1b0"/><circle cx="10" cy="3" r="2" fill="#fff1b0"/><circle cx="-3" cy="16" r="2" fill="#fff1b0"/>`;
    }

    if (kind === 'mango') {
      return `<path d="M-31 16C-39-5-23-32 4-35 22-38 36-21 31-4 26 15 7 32-14 30-23 29-29 24-31 16Z" fill="#ffb627" stroke="#2b1b2e" stroke-width="3"/><path d="M-2-32C5-46 17-49 27-43 20-31 11-27 2-27Z" fill="#4cb35e" stroke="#2b1b2e" stroke-width="2"/>`;
    }

    if (kind === 'grape') {
      return `<g fill="#a477d1" stroke="#2b1b2e" stroke-width="2.5"><circle cx="-17" cy="-20" r="13"/><circle cx="5" cy="-23" r="13"/><circle cx="23" cy="-9" r="13"/><circle cx="-25" cy="2" r="13"/><circle cx="-4" cy="0" r="13"/><circle cx="16" cy="13" r="13"/><circle cx="-14" cy="22" r="13"/></g><path d="M0-34Q4-49 18-48" fill="none" stroke="#4cb35e" stroke-width="5" stroke-linecap="round"/>`;
    }

    if (kind === 'bread') {
      return `<path d="M-34 30V-8C-48-18-36-40-18-36 0-50 18-38 22-34 42-38 49-17 34-8V30Z" fill="${colors.bread}" stroke="#2b1b2e" stroke-width="3"/><path d="M-22 26V-3M22 26V-3" stroke="#e5b76b" stroke-width="3"/>`;
    }

    if (kind === 'cream') {
      return `<path d="M-34-4H34C32 23 19 34 0 34S-32 23-34-4Z" fill="#fff" stroke="#2b1b2e" stroke-width="3"/><path d="M-18-4C-25-18-8-19-9-29-3-39 12-33 10-23 22-18 21-7 19-4Z" fill="#fffdf5" stroke="#2b1b2e" stroke-width="2"/>`;
    }

    if (kind === 'milk') {
      return `<path d="M-22-12 0-32 22-12V35H-22Z" fill="#fff" stroke="#2b1b2e" stroke-width="3"/><rect x="-22" y="6" width="44" height="16" fill="${colors.milk}" stroke="#2b1b2e" stroke-width="2"/>`;
    }

    if (kind === 'sugar') {
      return `<rect x="-25" y="-15" width="50" height="48" rx="8" fill="#fff" stroke="#2b1b2e" stroke-width="3"/><rect x="-29" y="-27" width="58" height="14" rx="6" fill="${colors.sugar}" stroke="#2b1b2e" stroke-width="3"/>`;
    }

    return `<path d="M-35 25C-14-23 14-35 35-30 17-4 3 26-35 25Z" fill="${colors.vanilla}" stroke="#2b1b2e" stroke-width="3"/>`;
  }

  function icon(kind) {
    return svg('-50 -50 100 100', `<g transform="translate(0 5)">${fruit(kind)}</g>`);
  }

  function productData(slug) {
    return window.SANDO && window.SANDO.products && window.SANDO.products[slug];
  }

  function scene(slug, index = 0) {
    const product = productData(slug);
    const kind = product ? product.artSlug : slug;
    const fruits = product ? product.fruits : [kind];
    const image = imagePaths[kind];
    const sandoImage = image
      ? `<image href="${image}" x="78" y="5" width="244" height="330" preserveAspectRatio="xMidYMid meet"/>`
      : ['strawberry', 'mango', 'grape'].map((fruitKind, position) => {
        const href = imagePaths[fruitKind];
        return `<image href="${href}" x="${position * 96 + 12}" y="48" width="176" height="248" preserveAspectRatio="xMidYMid meet" transform="rotate(${position === 1 ? 0 : position === 0 ? -9 : 9} ${position * 96 + 100} 170)"/>`;
      }).join('');
    let contents = '';

    if (index === 0) {
      contents = `<ellipse cx="200" cy="314" rx="110" ry="12" fill="#2b1b2e" opacity=".14"/>${sandoImage}<path d="M55 92l8 13 14 2-10 10 2 14-14-7-12 7 2-14-10-10 14-2zM326 67l6 10 11 2-8 8 2 11-11-5-9 5 2-11-8-8 11-2z" fill="#ffb627" stroke="#2b1b2e" stroke-width="2"/>`;
    } else if (index === 1) {
      contents = `<circle cx="200" cy="170" r="158" fill="#fff" stroke="#2b1b2e" stroke-width="3"/>${image ? `<image href="${image}" x="35" y="-20" width="330" height="390" preserveAspectRatio="xMidYMid meet"/>` : sandoImage}`;
    } else if (index === 2) {
      const accent = escapeAttribute(product ? product.accent : '#ffb627');
      contents = `<rect x="55" y="62" width="290" height="225" rx="18" fill="${accent}" stroke="#2b1b2e" stroke-width="4"/><rect x="55" y="62" width="290" height="45" rx="15" fill="#fff" stroke="#2b1b2e" stroke-width="3"/><text x="200" y="92" text-anchor="middle" font-family="sans-serif" font-weight="700" font-size="17" fill="#2b1b2e">tada sweetsando</text><rect x="94" y="122" width="212" height="145" rx="12" fill="#fffdf5"/>${image ? `<image href="${image}" x="118" y="118" width="165" height="152" preserveAspectRatio="xMidYMid meet"/>` : sandoImage}`;
    } else if (index === 3) {
      contents = `<circle cx="200" cy="172" r="145" fill="#fff" stroke="#e8dff0" stroke-width="10"/>${sandoImage}${fruits.map((item, position) => `<g transform="translate(${90 + position * 105} 284) scale(.48)">${fruit(item)}</g>`).join('')}`;
    } else {
      const items = ['milk', 'bread', 'cream', ...fruits];
      contents = items.map((item, position) => `<g transform="translate(${78 + position % 3 * 122} ${105 + Math.floor(position / 3) * 112})">${fruit(item)}</g>`).join('');
    }

    return svg('0 0 400 340', contents, 'preserveAspectRatio="xMidYMid meet"');
  }

  function banner(slug) {
    const product = productData(slug);
    const kind = product ? product.artSlug : slug;
    const fruits = product ? product.fruits : [kind];
    const head = escapeAttribute(product ? product.head : '#ffe27a');
    const accent = escapeAttribute(product ? product.accent : '#ffb627');
    const icons = fruits.map((item, position) => `<g transform="translate(${45 + position * 70} 62) scale(.55)">${fruit(item)}</g>`).join('');

    return svg('0 0 400 120', `<rect width="400" height="120" fill="${head}"/><path d="M0 90Q50 60 100 90T200 90 300 90 400 90V120H0Z" fill="${accent}" opacity=".5"/>${icons}`);
  }

  function sando(kind) {
    const items = kind === 'mix' ? ['strawberry', 'mango', 'grape'] : [kind];
    return `<path d="M-95 85V-42Q-105-75-74-80H74Q105-75 95-42V85Z" fill="#ffe9b0" stroke="#2b1b2e" stroke-width="6"/><path d="M-79 54V-39H79V54Z" fill="#fff" stroke="#2b1b2e" stroke-width="4"/>${items.map((item, position) => `<g transform="translate(${(position - (items.length - 1) / 2) * 48} ${position % 2 ? 2 : -4}) scale(.7)">${fruit(item)}</g>`).join('')}<path d="M-92 59H92V85H-92Z" fill="#f3d9a0" stroke="#2b1b2e" stroke-width="4"/>`;
  }

  function cluster() {
    return svg('0 0 800 420', `<ellipse cx="400" cy="380" rx="280" ry="18" fill="#2b1b2e" opacity=".12"/><image href="${imagePaths.grape}" x="80" y="116" width="190" height="250" transform="rotate(-10 175 240)"/><image href="${imagePaths.mango}" x="525" y="108" width="190" height="258" transform="rotate(10 620 240)"/><image href="${imagePaths.strawberry}" x="288" y="70" width="228" height="305"/><path d="M160 65l11 18 21 4-15 14 3 21-20-10-19 10 3-21-15-14 21-4zM690 52l9 15 17 3-12 12 3 17-17-8-15 8 3-17-13-12 17-3z" fill="#ffb627" stroke="#2b1b2e" stroke-width="3"/>`);
  }

  window.art = {
    scene,
    banner,
    icon,
    sando,
    render(root = document) {
      root.querySelectorAll('[data-art]:not([data-done])').forEach(element => {
        const [type, name, index] = element.dataset.art.split(':');
        if (type === 'scene') element.innerHTML = scene(name, Number(index) || 0);
        else if (type === 'fruit') element.innerHTML = icon(name);
        else if (type === 'ing') element.innerHTML = icon(name);
        else if (type === 'banner') element.innerHTML = banner(name);
        else if (type === 'cluster') element.innerHTML = cluster();
        element.dataset.done = '1';
      });
    },
  };
})();
