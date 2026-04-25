<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SportBook') }} - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-green-800 text-white flex flex-col">
        <div class="h-16 flex items-center justify-center border-b border-green-700">
            <span class="text-2xl font-bold tracking-wider">SportBook</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-700' : 'hover:bg-green-700' }}">Dashboard</a>
            <a href="{{ route('admin.field-types.index') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.field-types.*') ? 'bg-green-700' : 'hover:bg-green-700' }}">Jenis Lapangan</a>
            <a href="{{ route('admin.fields.index') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.fields.*') ? 'bg-green-700' : 'hover:bg-green-700' }}">Lapangan</a>
            <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-green-700' : 'hover:bg-green-700' }}">Bookings</a>
            <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-green-700' : 'hover:bg-green-700' }}">Laporan</a>
        </nav>
        <div class="p-4 border-t border-green-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-green-700 rounded transition-colors">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white shadow flex items-center justify-between px-6">
            <h2 class="text-xl font-semibold text-gray-800">
                @yield('header', 'Admin Panel')
            </h2>
            <div class="flex items-center">
                <span class="text-gray-600">{{ Auth::user()->name }}</span>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
