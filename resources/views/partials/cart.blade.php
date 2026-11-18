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
</aside>