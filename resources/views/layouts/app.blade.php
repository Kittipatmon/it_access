<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'IT Access Request') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Outfit:wght@100..900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Sarabun', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ mobileMenuOpen: false }">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center font-bold text-xl text-blue-600">
                        IT ACCESS
                    </div>
                    <!-- Desktop Menu -->
                    <div class="hidden sm:-my-px sm:ml-10 sm:flex sm:space-x-8">
                        <a href="{{ route('request.index') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('request.*') ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                            หน้าแรก (ส่งคำร้อง)
                        </a>
                        <a href="{{ route('tracking.index') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('tracking.*') ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out relative">
                            ติดตามสถานะ
                            @if(isset($navPendingCount) && $navPendingCount > 0)
                                <span class="absolute top-3 -right-3 flex h-4 w-4">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] text-white items-center justify-center font-bold">
                                        {{ $navPendingCount }}
                                    </span>
                                </span>
                            @endif
                        </a>
                        @auth
                            @if(Auth::user()->role === 'admin' || Auth::user()->dept_id == 16)
                                <a href="{{ route('backend.dashboard') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('backend.*') ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-slate-500 hover:text-blue-600 hover:border-blue-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    หลังบ้าน (Admin)
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Right Side: Profile Dropdown & Mobile Toggle -->
                <div class="flex items-center space-x-4">
                    <!-- Desktop Manual Link -->
                    <a href="#"
                        class="text-sm font-medium text-slate-600 hover:text-slate-900 border-r border-slate-200 pr-4 mr-2 hidden lg:block">คู่มือการใช้</a>

                    @auth
                        <div class="relative hidden sm:block" x-data="{ profileOpen: false }">
                            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
                                class="flex items-center space-x-2 bg-white border-2 border-slate-900 rounded-full pl-1 pr-3 py-1 shadow-sm hover:bg-slate-50 transition focus:outline-none">
                                <div
                                    class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden border border-slate-100 flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->fullname) }}&color=7F9CF5&background=EBF4FF"
                                        alt="Avatar">
                                </div>
                                <span
                                    class="text-sm font-bold text-slate-700 hidden sm:inline">{{ Auth::user()->employee_code }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 transition-transform"
                                    :class="{'rotate-180': profileOpen}" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Card -->
                            <div x-show="profileOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                                style="display: none;">

                                <!-- User Header -->
                                <div class="p-5 flex items-center space-x-4 border-b border-slate-50">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 overflow-hidden ring-2 ring-blue-100">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->fullname) }}&color=7F9CF5&background=EBF4FF"
                                            alt="Avatar">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->fullname }}
                                        </p>
                                        <p class="text-xs text-slate-400 truncate">
                                            {{ Auth::user()->position ?? 'Employee' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-2">
                                    @if(Auth::user()->role === 'admin' || Auth::user()->dept_id == 16)
                                        <a href="{{ route('backend.dashboard') }}"
                                            class="flex items-center space-x-3 px-5 py-2.5 text-sm text-blue-600 hover:bg-blue-50 transition font-bold bg-blue-50/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                            <span>Dashboard (Admin)</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('manage.profile.signature') }}"
                                        class="flex items-center space-x-3 px-5 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        <span class="font-medium">จัดการลายเซ็น</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center space-x-3 px-5 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="font-medium">การตั้งค่า</span>
                                    </a>
                                </div>

                                <!-- Logout -->
                                <div class="border-t border-slate-50">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center space-x-3 px-5 py-4 text-sm text-red-600 hover:bg-red-50 transition group">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-red-500 group-hover:scale-110 transition-transform"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            <span class="font-bold">ออกจากระบบ</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">เข้าสู่ระบบ</a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center sm:hidden ml-2">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }"
                                    class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }"
                                    class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Backdrop -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="sm:hidden fixed inset-0 top-16 z-40 bg-slate-900/50 backdrop-blur-sm" aria-hidden="true"
                style="display: none;"></div>

            <!-- Mobile Menu Panel -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                class="sm:hidden absolute top-16 left-0 right-0 bg-white border-b border-slate-200 shadow-2xl z-50"
                style="display: none;">

                @auth
                    <!-- User Info for Mobile Nav -->
                    <div class="p-4 border-b border-slate-100 flex items-center space-x-3 bg-slate-50/50">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden border border-slate-200 flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->fullname) }}&color=7F9CF5&background=EBF4FF"
                                alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->fullname }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->employee_code }}</p>
                        </div>
                    </div>
                @endauth

                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('request.index') }}"
                        class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('request.*') ? 'border-blue-500 bg-blue-50 text-blue-700 font-bold' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800' }} text-base font-medium">หน้าแรก
                        (ส่งคำร้อง)</a>
                    <a href="{{ route('tracking.index') }}"
                        class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('tracking.*') ? 'border-blue-500 bg-blue-50 text-blue-700 font-bold' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800' }} text-base font-medium relative">
                        ติดตามสถานะ
                        @if(isset($navPendingCount) && $navPendingCount > 0)
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-600 ml-2">
                                {{ $navPendingCount }} งานรออนุมัติ
                            </span>
                        @endif
                    </a>

                    @auth
                        @if(Auth::user()->role === 'admin' || Auth::user()->dept_id == 16)
                            <a href="{{ route('backend.dashboard') }}"
                                class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-blue-600 hover:bg-blue-50 font-bold">Dashboard
                                (Admin)</a>
                        @endif
                        <a href="{{ route('manage.profile.signature') }}"
                            class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-slate-600 hover:bg-slate-50 font-medium">จัดการลายเซ็น</a>
                        <a href="#"
                            class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-slate-600 hover:bg-slate-50 font-medium">การตั้งค่า</a>

                        <div class="border-t border-slate-100 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block pl-3 pr-4 py-2 border-l-4 border-transparent text-red-600 hover:bg-red-50 font-bold">
                                    ออกจากระบบ
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'ตกลง',
                timer: 3000,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'ตกลง'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'ข้อมูลไม่ครบถ้วน!',
                text: 'กรุณาตรวจสอบและแก้ไขข้อมูลในช่องที่มีสีแดง',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'ตกลง'
            });
        @endif
    </script>
    @yield('scripts')
</body>

</html>