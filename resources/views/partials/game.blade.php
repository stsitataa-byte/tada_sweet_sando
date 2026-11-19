{{-- MINI GAME (dibuka dari ikon controller di header). Logika: public/js/game.js --}}
<div class="game-modal" id="gameModal" hidden
     data-target="{{ (int) config('sando.game.target', 10) }}"
     data-reward="{{ config('sando.game.reward_text', '') }}">
    <div class="game-box">
        <div class="game-bar">
            <button type="button" class="game-sound" data-game-sound aria-label="Suara">Sound: on</button>
            <div class="game-progress" data-game-progress></div>
            <button type="button" class="game-exit" data-game-close>&times; EXIT</button>
        </div>

        <div class="game-stage">
            <canvas width="900" height="420"></canvas>

            <div class="game-overlay" data-game-intro>
                <div class="game-card">
                    <p>Hindari kaktus dan kumpulkan {{ (int) config('sando.game.target', 10) }} buah untuk membuka hadiah!</p>
                    <small>Klik atau tekan spasi untuk lompat</small>
                </div>
            </div>

            <div class="game-overlay" data-game-result hidden>
                <div class="game-card">
                    <p data-game-result-title></p>
                    <small data-game-result-text></small>
                    <button type="button" class="btn btn--pink" data-game-again>Main lagi</button>
                </div>
            </div>
        </div>
    </div>
</div>