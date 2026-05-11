@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center gap-4 animate-fadeInUp">
    <a href="{{ route('siswa.index') }}" class="p-2 text-gray-400 hover:text-slate-800 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Data Siswa</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui data siswa yang sudah ada di sistem.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-3xl animate-fadeInUp anim-delay-2">
    <form action="{{ route('siswa.update', $student->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">NISN / NIS</label>
                <input type="text" name="nis" value="{{ $student->nisn ?? $student->nis }}" required placeholder="Contoh: 100234" 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ $student->nama_siswa ?? $student->nama }}" required placeholder="Masukkan nama siswa" 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                <div class="relative">
                    <select name="kelas_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700 appearance-none">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}" {{ $k->id == $student->kelas_id ? 'selected' : '' }}>{{ $k->kelas }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                <div class="relative">
                    <select name="jenis_kelamin" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700 appearance-none">
                        <option value="">-- Pilih Gender --</option>
                        <option value="L" {{ ($student->jenis_kelamin ?? 'L') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ ($student->jenis_kelamin ?? 'P') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
            <textarea name="alamat" rows="3" placeholder="Alamat rumah (opsional)..." 
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none text-slate-700">{{ $student->alamat }}</textarea>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('siswa.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-colors btn-animate">Update Data</button>
        </div>
    </form>
</div>
@endsection
