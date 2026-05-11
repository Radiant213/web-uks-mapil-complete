@extends('layouts.app')

@section('content')
<!-- GREETING SECTION -->
<div class="mb-8 animate-fadeInUp">
    <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Hello, {{ auth()->user()->name }}! 👋</h1>
    <p class="text-gray-500 mt-1 text-sm">This is what's happening in your UKS today.</p>
</div>

<!-- METRIC CARDS GRID -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Card 1: Total Kunjungan (The Vibrant Purple Card) -->
    <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-200 relative overflow-hidden group card-hover animate-fadeInUp anim-delay-1">
        <!-- Decorative shapes -->
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
        <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-indigo-500/50 rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-4">
                <span class="text-indigo-100 text-sm font-medium">Total Kunjungan</span>
                <div class="w-8 h-8 rounded-full bg-white text-indigo-600 flex items-center justify-center">
                    <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </div>
            </div>
            <h2 class="text-4xl font-bold mb-2">{{ $total_kunjungan }} <span class="text-lg font-normal text-indigo-200">Siswa</span></h2>
            <div class="flex items-center gap-2">
                <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-semibold backdrop-blur-sm flex items-center gap-1">
                    <i data-lucide="calendar" class="w-3 h-3"></i> Tahun {{ date('Y') }}
                </span>
                <span class="text-xs text-indigo-200">Rekap kunjungan</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Siswa -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow card-hover animate-fadeInUp anim-delay-2">
        <div class="flex justify-between items-start mb-4">
            <span class="text-gray-500 text-sm font-medium">Total Siswa Aktif</span>
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                <i data-lucide="users" class="w-4 h-4"></i>
            </div>
        </div>
        <h2 class="text-4xl font-bold text-slate-800 mb-2">{{ $total_siswa }}</h2>
        <div class="flex items-center gap-2">
            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1">
                <i data-lucide="trending-up" class="w-3 h-3"></i> Aktif
            </span>
            <span class="text-xs text-gray-400">Terdaftar di sistem</span>
        </div>
    </div>

    <!-- Card 3: Macam Obat -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow card-hover animate-fadeInUp anim-delay-3">
        <div class="flex justify-between items-start mb-4">
            <span class="text-gray-500 text-sm font-medium">Macam Obat Tersedia</span>
            <div class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                <i data-lucide="pill" class="w-4 h-4"></i>
            </div>
        </div>
        <h2 class="text-4xl font-bold text-slate-800 mb-2">{{ $total_obat }} <span class="text-lg font-normal text-gray-400">Jenis</span></h2>
        <div class="flex items-center gap-2">
            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1">
                <i data-lucide="info" class="w-3 h-3"></i> Info
            </span>
            <span class="text-xs text-gray-400">Di Apotek UKS</span>
        </div>
    </div>

</div>

<!-- CHART SECTION -->
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 animate-fadeInUp anim-delay-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Grafik Kunjungan</h3>
            <p class="text-xs text-gray-400 mt-1">Rekapitulasi tahun {{ date('Y') }}</p>
        </div>
        <div class="p-2 bg-gray-50 rounded-lg text-gray-500 hover:bg-gray-100 cursor-pointer transition-colors">
            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
        </div>
    </div>
    
    <div class="h-[300px] w-full">
        <canvas id="kunjunganChart"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('kunjunganChart').getContext('2d');
        const dataKunjungan = @json($visitsPerMonth);

        // Gradient for Line Chart (from CRM reference, we use sleek purple)
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.5)'); // Indigo-600
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Visits',
                    data: dataKunjungan,
                    backgroundColor: '#4f46e5', // indigo-600
                    borderRadius: 6, // rounded bars!
                    barThickness: 24,
                    hoverBackgroundColor: '#4338ca' // indigo-700
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart',
                    delay: function(context) {
                        return context.dataIndex * 80;
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: 'Inter' },
                        bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                        displayColors: false,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9', // slate-100
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Inter', size: 12 },
                            color: '#94a3b8', // slate-400
                            stepSize: 1
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: {
                            font: { family: 'Inter', size: 12 },
                            color: '#94a3b8'
                        },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
