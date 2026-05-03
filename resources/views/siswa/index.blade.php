@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Siswa</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola master data siswa yang terdaftar di sekolah.</p>
    </div>
    <a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Tambah Siswa
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">NISN</th>
                    <th class="px-6 py-4">Nama Siswa</th>
                    <th class="px-6 py-4">Kelas</th>
                    <th class="px-6 py-4">L/P</th>
                    <th class="px-6 py-4">Alamat</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($students as $index => $siswa)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-mono text-gray-500">{{ $siswa->nisn ?? $siswa->nis }}</td>
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $siswa->nama_siswa ?? $siswa->nama }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $siswa->kelas->kelas ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ ($siswa->jenis_kelamin ?? 'L') == 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                            {{ ($siswa->jenis_kelamin ?? 'L') == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 truncate max-w-[150px]" title="{{ $siswa->alamat }}">
                        {{ $siswa->alamat ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('siswa.edit', $siswa->id) }}" class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" title="Edit Siswa">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Hapus Siswa">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i data-lucide="users" class="w-12 h-12 mb-3 text-gray-300"></i>
                            <p class="text-base font-medium text-gray-500">Belum ada data siswa</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
