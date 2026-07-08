@php $user = null; @endphp
<x-admin-layout title="Akun Baru">

    <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 rounded-xl border border-onyx/10 max-w-lg">
        @csrf
        @include('users._form')

        <button type="submit" class="mt-6 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan Akun</button>
    </form>

</x-admin-layout>
