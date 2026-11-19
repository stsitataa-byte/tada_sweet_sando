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
</footer>