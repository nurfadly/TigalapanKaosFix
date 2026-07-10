<x-site-layout title="Hubungi Kami - Tigalapankaos" description="Hubungi Tigalapankaos untuk pemesanan produk, harga grosir, atau kerja sama mitra.">

<main class="pt-16 md:pt-[72px]">

  <!-- HEADER -->
  <section class="py-12 md:py-16 border-b border-onyx/10">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <p class="text-sm text-onyx/50"><a href="{{ route('home') }}" class="hover:text-onyx">Beranda</a> / Kontak</p>
      <h1 class="mt-3 text-3xl md:text-5xl font-black tracking-tight">Hubungi Kami</h1>
      <p class="mt-3 text-onyx/70 max-w-[55ch]">Ada pertanyaan soal produk, harga grosir, atau kerja sama? Isi form di bawah atau langsung chat admin lewat WhatsApp.</p>
    </div>
  </section>

  <!-- FORM + INFO -->
  <section class="py-14 md:py-20">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8 grid lg:grid-cols-5 gap-10 lg:gap-14">

      <!-- FORM -->
      <form id="contactForm" class="lg:col-span-3 space-y-5">
        <div class="grid sm:grid-cols-2 gap-5">
          <div>
            <label for="nama" class="text-sm font-bold">Nama</label>
            <input id="nama" name="nama" type="text" required placeholder="Nama lengkap" class="mt-2 w-full px-4 py-3 text-sm" />
          </div>
          <div>
            <label for="wa" class="text-sm font-bold">Nomor WhatsApp</label>
            <input id="wa" name="wa" type="tel" required placeholder="08xx xxxx xxxx" class="mt-2 w-full px-4 py-3 text-sm" />
          </div>
        </div>
        <div>
          <label for="email" class="text-sm font-bold">Email</label>
          <input id="email" name="email" type="email" placeholder="nama@email.com" class="mt-2 w-full px-4 py-3 text-sm" />
          <p class="mt-1.5 text-xs text-onyx/50">Opsional, diisi jika ingin balasan lewat email.</p>
        </div>
        <div>
          <label for="topik" class="text-sm font-bold">Topik</label>
          <select id="topik" name="topik" class="mt-2 w-full px-4 py-3 text-sm bg-white border border-onyx/15">
            <option>Pemesanan Produk</option>
            <option>Harga Grosir</option>
            <option>Kerja Sama Mitra</option>
            <option>Lainnya</option>
          </select>
        </div>
        <div>
          <label for="pesan" class="text-sm font-bold">Pesan</label>
          <textarea id="pesan" name="pesan" rows="5" required placeholder="Tulis kebutuhan Anda di sini" class="mt-2 w-full px-4 py-3 text-sm"></textarea>
        </div>
        <button type="submit" id="contactSubmit" class="inline-flex items-center gap-2 bg-onyx text-cloud font-bold px-7 py-3.5 rounded-full hover:bg-gusto hover:text-onyx active:scale-[0.98] transition-all">
          Kirim Pesan <i class="ph-bold ph-paper-plane-tilt"></i>
        </button>
        <p id="formNote" class="text-sm text-onyx/50"></p>
      </form>

      <!-- INFO -->
      <div class="lg:col-span-2 space-y-4">
        <a href="{{ $settings->whatsapp_link }}" target="_blank" rel="noopener" class="flex items-center gap-4 bg-onyx text-cloud p-6 hover:opacity-90 transition-opacity">
          <i class="ph-bold ph-whatsapp-logo text-3xl text-gusto"></i>
          <div>
            <p class="font-bold">Chat Admin</p>
            <p class="text-sm text-cloud/70">Respons tercepat lewat WhatsApp</p>
          </div>
        </a>
        @if ($settings->contact_email)
        <a href="mailto:{{ $settings->contact_email }}" class="flex items-center gap-4 bg-white border border-onyx/10 p-6 hover:border-onyx transition-colors">
          <i class="ph-bold ph-envelope-simple text-3xl text-gusto"></i>
          <div>
            <p class="font-bold">Email</p>
            <p class="text-sm text-onyx/60">{{ $settings->contact_email }}</p>
          </div>
        </a>
        @endif
        <div class="flex items-center gap-4 bg-white border border-onyx/10 p-6">
          <i class="ph-bold ph-clock text-3xl text-gusto"></i>
          <div>
            <p class="font-bold">Jam Layanan</p>
            <p class="text-sm text-onyx/60">Senin - Sabtu, 08.00 - 17.00 WITA</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- BRANCHES -->
  <section class="py-14 md:py-20 border-t border-onyx/10">
    <div class="max-w-[1400px] mx-auto px-5 md:px-8">
      <h2 class="text-2xl md:text-3xl font-black">Kantor dan Titik Distribusi</h2>
      <p class="mt-3 text-onyx/70 max-w-[60ch]">Alamat lengkap tiap kota akan kami infokan langsung lewat admin. Berikut kota tempat Tigalapankaos melayani mitra secara aktif.</p>

      <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @forelse ($branches as $branch)
          <div class="bg-white border border-onyx/10 p-6">
            <p class="text-xs font-bold uppercase tracking-wide text-gusto">{{ $branch->label ?: ($branch->is_hq ? 'Kantor Pusat' : 'Titik Distribusi') }}</p>
            <p class="mt-2 font-bold text-lg">{{ $branch->city }}</p>
            <p class="mt-1 text-sm text-onyx/60">{{ $branch->province }}</p>
          </div>
        @empty
          <p class="text-onyx/50 text-sm">Data cabang belum ditambahkan.</p>
        @endforelse
      </div>
    </div>
  </section>

</main>

<x-slot:scripts>
<script>
  document.getElementById('contactForm')?.addEventListener('submit', (e) => {
    e.preventDefault();

    const form = e.target;
    const note = document.getElementById('formNote');
    const submitBtn = document.getElementById('contactSubmit');
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');

    const payload = {
      name: document.getElementById('nama').value.trim(),
      phone: document.getElementById('wa').value.trim(),
      email: document.getElementById('email').value.trim() || null,
      topic: document.getElementById('topik').value,
      message: document.getElementById('pesan').value.trim(),
    };

    if (!payload.name || !payload.phone || !payload.message) return;

    if (submitBtn) submitBtn.disabled = true;

    fetch('{{ route('site.lead.store') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': tokenMeta ? tokenMeta.content : '',
      },
      body: JSON.stringify(payload),
    })
      .then((res) => {
        if (!res.ok) throw new Error('Gagal mengirim');
        if (note) note.textContent = 'Pesan terkirim. Admin kami akan segera menghubungi Anda.';
        form.reset();
      })
      .catch(() => {
        if (note) note.textContent = 'Gagal mengirim pesan. Silakan coba lagi atau hubungi lewat WhatsApp Admin.';
      })
      .finally(() => {
        if (submitBtn) submitBtn.disabled = false;
      });
  });
</script>
</x-slot:scripts>

</x-site-layout>
