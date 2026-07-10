<x-admin-layout title="Pengaturan Situs">

    <p class="text-sm text-onyx/60 mb-4">
        Nilai di sini dipakai di seluruh halaman publik: nomor WhatsApp (tombol "Hubungi Kami", checkout, chat lead),
        email, link Instagram/TikTok di footer, dan deskripsi singkat brand di footer.
    </p>

    <form action="{{ route('settings.update') }}" method="POST" class="bg-white p-6 rounded-xl border border-onyx/10 max-w-2xl">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-bold">Nama Situs</label>
                <input type="text" name="site_name" value="{{ old('site_name', $setting->site_name) }}" required
                       class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-sm font-bold">Nomor WhatsApp</label>
                <p class="text-xs text-onyx/50 mb-1">Format internasional tanpa "+", cth. 6281234567890.</p>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $setting->whatsapp_number) }}" required
                       class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="mt-4 grid md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-bold">Email Kontak</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $setting->contact_email) }}"
                       class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            </div>
            <div></div>
            <div>
                <label class="text-sm font-bold">Link Instagram</label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}" placeholder="https://instagram.com/tigalapankaos"
                       class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-sm font-bold">Link TikTok</label>
                <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $setting->tiktok_url) }}" placeholder="https://tiktok.com/@tigalapankaos"
                       class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="mt-4 grid md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-bold">Kota (untuk footer)</label>
                <input type="text" name="address_city" value="{{ old('address_city', $setting->address_city) }}"
                       class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="text-sm font-bold">Provinsi</label>
                <input type="text" name="address_province" value="{{ old('address_province', $setting->address_province) }}"
                       class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="mt-4">
            <label class="text-sm font-bold">Deskripsi Singkat (Footer)</label>
            <textarea name="footer_description" rows="3" maxlength="500" class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">{{ old('footer_description', $setting->footer_description) }}</textarea>
        </div>

        <div class="mt-8 pt-6 border-t border-onyx/10">
            <p class="text-sm font-bold">Google Analytics 4</p>
            <p class="text-xs text-onyx/50 mt-1 mb-3">
                Opsional — isi dua field ini saat website sudah live agar card "Traffic Website" di Dashboard
                menampilkan data pengunjung asli. Sebelum diisi, card tersebut otomatis tampil sebagai
                "Belum Terhubung". Panduan lengkap ada di file <span class="font-mono">GOOGLE-ANALYTICS-SETUP.md</span> di project.
            </p>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-bold">GA4 Property ID</label>
                    <input type="text" name="ga4_property_id" value="{{ old('ga4_property_id', $setting->ga4_property_id) }}" placeholder="properties/123456789"
                           class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm font-mono">
                </div>
                <div>
                    <label class="text-sm font-bold">Path Credentials (JSON)</label>
                    <input type="text" name="ga4_credentials_path" value="{{ old('ga4_credentials_path', $setting->ga4_credentials_path) }}" placeholder="google/ga4-service-account.json"
                           class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm font-mono">
                    <p class="text-xs text-onyx/50 mt-1">Relatif terhadap storage/app — file JSON diupload manual ke server, bukan lewat form ini.</p>
                </div>
            </div>
            @if ($setting->isGa4Configured())
                <p class="mt-3 text-xs font-bold text-green-700"><i class="ph-bold ph-check-circle"></i> Field sudah diisi.</p>
            @else
                <p class="mt-3 text-xs text-onyx/40">Belum diisi — Dashboard akan menampilkan status "Belum Terhubung".</p>
            @endif
        </div>

        <button type="submit" class="mt-6 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan Pengaturan</button>
    </form>

</x-admin-layout>
