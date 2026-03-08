<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Appointment Booking SaaS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts and Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased bg-[#f8fafc] min-h-screen selection:bg-indigo-100 selection:text-indigo-700">
        <!-- Decorative Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-indigo-50/50 blur-[120px]"></div>
            <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] rounded-full bg-blue-50/50 blur-[100px]"></div>
        </div>

        <div class="min-h-screen flex flex-col pt-6 sm:pt-16 pb-12">
            <!-- Header/Logo Area -->
            <div class="flex justify-center mb-8">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 flex items-center justify-center">
                        <x-heroicon-s-calendar class="w-6 h-6 text-white" />
                    </div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">{{ config('app.name') }}</span>
                </div>
            </div>

            <main class="w-full sm:max-w-3xl mx-auto px-4 sm:px-6">
                <div class="relative group">
                    <!-- Subtle card glow -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-2xl blur opacity-5 group-hover:opacity-10 transition duration-1000"></div>
                    
                    <div class="relative">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="mt-auto py-8 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </footer>
        </div>
        
        @livewireScripts
    </body>
</html>
