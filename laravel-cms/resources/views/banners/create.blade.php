@php $banner = null; @endphp
<x-admin-layout title="Banner Baru">

    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-onyx/10">
        @csrf
        @include('banners._form')

        <button type="submit" class="mt-8 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan Banner</button>
    </form>

</x-admin-layout>
