@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('pengobatan.index') }}" class="p-2 text-gray-400 hover:text-slate-800 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Catat Kunjungan Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Buku rekam medis UKS harian.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-3xl">
    <form action="{{ route('pengobatan.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Identitas Pasien & Diagnosa -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Pasien (Siswa)</label>
                <div class="relative">
                    <select name="student_id" required class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700 appearance-none">
                        <option value="">-- Cari Nama Siswa --</option>
                        @foreach ($students as $siswa)
                            <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa ?? $siswa->nama }} - {{ $siswa->kelas->kelas ?? '' }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Keluhan Utama</label>
                <textarea name="keluhan" required rows="2" placeholder="Contoh: Pusing, mual, sakit perut..." 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Diagnosa / Tindakan</label>
                <input type="text" name="diagnosa" required placeholder="Contoh: Maag, Istirahat di ranjang 1..." 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700">
            </div>
        </div>

        <!-- Pemberian Obat (Opsional) -->
        <div class="pt-6 border-t border-gray-100">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Pemberian Obat (Opsional)</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Obat</label>
                    <div class="relative">
                        <select name="medicine_id" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700 appearance-none">
                            <option value="">-- Tidak dikasih obat --</option>
                            @foreach ($medicines as $obat)
                                <option value="{{ $obat->id }}">{{ $obat->nama_obat }} (Stok: {{ $obat->stok }})</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah</label>
                    <input type="number" name="jumlah_obat" min="1" placeholder="0" 
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700">
                </div>
            </div>
        </div>

        <div class="pt-6 flex justify-end gap-3">
            <a href="{{ route('pengobatan.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-colors">Simpan Kunjungan</button>
        </div>
    </form>
</div>
@endsection
