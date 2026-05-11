<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem UKS</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Vite & Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased flex h-screen overflow-hidden">
    <!-- Left Side: Login Form -->
    <div class="w-full md:w-1/2 flex flex-col justify-center items-center px-8 sm:px-12 lg:px-24 bg-white z-10 shadow-2xl relative login-form-animate">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-10 login-field-animate">
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-200">
                    <i data-lucide="cross"></i>
                </div>
                <span class="font-bold text-2xl tracking-tight text-slate-800">UKS<span class="text-indigo-600">App</span></span>
            </div>

            <h1 class="text-3xl font-bold text-slate-800 mb-2 tracking-tight login-field-animate">Welcome back!</h1>
            <p class="text-gray-500 mb-8 text-sm login-field-animate">Please enter your credentials to access the system.</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-center gap-3 text-red-700 alert-animate">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <p class="font-medium text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div class="login-field-animate">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@uks.com" 
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all outline-none text-slate-700">
                    </div>
                </div>
                
                <div class="login-field-animate">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="password" name="password" required placeholder="••••••••" 
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all outline-none text-slate-700">
                    </div>
                </div>

                <div class="flex items-center justify-between mt-2 login-field-animate">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="text-sm text-gray-600 font-medium">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                </div>

                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-medium py-3 rounded-xl transition-all shadow-lg shadow-slate-200 mt-8 flex justify-center items-center gap-2 btn-animate login-field-animate">
                    Sign in to account
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Right Side: Decorative/Image (Hidden on mobile) -->
    <div class="hidden md:flex md:w-1/2 bg-indigo-600 relative overflow-hidden flex-col justify-center items-center p-12 text-center login-deco-animate">
        <!-- Abstract Background Shapes -->
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-indigo-500 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-indigo-700 rounded-full blur-3xl opacity-50"></div>
        
        <div class="relative z-10 max-w-md">
            <div class="bg-white/10 p-6 rounded-2xl backdrop-blur-sm border border-white/20 mb-8 shadow-2xl animate-scaleIn anim-delay-3">
                <i data-lucide="activity" class="w-16 h-16 text-white mb-4 mx-auto"></i>
                <h2 class="text-3xl font-bold text-white mb-4 leading-tight">Manage Your UKS with Ease.</h2>
                <p class="text-indigo-100 text-sm leading-relaxed">Sistem Informasi Manajemen Unit Kesehatan Sekolah. Mencatat riwayat medis siswa dan mengontrol ketersediaan obat secara otomatis.</p>
            </div>
            
            <div class="flex gap-4 justify-center text-indigo-200 text-xs animate-fadeIn anim-delay-5">
                <span>Admin: admin@uks.com</span>
                <span>•</span>
                <span>PMR: petugas@uks.com</span>
            </div>
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
