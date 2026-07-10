<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Admin' }} - CMS Tigalapankaos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: { extend: {
          colors: { cloud: '#EFEFEF', onyx: '#0F0F0F', silver: '#8E8E8E', gusto: '#F9A31A' },
        } }
      }
    </script>
</head>
<body class="bg-cloud text-onyx antialiased">
<div class="min-h-screen flex">

    <aside class="w-64 shrink-0 bg-onyx text-cloud min-h-screen p-6 hidden md:block">
        <a href="{{ route('dashboard') }}" class="text-xl font-black italic">tigalapankaos</a>
        <p class="text-xs text-cloud/50 mt-1">Admin CMS</p>

        <nav class="mt-8 flex flex-col gap-1 text-sm font-semibold">
            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Dashboard</a>
            <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('products.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Produk</a>
            <a href="{{ route('articles.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('articles.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Artikel</a>
            <a href="{{ route('banners.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('banners.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Banner</a>
            <a href="{{ route('orders.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('orders.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Pesanan</a>
            <a href="{{ route('stock.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('stock.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Stok Outlet</a>
            <a href="{{ route('leads.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('leads.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Leads</a>
            <a href="{{ route('testimonials.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('testimonials.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Testimoni</a>
            <a href="{{ route('branches.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('branches.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Cabang</a>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('categories.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Kategori</a>
                <a href="{{ route('users.index') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('users.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Pengguna</a>
                <a href="{{ route('settings.edit') }}" class="px-3 py-2 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-gusto text-onyx' : 'hover:bg-white/10' }}">Pengaturan</a>
            @endif
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="mt-8">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold hover:bg-white/10">Keluar</button>
        </form>
    </aside>

    <main class="flex-1 p-6 md:p-10">
        <div class="max-w-5xl mx-auto">
            @if (session('status'))
                <div class="mb-6 bg-green-100 border border-green-300 text-green-800 text-sm font-semibold px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-300 text-red-800 text-sm font-semibold px-4 py-3 rounded-lg">
                    <p class="mb-1">Periksa lagi isian berikut:</p>
                    <ul class="list-disc list-inside font-normal">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="text-2xl font-black mb-6">{{ $title ?? 'Admin' }}</h1>

            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>
