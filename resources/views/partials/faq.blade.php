<<<<<<< HEAD
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
=======
<section class="faq" id="faq">
  <h2 class="mega-s">QUESTIONS?</h2>
  <div class="faq-l">
    @foreach (config('sando.faq') as [$q, $a])
    <div class="fq">
      <button class="fq-q" type="button" aria-expanded="false"><span>{{ $q }}</span><i>+</i></button>
      <div class="fq-a"><p>{{ $a }}</p></div>
    </div>
    @endforeach
  </div>
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
</section>