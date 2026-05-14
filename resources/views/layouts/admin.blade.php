<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - {{ config('app.name', 'IT Access') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sarabun:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        body {
            font-family: 'Sarabun', sans-serif;
        }

        .sidebar-item-active {
            background-color: #fff1f0;
            border-right: 4px solid #ff4d4f;
            color: #ff4d4f;
        }

        /* Prevent layout shift during Turbo navigation */
        .turbo-progress-bar {
            background-color: #3b82f6;
        }
    </style>
</head>

<body class="bg-[#f9fafb] text-slate-700">
    <div class="flex h-screen overflow-hidden bg-[#f9fafb]" x-data="{ 
            sidebarOpen: localStorage.getItem('sidebarOpen') !== null ? JSON.parse(localStorage.getItem('sidebarOpen')) : window.innerWidth >= 1024,
            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
            }
        }">
        <!-- Sidebar Mobile Overlay -->
        <div x-show="sidebarOpen && window.innerWidth < 1024"
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false; localStorage.setItem('sidebarOpen', 'false')"></div>

        <!-- Sidebar -->
        <div id="main-sidebar" class="fixed inset-y-0 left-0 z-50 lg:static bg-white border-r border-slate-200 flex-shrink-0 flex flex-col h-full shadow-2xl lg:shadow-none transition-all duration-300 ease-in-out overflow-hidden"
            :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full lg:w-20 lg:translate-x-0'">
            <div
                class="h-16 flex items-center justify-between px-6 border-b border-slate-100 overflow-hidden flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-slate-800 truncate"
                        x-show="sidebarOpen || window.innerWidth < 1024">IT ACCESS</span>
                </div>

                <!-- Close Button Mobile -->
                <button @click="sidebarOpen = false; localStorage.setItem('sidebarOpen', 'false')"
                    class="lg:hidden text-slate-400 hover:text-slate-600 transition p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="mt-6 flex-1 space-y-1">
                <div class="px-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest"
                    x-show="sidebarOpen">Academics</div>
                <a href="{{ route('backend.dashboard') }}"
                    class="flex items-center {{ request()->routeIs('backend.dashboard*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}"
                    :class="sidebarOpen ? 'px-6 py-3' : 'px-0 py-4 justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="sidebarOpen ? 'mr-3' : 'mr-0'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                <a href="{{ route('manage.profile.signature') }}"
                    class="flex items-center {{ request()->routeIs('manage.profile.signature') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}"
                    :class="sidebarOpen ? 'px-6 py-3' : 'px-0 py-4 justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="sidebarOpen ? 'mr-3' : 'mr-0'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span x-show="sidebarOpen">จัดการลายเซ็น</span>
                </a>

                <div class="px-6 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest"
                    x-show="sidebarOpen">System (Admin)</div>
                <a href="{{ route('manage.approvals.index') }}"
                    class="flex items-center {{ request()->routeIs('manage.approvals.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}"
                    :class="sidebarOpen ? 'px-6 py-3' : 'px-0 py-4 justify-center'">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="sidebarOpen ? 'mr-3' : 'mr-0'"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span x-show="sidebarOpen" class="flex-1">จัดการคำร้อง</span>
                    @if(isset($adminTotalNotify) && $adminTotalNotify > 0)
                        <span x-show="sidebarOpen" class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">
                            {{ $adminTotalNotify }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('backend.users.index') }}"
                    class="flex items-center {{ request()->routeIs('backend.users.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}"
                    :class="sidebarOpen ? 'px-6 py-3' : 'px-0 py-4 justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="sidebarOpen ? 'mr-3' : 'mr-0'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span x-show="sidebarOpen">User Management</span>
                </a>
                <a href="{{ route('backend.access-options.index') }}"
                    class="flex items-center {{ request()->routeIs('backend.access-options.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}"
                    :class="sidebarOpen ? 'px-6 py-3' : 'px-0 py-4 justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="sidebarOpen ? 'mr-3' : 'mr-0'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    <span x-show="sidebarOpen">จัดการรายการเข้าถึง</span>
                </a>
                <a href="{{ route('backend.approval-configs.index') }}"
                    class="flex items-center {{ request()->routeIs('backend.approval-configs.*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}"
                    :class="sidebarOpen ? 'px-6 py-3' : 'px-0 py-4 justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="sidebarOpen ? 'mr-3' : 'mr-0'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                    </svg>
                    <span x-show="sidebarOpen">กำหนดขั้นตอนการอนุมัติ</span>
                </a>
                <a href="{{ route('backend.nda-config.index') }}"
                    class="flex items-center {{ request()->routeIs('backend.nda-config*') ? 'sidebar-item-active' : 'text-slate-500 hover:bg-slate-50' }}"
                    :class="sidebarOpen ? 'px-6 py-3' : 'px-0 py-4 justify-center'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :class="sidebarOpen ? 'mr-3' : 'mr-0'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span x-show="sidebarOpen">ตั้งค่าผู้เซ็น NDA (บริษัท)</span>
                </a>
            </nav>

            <!-- Bottom Profile -->
            <div class="mt-auto border-t border-slate-100 p-4 overflow-hidden">
                <div class="flex items-center space-x-3 mb-4" :class="sidebarOpen ? '' : 'justify-center space-x-0'">
                    <div
                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold flex-shrink-0">
                        {{ substr(Auth::user()->fullname, 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen">
                        <p class="text-sm font-bold text-slate-800 truncate max-w-[120px]">{{ Auth::user()->fullname }}
                        </p>
                        <span
                            class="text-[10px] px-2 py-0.5 bg-orange-100 text-orange-600 rounded-md font-bold uppercase">{{ Auth::user()->role === 'admin' ? 'ADMIN' : 'ICT' }}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center text-sm font-medium text-red-500 hover:bg-red-50 hover:text-red-600 rounded-lg"
                            :class="sidebarOpen ? 'px-4 py-2.5' : 'p-2 justify-center'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0"
                                :class="sidebarOpen ? 'mr-2' : 'mr-0'" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span x-show="sidebarOpen">ออกจากระบบ</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header
                class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-4 md:px-8 flex-shrink-0">
                <div class="flex items-center space-x-2 md:space-x-4">
                    <button @click="toggleSidebar()"
                        class="p-2 -ml-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="text-sm text-slate-400 font-medium">
                        <span class="text-slate-300 mx-2">/</span>
                        @yield('breadcrumb', 'Dashboard')
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('request.index') }}"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        กลับหน้าหลัก
                    </a>
                    <!-- <div
                        class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xs">
                        {{ substr(Auth::user()->fullname, 0, 1) }}
                    </div> -->
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8">


                @yield('content')

                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    @if(session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: "{{ session('success') }}",
                            timer: 3000,
                            showConfirmButton: false
                        });
                    @endif

                    @if(session('error'))
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: "{{ session('error') }}"
                        });
                    @endif

                    @if($errors->any())
                        Swal.fire({
                            icon: 'warning',
                            title: 'ข้อมูลไม่ถูกต้อง',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                            confirmButtonColor: '#3b82f6',
                            confirmButtonText: 'ตกลง'
                        });
                    @endif
                </script>
                @yield('scripts')
            </main>
        </div>
    </div>
</body>

</html>