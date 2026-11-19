<<<<<<< HEAD
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
=======
<div class="ov" id="ov"></div>
<aside class="drawer" id="drawer" aria-label="Keranjang" aria-hidden="true">
  <div class="dr-h"><h2>CART (<span id="cartN">0</span>)</h2><button id="cartX" aria-label="Tutup">✕</button></div>
  <div class="ship"><span id="shipTxt"></span><div class="bar"><i id="shipBar"></i></div></div>
  <div class="dr-b" id="cartBody"></div>
  <div class="dr-f" id="cartFoot">
    <div id="cartSummary"></div>
    @auth
      <form id="checkoutForm" method="POST" action="{{ route('cart.checkout') }}" style="display:none">
        @csrf
        <label>Nomor telepon<input type="tel" name="customer_phone" autocomplete="tel" maxlength="30" required></label>
        <label>Alamat pengantaran<textarea name="customer_address" rows="2" maxlength="2000"></textarea></label>
        <label>Metode pembayaran<select name="payment_method" required><option value="COD">Bayar di tempat (COD)</option><option value="QRIS">QRIS</option></select></label>
        <button class="btn full ac" type="submit">BUAT PESANAN</button>
      </form>
    @endauth
  </div>
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
</aside>