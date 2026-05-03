@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('pengobatan.index') }}" class="p-2 text-gray-400 hover:text-slate-800 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Rekam Medis</h1>
        <p class="text-sm text-gray-500 mt-1">Rincian kunjungan dan obat yang diberikan.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Left Column: Patient Details -->
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i> Informasi Pasien
                </h3>
                <span class="text-sm text-gray-400">ID Kunjungan: #{{ str_pad($treatment->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Siswa</p>
                        <p class="text-sm font-medium text-slate-800">{{ $treatment->student->nama ?? $treatment->student->nama_siswa ?? 'Dihapus' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kelas</p>
                        <p class="text-sm font-medium text-slate-800">{{ $treatment->student->kelas->kelas ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Keluhan</p>
                        <p class="text-sm text-slate-700">{{ $treatment->keluhan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Diagnosa</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                            {{ $treatment->diagnosa }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medicine List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="pill" class="w-5 h-5 text-indigo-600"></i> Resep Obat Diberikan
                </h3>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse ($treatment->treatment_details as $detail)
                <div class="p-4 px-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <i data-lucide="tablets" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $detail->medicine->nama_obat ?? 'Obat Dihapus' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-slate-800">{{ $detail->jumlah_obat }} <span class="text-xs text-gray-400 font-normal">{{ $detail->medicine->satuan ?? 'pcs' }}</span></p>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <i data-lucide="shield-check" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-sm font-medium text-gray-500">Pasien tidak diberikan obat.</p>
                    <p class="text-xs text-gray-400">Hanya melakukan konsultasi atau istirahat.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Meta Info -->
    <div class="space-y-6">
        <div class="bg-indigo-600 rounded-2xl shadow-sm overflow-hidden text-white p-6 relative">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            
            <h3 class="text-indigo-100 text-xs font-semibold uppercase tracking-wider mb-4 relative z-10">Waktu Kunjungan</h3>
            
            <div class="flex flex-col gap-1 relative z-10">
                <span class="text-4xl font-bold">{{ \Carbon\Carbon::parse($treatment->tanggal_kunjungan)->format('H:i') }}</span>
                <span class="text-indigo-200">{{ \Carbon\Carbon::parse($treatment->tanggal_kunjungan)->format('l, d F Y') }}</span>
            </div>
            
            <div class="mt-6 pt-6 border-t border-indigo-500/50 relative z-10">
                <div class="flex items-center gap-2 text-sm text-indigo-100">
                    <i data-lucide="check-circle" class="w-4 h-4 text-green-300"></i>
                    Status: Selesai
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
