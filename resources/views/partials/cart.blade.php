{{-- CART DRAWER (muncul dari kanan). Isinya digambar oleh public/js/app.js dari data server. --}}
<div class="cart-overlay" data-cart-close></div>

<aside class="cart-drawer" id="cartDrawer" aria-hidden="true" aria-label="Keranjang belanja">
    <div class="cart-drawer__head">
        <div>
            <h2>Cart</h2>
            <p data-cart-sub>0 items</p>
        </div>
        <button type="button" class="icon-btn" data-cart-close aria-label="Tutup keranjang">
            <svg width="22" height="22"><use href="#i-close"/></svg>
        </button>
    </div>

    <div class="cart-drawer__body" data-cart-body></div>

    <div class="cart-drawer__foot" data-cart-foot>
        <div class="cart-line"><span>Subtotal</span><b data-cart-subtotal>Rp0</b></div>
        <div class="cart-line cart-line--total"><span>Total</span><b data-cart-total>Rp0</b></div>
        <a class="btn btn--pink" href="{{ route('checkout') }}">Checkout</a>
        <button type="button" class="btn btn--ghost" data-cart-close>Continue shopping</button>
    </div>
</aside>