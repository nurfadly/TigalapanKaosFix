@php $branch = null; @endphp
<x-admin-layout title="Cabang Baru">

    <form action="{{ route('branches.store') }}" method="POST" class="bg-white p-6 rounded-xl border border-onyx/10 max-w-xl">
        @csrf
        @include('branches._form')

        <button type="submit" class="mt-6 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan</button>
    </form>

</x-admin-layout>
