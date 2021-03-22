<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <style>
            /*body {*/
            /*    font-family: 'Nunito';*/
            /*}*/
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
            <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <a href="{{ url('/jobs/all') }}" class="text-sm text-gray-700 underline">{{ __('All Jobs')  }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">{{ __('Login')  }}</a>
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">{{ __('Register') }}</a>
                @endauth
            </div>

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="dark:text-white px-6 py-4">
                    This Will Be The Landing Page
                </div>

                <div class="mt-5 px-6 py-4 flex justify-between">
                    <a href="{{ route('register') }}" class="items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Register') }}
                    </a>

                    <a href="{{ url('/jobs/all') }}" class="items-center px-4 py-2 bg-gray-white border border-gray-900 rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('All Jobs')  }}
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
