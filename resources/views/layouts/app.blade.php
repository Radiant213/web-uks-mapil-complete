<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem UKS - CRM Edition</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite & Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* bg-gray-100 equivalent */
        }
    </style>
</head>
<body class="text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between hidden md:flex z-10 shadow-sm">
        <div>
            <!-- Logo -->
            <div class="h-20 flex items-center px-8 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                        <i data-lucide="cross"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-800">UKS<span class="text-indigo-600">App</span></span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 mt-4">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
                
                <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('/') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->is('/') ? 'text-indigo-600' : '' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('pengobatan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('pengobatan*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                    <i data-lucide="activity" class="w-5 h-5 {{ request()->is('pengobatan*') ? 'text-indigo-600' : '' }}"></i>
                    Kunjungan
                </a>

                <a href="{{ route('obat.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('obat*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                    <i data-lucide="pill" class="w-5 h-5 {{ request()->is('obat*') ? 'text-indigo-600' : '' }}"></i>
                    Obat & Stok
                </a>

                @if(auth()->user()->role === 'admin')
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Master Data</p>
                    
                    <a href="{{ route('siswa.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('siswa*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                        <i class="w-5 h-5 {{ request()->is('siswa*') ? 'text-indigo-600' : '' }}" data-lucide="users"></i>
                        Siswa
                    </a>

                    <a href="{{ route('kelas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->is('kelas*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-500 hover:bg-gray-50 hover:text-slate-900 transition-colors' }}">
                        <i class="w-5 h-5 {{ request()->is('kelas*') ? 'text-indigo-600' : '' }}" data-lucide="school"></i>
                        Kelas
                    </a>
                @endif
            </nav>
        </div>

        <!-- Logout Section -->
        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 w-full rounded-xl text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors font-medium">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-[#f8fafc]">
        
        <!-- HEADER -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 z-10">
            <!-- Search Bar Dummy -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-full text-sm focus:ring-2 focus:ring-indigo-100 focus:bg-white transition-all outline-none text-slate-600">
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex items-center gap-6">
                <div class="hidden md:flex items-center gap-2 text-sm text-gray-500 bg-gray-50 px-4 py-1.5 rounded-full">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>{{ date('d M Y') }}</span>
                </div>
                
                <button class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                    <div class="flex flex-col items-end">
                        <span class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-indigo-600 font-medium capitalize">{{ auth()->user()->role }}</span>
                    </div>
                    <!-- Avatar Dummy -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff" alt="Avatar" class="w-10 h-10 rounded-full shadow-sm border-2 border-white">
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f8fafc] p-8">
            <!-- Notifikasi Session -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 flex items-center gap-3 text-green-700 shadow-sm">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500"></i>
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 text-red-700 shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <p class="font-medium text-sm">
                        {{ session('error') ?? $errors->first() }}
                    </p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
