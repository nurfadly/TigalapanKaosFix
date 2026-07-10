/* Tigalapankaos - Cart system (shared across pages via localStorage) */
(function () {
  const STORE_KEY = 'tigalapankaos_cart';
  // Dibaca dari <meta name="wa-number"> yang di-render dinamis oleh site-layout.blade.php
  // (nilainya diatur admin lewat menu Pengaturan di CMS). Fallback kalau meta tidak ada.
  const waMeta = document.querySelector('meta[name="wa-number"]');
  const WA_NUMBER = waMeta ? waMeta.content : '6280000000000';

  function getCart() {
    try { return JSON.parse(localStorage.getItem(STORE_KEY)) || []; }
    catch (e) { return []; }
  }
  function saveCart(cart) {
    localStorage.setItem(STORE_KEY, JSON.stringify(cart));
    updateBadge();
    renderDrawer();
  }
  function formatRupiah(n) {
    return 'Rp' + Number(n).toLocaleString('id-ID');
  }
  function cartTotal(cart) {
    return cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  }
  function cartCount(cart) {
    return cart.reduce((sum, item) => sum + item.qty, 0);
  }

  function addToCart({ id, name, price, variant, image }, qty = 1) {
    const cart = getCart();
    const key = id + '|' + (variant || '');
    const existing = cart.find(i => (i.id + '|' + (i.variant || '')) === key);
    if (existing) { existing.qty += qty; }
    else { cart.push({ id, name, price: Number(price), variant: variant || '', image, qty }); }
    saveCart(cart);
    openDrawer();
  }

  function removeItem(index) {
    const cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
  }
  function changeQty(index, delta) {
    const cart = getCart();
    if (!cart[index]) return;
    cart[index].qty += delta;
    if (cart[index].qty < 1) cart.splice(index, 1);
    saveCart(cart);
  }

  function updateBadge() {
    const count = cartCount(getCart());
    document.querySelectorAll('.cart-count').forEach(el => {
      el.textContent = count > 99 ? '99+' : count;
      el.classList.toggle('hidden', count === 0);
      el.classList.toggle('flex', count > 0);
    });
  }

  /* ---------- UI injection ---------- */
  function injectMarkup() {
    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
    <!-- CART OVERLAY -->
    <div id="cartOverlay" class="hidden fixed inset-0 bg-onyx/50 z-[60] transition-opacity"></div>

    <!-- CART DRAWER -->
    <aside id="cartDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-cloud z-[70] translate-x-full transition-transform duration-300 flex flex-col">
      <div class="flex items-center justify-between px-5 md:px-6 h-16 md:h-[72px] border-b border-onyx/10 shrink-0">
        <p class="font-black text-lg">Keranjang Anda</p>
        <button id="closeCart" aria-label="Tutup keranjang" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-onyx/5">
          <i class="ph-bold ph-x text-xl"></i>
        </button>
      </div>
      <div id="cartItems" class="flex-1 overflow-y-auto px-5 md:px-6 py-5 space-y-4"></div>
      <div id="cartFooter" class="border-t border-onyx/10 px-5 md:px-6 py-5 shrink-0">
        <div class="flex items-center justify-between text-sm font-bold">
          <span>Subtotal</span>
          <span id="cartSubtotal">Rp0</span>
        </div>
        <button id="checkoutBtn" class="mt-4 w-full inline-flex items-center justify-center gap-2 bg-onyx text-cloud font-bold px-7 py-3.5 rounded-full hover:bg-gusto hover:text-onyx active:scale-[0.98] transition-all disabled:opacity-40 disabled:pointer-events-none">
          Checkout <i class="ph-bold ph-arrow-right"></i>
        </button>
      </div>
    </aside>

    <!-- CHECKOUT MODAL -->
    <div id="checkoutOverlay" class="hidden fixed inset-0 bg-onyx/50 z-[80] items-center justify-center p-4">
      <div class="bg-cloud w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 h-16 border-b border-onyx/10 sticky top-0 bg-cloud">
          <p class="font-black text-lg">Ringkasan Pesanan</p>
          <button id="closeCheckout" aria-label="Tutup" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-onyx/5">
            <i class="ph-bold ph-x text-xl"></i>
          </button>
        </div>
        <div class="p-6">
          <div id="checkoutItems" class="space-y-3 pb-4 border-b border-onyx/10"></div>
          <div class="flex items-center justify-between font-black text-lg py-4">
            <span>Total</span>
            <span id="checkoutTotal">Rp0</span>
          </div>
          <form id="checkoutForm" class="space-y-4">
            <div>
              <label for="ckNama" class="text-sm font-bold">Nama</label>
              <input id="ckNama" name="nama" type="text" required placeholder="Nama lengkap" class="mt-2 w-full px-4 py-3 text-sm bg-white border border-onyx/15 focus:outline-2 focus:outline-onyx" />
            </div>
            <div>
              <label for="ckTelepon" class="text-sm font-bold">Nomor Telepon</label>
              <input id="ckTelepon" name="telepon" type="tel" required placeholder="08xx xxxx xxxx" class="mt-2 w-full px-4 py-3 text-sm bg-white border border-onyx/15 focus:outline-2 focus:outline-onyx" />
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-onyx text-cloud font-bold px-7 py-3.5 rounded-full hover:bg-gusto hover:text-onyx active:scale-[0.98] transition-all">
              Pesan Sekarang
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- SUCCESS MODAL -->
    <div id="successOverlay" class="hidden fixed inset-0 bg-onyx/50 z-[90] items-center justify-center p-4">
      <div class="bg-cloud w-full max-w-sm p-8 text-center">
        <div class="w-16 h-16 rounded-full bg-gusto/20 flex items-center justify-center mx-auto">
          <i class="ph-fill ph-check-circle text-4xl text-gusto"></i>
        </div>
        <h2 class="mt-5 text-xl font-black">Pemesanan Berhasil</h2>
        <p class="mt-2 text-sm text-onyx/60">Admin kami akan segera menghubungi Anda untuk konfirmasi pesanan dan proses pembayaran.</p>
        <a id="successWaLink" href="#" target="_blank" rel="noopener" class="mt-6 w-full inline-flex items-center justify-center gap-2 bg-onyx text-cloud font-bold px-7 py-3.5 rounded-full hover:opacity-85 transition-all">
          <i class="ph-bold ph-whatsapp-logo"></i> Konfirmasi via WhatsApp
        </a>
        <button id="closeSuccess" class="mt-3 w-full text-sm font-bold text-onyx/60 hover:text-onyx py-2">Tutup</button>
      </div>
    </div>
    `;
    document.body.appendChild(wrapper);
  }

  function renderDrawer() {
    const cart = getCart();
    const itemsEl = document.getElementById('cartItems');
    const subtotalEl = document.getElementById('cartSubtotal');
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (!itemsEl) return;

    if (cart.length === 0) {
      itemsEl.innerHTML = `
        <div class="h-full flex flex-col items-center justify-center text-center py-16">
          <i class="ph-bold ph-shopping-bag text-4xl text-onyx/20"></i>
          <p class="mt-4 text-onyx/60 text-sm">Keranjang masih kosong.</p>
          <a href="/produk" class="mt-4 inline-flex items-center gap-2 font-bold text-sm hover:text-gusto">Lihat Katalog <i class="ph-bold ph-arrow-right"></i></a>
        </div>`;
      checkoutBtn.setAttribute('disabled', 'true');
    } else {
      itemsEl.innerHTML = cart.map((item, i) => `
        <div class="flex gap-3">
          <div class="w-20 h-20 shrink-0 bg-white overflow-hidden">
            <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover" />
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-bold text-sm truncate">${item.name}</p>
            <p class="text-xs text-onyx/50">${item.variant || ''}</p>
            <div class="mt-2 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <button class="qty-btn w-7 h-7 rounded-full border border-onyx/20 flex items-center justify-center text-sm" data-index="${i}" data-delta="-1" aria-label="Kurangi jumlah">-</button>
                <span class="text-sm font-bold w-5 text-center">${item.qty}</span>
                <button class="qty-btn w-7 h-7 rounded-full border border-onyx/20 flex items-center justify-center text-sm" data-index="${i}" data-delta="1" aria-label="Tambah jumlah">+</button>
              </div>
              <p class="text-sm font-bold">${formatRupiah(item.price * item.qty)}</p>
            </div>
          </div>
          <button class="remove-btn shrink-0 w-7 h-7 flex items-center justify-center text-onyx/40 hover:text-onyx" data-index="${i}" aria-label="Hapus item">
            <i class="ph-bold ph-trash"></i>
          </button>
        </div>
      `).join('');
      checkoutBtn.removeAttribute('disabled');
    }
    subtotalEl.textContent = formatRupiah(cartTotal(cart));

    itemsEl.querySelectorAll('.qty-btn').forEach(btn => {
      btn.addEventListener('click', () => changeQty(parseInt(btn.dataset.index), parseInt(btn.dataset.delta)));
    });
    itemsEl.querySelectorAll('.remove-btn').forEach(btn => {
      btn.addEventListener('click', () => removeItem(parseInt(btn.dataset.index)));
    });

    updateBadge();
  }

  function openDrawer() {
    document.getElementById('cartOverlay').classList.remove('hidden');
    document.getElementById('cartDrawer').classList.remove('translate-x-full');
    document.body.style.overflow = 'hidden';
  }
  function closeDrawer() {
    document.getElementById('cartOverlay').classList.add('hidden');
    document.getElementById('cartDrawer').classList.add('translate-x-full');
    document.body.style.overflow = '';
  }

  function openCheckout() {
    const cart = getCart();
    if (cart.length === 0) return;
    const itemsEl = document.getElementById('checkoutItems');
    itemsEl.innerHTML = cart.map(item => `
      <div class="flex items-center justify-between text-sm">
        <span class="text-onyx/70">${item.name}${item.variant ? ' (' + item.variant + ')' : ''} &times; ${item.qty}</span>
        <span class="font-bold">${formatRupiah(item.price * item.qty)}</span>
      </div>
    `).join('');
    document.getElementById('checkoutTotal').textContent = formatRupiah(cartTotal(cart));
    closeDrawer();
    document.getElementById('checkoutOverlay').classList.remove('hidden');
    document.getElementById('checkoutOverlay').classList.add('flex');
    document.body.style.overflow = 'hidden';
  }
  function closeCheckout() {
    document.getElementById('checkoutOverlay').classList.add('hidden');
    document.getElementById('checkoutOverlay').classList.remove('flex');
    document.body.style.overflow = '';
  }

  function openSuccess(nama, telepon) {
    const cart = getCart();
    const lines = cart.map(item => `- ${item.name}${item.variant ? ' (' + item.variant + ')' : ''} x${item.qty} = ${formatRupiah(item.price * item.qty)}`).join('\n');
    const message = `Halo, saya ${nama} ingin konfirmasi pesanan berikut:\n${lines}\nTotal: ${formatRupiah(cartTotal(cart))}\nNomor telepon saya: ${telepon}`;
    document.getElementById('successWaLink').href = `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(message)}`;
    closeCheckout();
    document.getElementById('successOverlay').classList.remove('hidden');
    document.getElementById('successOverlay').classList.add('flex');
    document.body.style.overflow = 'hidden';
    saveCart([]);
  }
  function closeSuccess() {
    document.getElementById('successOverlay').classList.add('hidden');
    document.getElementById('successOverlay').classList.remove('flex');
    document.body.style.overflow = '';
  }

  /**
   * Kirim ringkasan pesanan ke backend Laravel (route: POST /pesanan) supaya
   * muncul di menu Pesanan pada CMS. Dipanggil sebelum openSuccess().
   * Gagal kirim ke server tidak menghalangi alur WhatsApp yang sudah ada.
   */
  function submitOrderToServer(nama, telepon) {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const endpointMeta = document.querySelector('meta[name="order-endpoint"]');
    if (!tokenMeta || !endpointMeta) return Promise.resolve();

    const cart = getCart();
    if (cart.length === 0) return Promise.resolve();

    return fetch(endpointMeta.content, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': tokenMeta.content,
      },
      body: JSON.stringify({
        customer_name: nama,
        customer_phone: telepon,
        items: cart.map(item => ({
          id: item.id,
          name: item.name,
          variant: item.variant || '',
          price: item.price,
          qty: item.qty,
        })),
      }),
    }).catch((err) => {
      console.warn('Gagal menyimpan pesanan ke server, lanjut ke WhatsApp saja.', err);
    });
  }

  function wireEvents() {
    document.querySelectorAll('.cart-open-btn').forEach(btn => btn.addEventListener('click', openDrawer));
    document.getElementById('closeCart').addEventListener('click', closeDrawer);
    document.getElementById('cartOverlay').addEventListener('click', closeDrawer);
    document.getElementById('checkoutBtn').addEventListener('click', openCheckout);
    document.getElementById('closeCheckout').addEventListener('click', closeCheckout);

    document.getElementById('checkoutForm').addEventListener('submit', (e) => {
      e.preventDefault();
      const nama = document.getElementById('ckNama').value.trim();
      const telepon = document.getElementById('ckTelepon').value.trim();
      if (!nama || !telepon) return;

      const submitBtn = e.target.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      submitOrderToServer(nama, telepon).finally(() => {
        // Alur WhatsApp tetap jalan walau penyimpanan ke server gagal
        // (misalnya karena koneksi terputus), supaya customer tidak terhambat.
        openSuccess(nama, telepon);
        e.target.reset();
        if (submitBtn) submitBtn.disabled = false;
      });
    });

    document.getElementById('closeSuccess').addEventListener('click', closeSuccess);

    document.querySelectorAll('.add-to-cart').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        addToCart({
          id: btn.dataset.id,
          name: btn.dataset.name,
          price: btn.dataset.price,
          variant: btn.dataset.variant || '',
          image: btn.dataset.image,
        }, 1);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    injectMarkup();
    wireEvents();
    renderDrawer();
    updateBadge();
  });

  window.tigalapankaosCart = { addToCart };
})();
