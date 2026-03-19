{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <!-- Vite (for Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Glassmorphism effects */
        .glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Hide Alpine.js elements before init */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Modern Gradient with Glass Effect -->
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="w-72 bg-gradient-to-b from-indigo-600 via-purple-600 to-pink-600 text-white flex flex-col relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <div class="absolute top-20 -left-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-20 -right-10 w-60 h-60 bg-white rounded-full blur-3xl"></div>
                </div>

                <!-- Sidebar Content -->
                <div class="relative z-10 flex flex-col h-full p-6">
                    <!-- Logo Section -->
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-22 h-22 bg-white/20 rounded-xl backdrop-blur-lg flex items-center justify-center">
                            <img src="{{ asset(config('app.logo', 'src/3spro.png')) }}" alt="Logo" class="w-16 h-16 object-contain">
                        </div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight">{{ config('app.name') }}</h1>
                            <p class="text-xs text-white/70">Management System</p>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <nav class="flex-1 space-y-1 mt-6">
                        @yield('sidebar-menu')
                    </nav>

                    <!-- User Profile Section -->
                    <div class="mt-auto pt-6 border-t border-white/20">
                        <div class="flex items-center space-x-3 p-3 bg-white/10 rounded-xl backdrop-blur-lg">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ substr(session('user_name'), 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold truncate">{{ session('user_name') }}</p>
                                <p class="text-xs text-white/70 truncate">{{ session('position') }}</p>
                                <p class="text-xs text-white/50 truncate">{{ session('dept') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Toggle -->
        <div id="mobileSidebar" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleMobileSidebar()"></div>
            <div class="absolute left-0 top-0 h-full w-72 bg-gradient-to-b from-indigo-600 via-purple-600 to-pink-600 text-white transform transition-transform duration-300 ease-out">
                <!-- Same sidebar content as above -->
                <div class="relative h-full p-6">
                    <div class="absolute top-0 left-0 w-full h-full opacity-10">
                        <div class="absolute top-20 -left-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-center space-x-3 mb-8">
                            <div class="w-12 h-12 bg-white/20 rounded-xl backdrop-blur-lg flex items-center justify-center">
                                <img src="{{ asset(config('app.logo', 'src/3spro.png')) }}" alt="Logo" class="w-12 h-12 object-contain">
                            </div>
                            <div>
                                <h1 class="text-xl font-bold">{{ config('app.name') }}</h1>
                                <p class="text-xs text-white/70">Management System</p>
                            </div>
                        </div>

                        <nav class="flex-1 space-y-1">
                            @yield('sidebar-menu')
                        </nav>

                        <div class="mt-auto pt-6 border-t border-white/20">
                            <div class="flex items-center space-x-3 p-3 bg-white/10 rounded-xl backdrop-blur-lg">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr(session('user_name'), 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold truncate">{{ session('user_name') }}</p>
                                    <p class="text-xs text-white/70 truncate">{{ session('position') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden bg-slate-50">
            <!-- Top Navigation - Glass Effect -->
            <header class="glass-card shadow-sm sticky top-0 z-30">
                <div class="px-6 py-3 flex justify-between items-center">
                    <button id="sidebarToggle" class="md:hidden w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 text-white flex items-center justify-center hover:from-indigo-600 hover:to-purple-600 transition-all">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="flex-1 flex justify-end items-center space-x-4">
                        <!-- Quick Actions -->
                        <div class="hidden md:flex items-center space-x-2">
                            <button class="w-10 h-10 rounded-xl bg-white text-gray-600 hover:bg-gray-50 transition-all flex items-center justify-center shadow-sm">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="w-10 h-10 rounded-xl bg-white text-gray-600 hover:bg-gray-50 transition-all flex items-center justify-center shadow-sm">
                                <i class="fas fa-calendar-alt"></i>
                            </button>
                        </div>

                        <!-- Notifications -->
                        @yield('notifications')

                        <!-- Default Notifications -->
                        @hasSection('notifications')
                        @else
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="relative w-10 h-10 rounded-xl bg-white text-gray-600 hover:bg-gray-50 transition-all flex items-center justify-center shadow-sm">
                                    <i class="fas fa-bell"></i>
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>

                                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">Notifications</p>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                                            <p class="text-sm text-gray-900">New job request received</p>
                                            <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                                        </a>
                                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 transition-colors">
                                            <p class="text-sm text-gray-900">Parts status updated</p>
                                            <p class="text-xs text-gray-500 mt-1">1 hour ago</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 bg-white pl-3 pr-2 py-2 rounded-xl shadow-sm hover:shadow-md transition-all">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr(session('user_name'), 0, 1) }}
                                </div>
                                <span class="hidden md:inline text-sm font-medium text-gray-700">{{ session('login_id') }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-900">{{ session('user_name') }}</p>
                                    <p class="text-xs text-gray-500">{{ session('position') }} | {{ session('dept') }}</p>
                                </div>

                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-user w-5 text-gray-400"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-cog w-5 text-gray-400"></i>
                                    <span>Settings</span>
                                </a>

                                <div class="border-t border-gray-100 my-2"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-5"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8">
                <!-- Welcome Banner (only on dashboard) -->
                @hasSection('showWelcome')
                <div class="mb-8 animate-fade-in">
                    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-20 -mb-20 blur-3xl"></div>

                        <div class="relative z-10">
                            <h1 class="text-3xl md:text-4xl font-bold mb-2">Welcome back, {{ session('user_name') }}! 👋</h1>
                            <p class="text-white/90 text-lg">Here's what's happening with your {{ session('dept') }} today.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Alerts -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl animate-fade-in">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl animate-fade-in">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                <!-- Page Content -->
                <div class="animate-fade-in">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 px-6 py-3 text-sm text-gray-600">
                <div class="flex justify-between items-center">
                    <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
                    <span class="text-xs text-gray-400">v1.0.0</span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Alpine.js for dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        function toggleMobileSidebar() {
            document.getElementById('mobileSidebar').classList.toggle('hidden');
        }

        document.getElementById('sidebarToggle')?.addEventListener('click', toggleMobileSidebar);

        // Close mobile sidebar when clicking on a link
        document.querySelectorAll('#mobileSidebar a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileSidebar').classList.add('hidden');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
