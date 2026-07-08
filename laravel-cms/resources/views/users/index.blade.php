<x-admin-layout title="Pengguna Admin">

    <div class="flex justify-end mb-4">
        <a href="{{ route('users.create') }}" class="bg-onyx text-cloud font-bold text-sm px-5 py-2.5 rounded-full hover:bg-gusto hover:text-onyx transition-colors">+ Akun Baru</a>
    </div>

    <div class="bg-white rounded-xl border border-onyx/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-onyx/5 text-left">
                <tr>
                    <th class="px-4 py-3 font-bold">Nama</th>
                    <th class="px-4 py-3 font-bold">Email</th>
                    <th class="px-4 py-3 font-bold">Role</th>
                    <th class="px-4 py-3 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-onyx/10">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-onyx/60">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold uppercase px-2 py-1 rounded-full {{ $user->isAdmin() ? 'bg-gusto/20 text-gusto' : 'bg-onyx/10 text-onyx/70' }}">
                                {{ $user->isAdmin() ? 'Admin' : 'Editor' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('users.edit', $user) }}" class="text-sm font-semibold text-onyx/70 hover:text-onyx">Ubah</a>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-3 text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-admin-layout>
