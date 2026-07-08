<x-admin-layout title="Ubah Kategori">

    <form action="{{ route('categories.update', $category) }}" method="POST" class="bg-white p-6 rounded-xl border border-onyx/10 max-w-lg">
        @csrf
        @method('PUT')
        @include('categories._form')

        <button type="submit" class="mt-6 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan Perubahan</button>
    </form>

</x-admin-layout>
