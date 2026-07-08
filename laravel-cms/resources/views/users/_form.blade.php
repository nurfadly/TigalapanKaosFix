@php $user = $user ?? null; @endphp

<div class="grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="text-sm font-bold">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
               class="mt-1 w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
</div>

<div class="mt-4 grid md:grid-cols-2 gap-6">
    <div>
        <label class="text-sm font-bold">Role</label>
        <p class="text-xs text-onyx/50 mb-1">Admin: akses semua. Editor: Produk, Artikel, Banner saja.</p>
        <select name="role" required class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
            <option value="editor" @selected(old('role', $user->role ?? 'editor') === 'editor')>Editor</option>
            <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Admin</option>
        </select>
    </div>
    <div>
        <label class="text-sm font-bold">Password</label>
        <p class="text-xs text-onyx/50 mb-1">{{ $user ? 'Kosongkan kalau tidak ingin mengubah password.' : 'Wajib diisi untuk akun baru.' }}</p>
        <input type="password" name="password" {{ $user ? '' : 'required' }}
               class="w-full border border-onyx/20 rounded-lg px-3 py-2 text-sm">
    </div>
</div>
