<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LapTrackr</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col items-center justify-center">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Welcome to LapTrackr</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">Track your lap times with ease</p>
            </div>

            <div class="space-y-4">
                @auth
                    <a href="{{ url('/admin') }}" class="block w-full px-8 py-3 text-center bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block w-full px-8 py-3 text-center bg-gray-800 hover:bg-gray-700 text-white rounded-lg font-medium">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block w-full px-8 py-3 text-center border border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 text-gray-800 dark:text-gray-200 rounded-lg font-medium">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </body>
</html>
