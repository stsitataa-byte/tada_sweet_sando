/* =========================================================
   TADA SWEETSANDO - game.js
   Mini game: hindari kaktus, kumpulkan buah. Canvas tanpa file gambar.
   ========================================================= */
(function () {
    'use strict';

    var modal = document.getElementById('gameModal');
    if (!modal) return;

    var canvas = modal.querySelector('canvas');
    var ctx = canvas.getContext('2d');
    var W = canvas.width, H = canvas.height;
    var GROUND = H - 78;

    var target = parseInt(modal.getAttribute('data-target'), 10) || 10;
    var reward = modal.getAttribute('data-reward') || '';

    var introEl = modal.querySelector('[data-game-intro]');
    var resultEl = modal.querySelector('[data-game-result]');
    var resultTitle = modal.querySelector('[data-game-result-title]');
    var resultText = modal.querySelector('[data-game-result-text]');
    var progressEl = modal.querySelector('[data-game-progress]');
    var soundBtn = modal.querySelector('[data-game-sound]');

    var status = 'idle';      // idle | run | over
    var raf = null, last = 0;
    var player, things, score, speed, spawnIn, t;
    var muted = false, audio = null;

    /* ---------- suara sederhana ---------- */
    function beep(freq, dur) {
        if (muted) return;
        try {
            audio = audio || new (window.AudioContext || window.webkitAudioContext)();
            var o = audio.createOscillator(), g = audio.createGain();
            o.frequency.value = freq; o.type = 'square';
            g.gain.value = 0.04;
            o.connect(g); g.connect(audio.destination);
            o.start(); o.stop(audio.currentTime + dur);
        } catch (e) { /* abaikan jika browser menolak audio */ }
    }

    /* ---------- progres buah ---------- */
    function buildProgress() {
        progressEl.innerHTML = '';
        for (var i = 0; i < target; i++) progressEl.appendChild(document.createElement('i'));
    }
    function updateProgress() {
        var dots = progressEl.children;
        for (var i = 0; i < dots.length; i++) dots[i].classList.toggle('on', i < score);
    }

    function reset() {
        player = { x: 110, y: GROUND, vy: 0, onGround: true };
        things = [];
        score = 0;
        speed = 280;
        spawnIn = 0.8;
        t = 0;
        updateProgress();
    }

    function jump() {
        if (status === 'idle') { start(); return; }
        if (status !== 'run') return;
        if (player.onGround) {
            player.vy = -820;
            player.onGround = false;
            beep(520, 0.08);
        }
    }

    function start() {
        reset();
        status = 'run';
        introEl.hidden = true;
        resultEl.hidden = true;
        last = performance.now();
        cancelAnimationFrame(raf);
        raf = requestAnimationFrame(loop);
    }

    function finish(won) {
        status = 'over';
        resultTitle.textContent = won ? 'Selamat! Semua buah terkumpul.' : 'Aduh, kena kaktus!';
        resultText.textContent = won
            ? (reward || 'Hadiah belum diatur oleh pemilik toko.')
            : 'Kamu mengumpulkan ' + score + ' dari ' + target + ' buah.';
        resultEl.hidden = false;
        beep(won ? 880 : 150, 0.25);
    }

    /* ---------- update & gambar ---------- */
    function update(dt) {
        t += dt;
        speed = Math.min(430, speed + 5 * dt);

        player.vy += 2200 * dt;
        player.y += player.vy * dt;
        if (player.y >= GROUND) { player.y = GROUND; player.vy = 0; player.onGround = true; }

        spawnIn -= dt;
        if (spawnIn <= 0) {
            var cactus = Math.random() < 0.42;
            things.push({
                type: cactus ? 'cactus' : 'fruit',
                x: W + 40,
                y: cactus ? GROUND : GROUND - (Math.random() < 0.5 ? 34 : 125)
            });
            spawnIn = 0.85 + Math.random() * 0.9;
        }

        for (var i = things.length - 1; i >= 0; i--) {
            var th = things[i];
            th.x -= speed * dt;

            if (th.x < -60) { things.splice(i, 1); continue; }

            var dx = Math.abs(th.x - player.x);

            if (th.type === 'cactus') {
                if (dx < 30 && player.y > GROUND - 42) { finish(false); return; }
            } else {
                var dy = Math.abs((th.y) - (player.y - 24));
                if (dx < 34 && dy < 40) {
                    things.splice(i, 1);
                    score++;
                    updateProgress();
                    beep(760, 0.07);
                    if (score >= target) { finish(true); return; }
                }
            }
        }
    }

    function drawScene() {
        ctx.fillStyle = '#d9efb0';
        ctx.fillRect(0, 0, W, H);

        // awan
        ctx.fillStyle = 'rgba(255,255,255,.55)';
        for (var c = 0; c < 4; c++) {
            var cx = ((c * 260 - t * 20) % (W + 200) + (W + 200)) % (W + 200) - 100;
            ctx.beginPath(); ctx.ellipse(cx, 70 + (c % 2) * 40, 70, 20, 0, 0, Math.PI * 2); ctx.fill();
        }

        // bukit
        ctx.fillStyle = '#b6dc7c';
        ctx.beginPath(); ctx.moveTo(0, H);
        for (var x = 0; x <= W; x += 20) ctx.lineTo(x, GROUND - 60 + Math.sin((x + t * 30) / 120) * 28);
        ctx.lineTo(W, H); ctx.closePath(); ctx.fill();

        // tanah
        ctx.fillStyle = '#8cc152';
        ctx.fillRect(0, GROUND + 4, W, H - GROUND);
        ctx.fillStyle = '#6fa63f';
        ctx.fillRect(0, GROUND + 4, W, 5);
    }

    function drawEmoji(ch, x, y, size, flip) {
        ctx.save();
        ctx.translate(x, y);
        if (flip) ctx.scale(-1, 1);
        ctx.font = size + 'px serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'alphabetic';
        ctx.fillText(ch, 0, 0);
        ctx.restore();
    }

    function draw() {
        drawScene();
        things.forEach(function (th) {
            if (th.type === 'cactus') drawEmoji('\uD83C\uDF35', th.x, th.y + 6, 50);
            else drawEmoji('\uD83C\uDF53', th.x, th.y + 12, 38);
        });
        drawEmoji('\uD83E\uDD94', player.x, player.y + 6, 54, true);
    }

    function loop(now) {
        var dt = Math.min((now - last) / 1000, 0.04);
        last = now;
        if (status === 'run') update(dt);
        draw();
        if (status !== 'idle' || modal.hidden === false) raf = requestAnimationFrame(loop);
    }

    /* ---------- buka / tutup ---------- */
    function open() {
        modal.hidden = false;
        document.body.classList.add('no-scroll');
        buildProgress();
        reset();
        status = 'idle';
        introEl.hidden = false;
        resultEl.hidden = true;
        last = performance.now();
        cancelAnimationFrame(raf);
        raf = requestAnimationFrame(loop);
    }

    function close() {
        modal.hidden = true;
        document.body.classList.remove('no-scroll');
        status = 'idle';
        cancelAnimationFrame(raf);
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-game-open]')) { open(); return; }
        if (e.target.closest('[data-game-close]')) { close(); return; }
        if (e.target.closest('[data-game-again]')) { start(); return; }
        if (e.target === modal) { close(); }
    });

    canvas.addEventListener('pointerdown', function (e) { e.preventDefault(); jump(); });
    introEl.addEventListener('pointerdown', function (e) { e.preventDefault(); jump(); });

    document.addEventListener('keydown', function (e) {
        if (modal.hidden) return;
        if (e.code === 'Space' || e.key === ' ') { e.preventDefault(); if (status === 'over') start(); else jump(); }
        if (e.key === 'Escape') close();
    });

    soundBtn.addEventListener('click', function () {
        muted = !muted;
        soundBtn.textContent = muted ? 'Sound: off' : 'Sound: on';
    });
})();