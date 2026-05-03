@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Buku Kunjungan UKS</h1>
        <p class="text-sm text-gray-500 mt-1">Rekam medis dan riwayat kunjungan siswa.</p>
    </div>
    <a href="{{ route('pengobatan.create') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium transition-colors shadow-sm">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Catat Kunjungan
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Tabel Container dengan Scroll Horizontal untuk Mobile -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Tanggal & Waktu</th>
                    <th class="px-6 py-4">Nama Pasien</th>
                    <th class="px-6 py-4">Keluhan</th>
                    <th class="px-6 py-4">Diagnosa</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($treatments as $index => $t)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar-clock" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-slate-700 font-medium">{{ \Carbon\Carbon::parse($t->tanggal_kunjungan)->format('d M Y') }}</span>
                        </div>
                        <span class="text-xs text-gray-400 ml-6">{{ \Carbon\Carbon::parse($t->tanggal_kunjungan)->format('H:i') }} WIB</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-slate-800">{{ $t->student->nama ?? 'Siswa Dihapus' }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600 truncate max-w-[200px]" title="{{ $t->keluhan }}">
                        {{ $t->keluhan }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                            {{ $t->diagnosa }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pengobatan.show', $t->id) }}" class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors" title="Lihat Detail & Resep">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i data-lucide="inbox" class="w-12 h-12 mb-3 text-gray-300"></i>
                            <p class="text-base font-medium text-gray-500">Belum ada kunjungan</p>
                            <p class="text-sm">Catatan kunjungan baru akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Footer Tabel / Pagination Area -->
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 flex items-center justify-between text-sm text-gray-500">
        <span>Menampilkan total {{ $treatments->count() }} kunjungan.</span>
    </div>
</div>
@endsection
