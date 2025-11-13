<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer - Dirental Atuh')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .active-menu {
            background: linear-gradient(135deg, #ff9b9b 0%, rgb(231, 81, 81) 100%);
            color: white;
        }

        .active-menu:hover {
            background: linear-gradient(135deg, #ff9b9b 0%, rgb(192, 48, 48) 100%);
        }
    </style>
</head>

<body class="bg-red-200">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-red-300 shadow-lg w-64 flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-car text-2xl text-black-500"></i>
                    <span class="logo-text text-xl font-bold text-black-800">Dirental Atuh</span>
                </div>
                <div class="mt-2 text-sm text-gray-700 logo-text">Customer Panel</div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('customer.dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-black-700 hover:bg-pink-50 hover:text-black-600 transition-all duration-200 {{ request()->routeIs('customer.dashboard') ? 'active-menu' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="sidebar-text font-medium">Dashboard</span>
                </a>

                <a href="{{ route('customer.rental') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-black-700 hover:bg-pink-50 hover:text-black-600 transition-all duration-200 {{ request()->routeIs('customer.rental') ? 'active-menu' : '' }}">
                    <i class="fas fa-car-side w-5"></i>
                    <span class="sidebar-text font-medium">Rental</span>
                </a>

                <a href="{{ route('customer.pembayaran') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-black-700 hover:bg-pink-50 hover:text-black-600 transition-all duration-200 {{ request()->routeIs('customer.pembayaran') ? 'active-menu' : '' }}">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span class="sidebar-text font-medium">Pembayaran</span>
                </a>

                <a href="{{ route('customer.denda') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-black-700 hover:bg-pink-50 hover:text-black-600 transition-all duration-200 {{ request()->routeIs('customer.denda') ? 'active-menu' : '' }}">
                    <i class="fas fa-credit-card w-5"></i>
                    <span class="sidebar-text font-medium">Denda</span>
                </a>

                <a href="{{ route('customer.profile') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-black-700 hover:bg-pink-50 hover:text-black-600 transition-all duration-200 {{ request()->routeIs('customer.profile') ? 'active-menu' : '' }}">
                    <i class="fas fa-user w-5"></i>
                    <span class="sidebar-text font-medium">Profil Saya</span>
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3 mb-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-pink-500 to-red-600 rounded-full flex items-center justify-center">
                        <span
                            class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="sidebar-text">
                        <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-500">Customer</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center space-x-3 w-full px-4 py-2 text-black-700 hover:bg-red-50 hover:text-black-700 rounded-lg transition-all duration-200">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="sidebar-text font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-pink shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-800">@yield('title')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notification -->
                        <button class="relative p-2 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-700">Halo, <strong>{{ Auth::user()->name }}</strong></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function handleResize() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth < 768) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();
    </script>
</body>

</html>
