<x-admin-layout title="Ubah Produk">

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-onyx/10">
        @csrf
        @method('PUT')
        @include('products._form')

        <button type="submit" class="mt-8 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan Perubahan</button>
    </form>

</x-admin-layout>
