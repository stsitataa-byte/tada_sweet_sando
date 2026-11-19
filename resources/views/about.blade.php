@extends('layouts.app')
<<<<<<< HEAD

@section('title', 'About | TADA SWEETSANDO')

@section('content')
<section class="about">
    <div class="about__copy">
        <h1 class="display-title display-title--hero">{{ config('sando.about.title') }}</h1>
        @foreach(config('sando.about.text', []) as $para)
            <p>{{ $para }}</p>
        @endforeach
        <a class="btn btn--pink" href="{{ route('shop') }}">Lihat varian</a>
    </div>
    <div class="about__art">
        @include('partials.img', ['src' => config('sando.images.about'), 'alt' => 'Tentang TADA SWEETSANDO', 'class' => 'about__img'])
    </div>
</section>

@include('partials.mission')
@include('partials.faq')
=======
@section('title', 'About — tada sweetsando')
@section('subnav')
<nav class="subnav" id="subnav">
  <a href="#ingredients">THE INGREDIENTS</a><a href="#story">OUR STORY</a><a href="#fruits">OUR FRUITS</a><a href="#quality">OUR QUALITY</a>
</nav>
@endsection
@section('content')
<section class="a-hero">
  <p class="mono">ABOUT TADA SWEETSANDO</p>
  <h1 class="mega"><span class="c1">WE WANTED</span> <span class="c2">TO CREATE</span><br><span class="c3">THE MOST</span> <span class="d" data-art="fruit:strawberry"></span><br><span class="c4">DELICIOUS</span> <span class="c5">FRUIT SANDO</span><br><span class="c1">IN THE</span> <span class="d" data-art="fruit:mango"></span> <span class="c2">WORLD.</span></h1>
</section>

<section class="a-state">
  <h2 class="mega-m"><span class="c3">FRESH FRUITS.</span><br><span class="c2">SOFT CREAM.</span><br><span class="c4">PURE HAPPINESS.</span></h2>
  <span class="fl f1" data-art="fruit:grape"></span><span class="fl f2" data-art="fruit:strawberry"></span><span class="fl f3" data-art="fruit:mango"></span>
</section>

<section class="a-hello">
  <h2 class="mega-s">HELLO WORLD!</h2>
  <p class="lead">Hello, fruit lovers! Selamat datang di tada sweetsando. Kami memadukan buah-buahan segar dan juicy, krim yang lembut, serta roti susu yang empuk untuk menciptakan kebahagiaan kecil dalam setiap gigitan.</p>
  <div class="big-art" data-art="cluster"></div>
</section>

<section class="a-two" id="story">
  <div class="two-t">
    <p class="mono">OUR STORY</p>
    <h2 class="h2">DARI IDE SEDERHANA</h2>
    <p>Setiap kreasi lezat selalu berawal dari sebuah ide sederhana: menghadirkan rasa manis alami buah-buahan segar ke dalam sandwich yang lembut dan creamy.</p>
    <p>Di tada sweetsando, kami memadukan buah pilihan, krim yang lembut, dan roti susu yang empuk untuk menciptakan camilan istimewa yang dapat dinikmati dalam setiap kesempatan.</p>
  </div>
  <div class="two-a" style="--bg:#ffe27a" data-art="scene:mix:0"></div>
</section>

<section class="a-two rev" id="ingredients">
  <div class="two-t">
    <p class="mono">INGREDIENTS</p>
    <h2 class="h2">FROM FRESH FRUITS TO YOUR HANDS</h2>
    <h3 class="h3">Fresh Fruits</h3><p>Mangga segar, stroberi segar, dan anggur segar tanpa biji.</p>
    <h3 class="h3">Soft Bread</h3><p>Roti susu lembut atau Japanese-style shokupan.</p>
    <h3 class="h3">Fluffy Cream</h3><p>Whipping cream, gula halus, dan ekstrak vanila.</p>
  </div>
  <div class="two-a" style="--bg:#bfe8c4" data-art="scene:mix:4"></div>
</section>

<section class="a-fruits" id="fruits">
  <h2 class="mega-s">OUR FRUITS</h2>
  <div class="fr-g">
    <article style="--bg:#ffd3dd"><div data-art="fruit:strawberry"></div><h3>STRAWBERRY</h3><p>Manis dengan sedikit asam yang segar, warnanya cantik di setiap potongan.</p><a href="{{ route('product','strawberry') }}">Strawberry Sando →</a></article>
    <article style="--bg:#fff1b0"><div data-art="fruit:mango"></div><h3>MANGO</h3><p>Mangga matang yang manis legit, lembut, dan wangi tropis.</p><a href="{{ route('product','mango') }}">Mango Sando →</a></article>
    <article style="--bg:#e3d6f7"><div data-art="fruit:grape"></div><h3>GRAPE</h3><p>Anggur tanpa biji yang juicy dan menyegarkan di setiap gigitan.</p><a href="{{ route('product','grape') }}">Grape Sando →</a></article>
  </div>
  <p class="center mono" style="margin-top:28px">Ketiganya bertemu dalam <a href="{{ route('product','mix') }}"><u>Mix Sando</u></a>.</p>
</section>

<section class="a-q" id="quality">
  <p class="mono">OUR QUALITY</p>
  <h2 class="mega-s">FRESH EVERY DAY</h2>
  <p class="lead">Buah dipilih saat matang, krim dikocok segar, roti susu dipilih yang empuk. Setiap pesanan dirakit dengan teliti dan dikemas agar tetap cantik sampai ke tanganmu.</p>
</section>

@include('partials.collection')
@include('partials.faq')
@include('partials.social')
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
@endsection