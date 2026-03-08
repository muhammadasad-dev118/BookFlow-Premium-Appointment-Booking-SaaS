<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account — {{ config('app.name', 'BookFlow') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .auth-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="bg-[#0f172a] text-slate-200 antialiased min-h-screen flex items-center justify-center">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md mx-4">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 mb-4">
                <svg class="w-7 h-7 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Create your account</h1>
            <p class="text-slate-400 text-sm mt-1">Start managing your appointments today</p>
        </div>

        <div class="auth-card p-8">
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-400 mb-1.5">Your name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-white text-sm placeholder-slate-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 outline-none transition">
                </div>
                <div>
                    <label for="business_name" class="block text-sm font-medium text-slate-400 mb-1.5">Business name</label>
                    <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" required
                        class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-white text-sm placeholder-slate-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 outline-none transition">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-400 mb-1.5">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-white text-sm placeholder-slate-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 outline-none transition">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-400 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-white text-sm placeholder-slate-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 outline-none transition">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-400 mb-1.5">Confirm password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-xl text-white text-sm placeholder-slate-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/30 outline-none transition">
                </div>
                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-900 font-semibold rounded-xl hover:shadow-lg hover:shadow-amber-500/25 transition-all text-sm">
                    Create Account
                </button>
            </form>

            <p class="text-center text-sm text-slate-400 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300 font-medium transition">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
