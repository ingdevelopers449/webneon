<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Neon Streaming</title>
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

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm text-[#f0f0f0]">{{ __('Name') }}</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="neon-input">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-[#ff4444] text-xs" />
            </div>

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm text-[#f0f0f0]">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="neon-input">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-[#ff4444] text-xs" />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm text-[#f0f0f0]">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="neon-input">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-[#ff4444] text-xs" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-5">
                <label for="password_confirmation" class="block mb-2 text-sm text-[#f0f0f0]">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="neon-input">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[#ff4444] text-xs" />
            </div>

            <button type="submit" class="btn-neon mt-2">
                {{ __('Register') }}
            </button>

            <div class="mt-5 text-center text-sm">
                <a href="{{ route('login') }}" class="neon-link">
                    {{ __('Already registered?') }}
                </a>
            </div>
        </form>
    </div>

</body>
</html>
