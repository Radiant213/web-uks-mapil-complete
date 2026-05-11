@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 animate-fadeInUp">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Kelas</h1>
        <p class="text-sm text-gray-500 mt-1">Master data tingkatan kelas.</p>
    </div>
    <a href="{{ route('kelas.create') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm btn-animate">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Tambah Kelas
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl animate-fadeInUp anim-delay-2">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <th class="px-6 py-4 w-20">No</th>
                    <th class="px-6 py-4">Nama Kelas</th>
                    <th class="px-6 py-4 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($kelas as $index => $k)
                <tr class="hover:bg-gray-50/50 transition-colors group table-row-animate">
                    <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $k->kelas }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('kelas.edit', $k->id) }}" class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors" title="Edit Kelas">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Hapus Kelas">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i data-lucide="school" class="w-12 h-12 mb-3 text-gray-300"></i>
                            <p class="text-base font-medium text-gray-500">Belum ada data kelas</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
