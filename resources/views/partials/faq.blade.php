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
</section>