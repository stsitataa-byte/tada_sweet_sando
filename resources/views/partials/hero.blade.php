@php
    $slugs = array_keys($catalog);
    $firstSlug = $slugs[0] ?? '';
    $hero = config('sando.hero');
    $words = preg_split('/\s+/', trim($hero['headline']));
@endphp

<section class="hero" id="hero" data-fx-hero data-theme="{{ $firstSlug }}">

    <div class="hero__art" aria-hidden="true">
        <svg viewBox="0 0 1440 800" preserveAspectRatio="xMidYMid slice">
            <g class="px px0">
                <circle class="blob b3" cx="1190" cy="140" r="200"/>
                <path class="blob b1" d="M-60 520 C 100 430 300 480 430 590 C 540 690 400 870 200 870 L -60 870 Z"/>
                <path class="blob b2" d="M280 830 C 420 650 640 710 770 665 C 910 615 1010 725 1190 700 C 1310 685 1390 640 1520 650 L 1520 870 L 280 870 Z"/>
            </g>

            <g class="px px1">
                <g class="art-fruit" data-for="mango"><g class="bob bob-a">
                    <use href="#i-mango" x="-40" y="60" width="340" height="340" transform="rotate(-18 130 230)"/>
                    <use href="#i-mango" x="880" y="440" width="270" height="270" transform="rotate(14 1015 575)"/>
                    <use href="#i-leaf" x="320" y="560" width="210" height="210" transform="rotate(25 425 665)"/>
                    <use href="#i-leaf" x="1240" y="380" width="200" height="200" transform="rotate(-30 1340 480)"/>
                </g></g>
                <g class="art-fruit" data-for="grape"><g class="bob bob-b">
                    <use href="#i-grape" x="-30" y="40" width="340" height="340" transform="rotate(-12 140 210)"/>
                    <use href="#i-grape" x="900" y="450" width="260" height="260" transform="rotate(16 1030 580)"/>
                    <use href="#i-leaf" x="330" y="570" width="200" height="200" transform="rotate(18 430 670)"/>
                    <use href="#i-leaf" x="1250" y="370" width="190" height="190" transform="rotate(-24 1345 465)"/>
                </g></g>
                <g class="art-fruit" data-for="strawberry"><g class="bob bob-a">
                    <use href="#i-strawberry" x="-40" y="50" width="350" height="350" transform="rotate(-14 135 225)"/>
                    <use href="#i-strawberry" x="880" y="440" width="280" height="280" transform="rotate(18 1020 580)"/>
                    <use href="#i-leaf" x="330" y="560" width="210" height="210" transform="rotate(22 435 665)"/>
                    <use href="#i-leaf" x="1250" y="380" width="190" height="190" transform="rotate(-28 1345 475)"/>
                </g></g>
                <g class="art-fruit" data-for="mix-fruit"><g class="bob bob-b">
                    <use href="#i-strawberry" x="-30" y="70" width="290" height="290" transform="rotate(-16 115 215)"/>
                    <use href="#i-mango" x="840" y="450" width="250" height="250" transform="rotate(12 965 575)"/>
                    <use href="#i-grape" x="1230" y="360" width="210" height="210" transform="rotate(-10 1335 465)"/>
                    <use href="#i-leaf" x="320" y="570" width="190" height="190" transform="rotate(20 415 665)"/>
                </g></g>
            </g>

            <g class="px px2">
                <g class="bob bob-c">
                    <use href="#i-sparkle" x="600" y="70" width="70" height="70"/>
                    <use href="#i-sparkle" x="1090" y="620" width="60" height="60"/>
                    <use href="#i-sparkle" x="60" y="640" width="54" height="54"/>
                </g>
            </g>
        </svg>
    </div>

    <div class="hero__text">
        <p class="hero__kicker">{{ $hero['kicker'] }}</p>
        <h1 class="hero__title" aria-label="{{ $hero['headline'] }}">
            @foreach($words as $w)
                <span class="hw" style="--w: {{ $loop->index }}" aria-hidden="true">{{ $w }}</span>
            @endforeach
        </h1>
        <p class="hero__lead">{{ $hero['text'] }}</p>
        <a class="btn btn--pink btn--big" href="{{ route('shop') }}">{{ $hero['button'] }} <svg width="18" height="18"><use href="#i-arrow"/></svg></a>
    </div>

    <div class="hero-card" data-hero-card>
        <svg class="hc-arc" viewBox="0 0 300 140" aria-hidden="true">
            <path id="hcArc" d="M 22 150 A 128 128 0 0 1 278 150" fill="none"/>
            <text><textPath href="#hcArc" startOffset="50%" text-anchor="middle">EXPLORE OUR FLAVORS</textPath></text>
        </svg>

        <div class="hc-stage">
            @foreach($catalog as $p)
                <div class="hc-slide {{ $loop->first ? 'is-active' : '' }}" data-slide="{{ $p['slug'] }}">
                    @include('partials.img', ['src' => $p['image'], 'alt' => 'Fruit sando ' . $p['name'], 'class' => 'hc-img'])
                </div>
            @endforeach
        </div>

        <div class="hc-picks" role="tablist" aria-label="Pilih rasa">
            @foreach($catalog as $p)
                <button type="button" class="hc-pick {{ $loop->first ? 'is-active' : '' }}" data-pick="{{ $p['slug'] }}" role="tab">
                    <svg class="eye eye--open" width="26" height="26" viewBox="0 0 26 26" aria-hidden="true"><path d="M2 13c3-5 7-7.5 11-7.5S21 8 24 13c-3 5-7 7.5-11 7.5S5 18 2 13Z" fill="#fff" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="13" cy="13" r="4.2" fill="currentColor"/></svg>
                    <svg class="eye eye--shut" width="26" height="26" viewBox="0 0 26 26" aria-hidden="true"><path d="M3 11c3 4 6 5.5 10 5.5S20 15 23 11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M6 15l-2 3M10 17l-1 3.2M16 17l1 3.2M20 15l2 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    <span>{{ $p['name'] }}</span>
                </button>
            @endforeach
        </div>

        <a class="hc-shop" data-hero-link href="{{ route('product', ['slug' => $firstSlug]) }}">SHOP NOW</a>
    </div>
</section>