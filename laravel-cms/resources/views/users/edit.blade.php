<x-admin-layout title="Ubah Akun">

    <form action="{{ route('users.update', $user) }}" method="POST" class="bg-white p-6 rounded-xl border border-onyx/10 max-w-lg">
        @csrf
        @method('PUT')
        @include('users._form')

        <button type="submit" class="mt-6 bg-onyx text-cloud font-bold px-6 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">Simpan Perubahan</button>
    </form>

</x-admin-layout>
