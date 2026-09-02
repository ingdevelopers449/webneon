<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neon Streaming</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-neon-dark min-h-screen flex items-center justify-center m-0 p-0">

    <div class="neon-card w-full max-w-[400px]">
        <div class="text-center mb-8">
            <img src="{{ asset('img/neonlogo.png') }}" alt="Neon Streaming Logo" class="max-w-[200px] h-auto mx-auto">
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm text-[#f0f0f0]">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="neon-input">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-[#ff4444] text-xs" />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm text-[#f0f0f0]">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="neon-input">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-[#ff4444] text-xs" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-5 text-sm">
                <input id="remember_me" type="checkbox" name="remember" class="mr-2 accent-[#ff00ff]">
                <label for="remember_me" class="mb-0">{{ __('Remember me') }}</label>
            </div>

            <button type="submit" class="btn-neon">
                {{ __('Log in') }}
            </button>

            <div class="mt-5 text-center flex justify-between text-sm">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="neon-link">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="neon-link">
                        {{ __('Registrarse') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

</body>
</html>
