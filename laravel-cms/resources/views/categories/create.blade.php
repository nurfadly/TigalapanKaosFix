<x-admin-layout title="Kategori Baru">

    <form action="{{ route('categories.store') }}" method="POST" class="bg-white p-6 rounded-xl border border-onyx/10 max-w-lg">
        @csrf
        @include('categories._form')

        <button type="submit" class="mt-6 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan</button>
    </form>

</x-admin-layout>
