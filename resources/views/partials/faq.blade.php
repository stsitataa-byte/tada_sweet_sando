{{-- FAQ ACCORDION. Isi diubah di config/sando.php (bagian 'faq'). --}}
<section class="faq" id="faq">
    <h2 class="display-title display-title--lilac">FAQs</h2>
    <div class="faq__list">
        @foreach(config('sando.faq', []) as $i => $item)
            <div class="faq__item">
                <button type="button" class="faq__q" aria-expanded="false" aria-controls="faq-a-{{ $i }}">
                    <span>{{ $item['q'] }}</span>
                    <svg class="faq__icon" width="22" height="22"><use href="#i-plus"/></svg>
                </button>
                <div class="faq__a" id="faq-a-{{ $i }}">
                    <div class="faq__a-inner">
                        <p>{{ $item['a'] ?? 'Jawaban belum diisi. Edit di config/sando.php (bagian faq).' }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>