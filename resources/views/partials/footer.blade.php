<<<<<<< HEAD
@php($contact = config('sando.contact', []))
<footer class="site-footer">
    <div class="site-footer__grid">
        <div class="site-footer__brand">
            <h3>TADA<br>SWEETSANDO</h3>
            <p>{{ config('sando.announcement.0', '') }} &bull; {{ config('sando.announcement.1', '') }}</p>
        </div>

        <nav class="site-footer__links" aria-label="Footer">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('home') }}#faq">FAQs</a>
            <a href="{{ route('login') }}">Log in</a>
        </nav>

        <div class="site-footer__contact">
            <b>Contact</b>
            @if(! empty($contact['email']))<a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>@endif
            @if(! empty($contact['phone']))<span>{{ $contact['phone'] }}</span>@endif
            @if(! empty($contact['address']))<span>{{ $contact['address'] }}</span>@endif
            @if(empty($contact['email']) && empty($contact['phone']) && empty($contact['address']))
                <span class="muted">Kontak belum diisi (config/sando.php).</span>
            @endif
        </div>
    </div>

    <div class="site-footer__bottom">
        <span>&copy; TADA SWEETSANDO {{ date('Y') }}</span>
    </div>
=======
<footer class="ft">
  <div class="ft-grid">
    <ul class="ft-l">
      <li><a href="{{ route('home') }}">HOME</a></li>
      <li><a href="{{ route('shop') }}">SHOP</a></li>
      <li><a href="{{ route('about') }}">ABOUT</a></li>
      <li><a href="#">CONTACT</a></li>
    </ul>
    <div class="ft-c">
      <div class="logo big"><span class="logo-1">tada</span><span class="logo-2">sweetsando</span></div>
      <p>Stay sweet. Stay fresh. Enjoy every bite.</p>
      <form class="nl" id="nl"><input type="email" required placeholder="Email address" aria-label="Email"><button aria-label="Daftar">→</button></form>
    </div>
    <ul class="ft-r">
      <li><a href="#faq">FAQ</a></li>
      <li><a href="#">SHIPPING INFORMATION</a></li>
      <li><a href="#">PRIVACY POLICY</a></li>
      <li><a href="#">TERMS OF SERVICE</a></li>
    </ul>
  </div>
  <div class="copy">© 2026 TADA SWEETSANDO. ALL RIGHTS RESERVED.</div>
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
</footer>