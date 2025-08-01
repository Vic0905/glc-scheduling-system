<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-image: url('{{ asset('images/glc2.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="font-sans antialiased text-white"> 

    <div class="min-h-screen flex items-center justify-center relative">

        <!-- Dark overlay for contrast -->
        <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

        <!-- Card Container -->
        <div class="relative z-10 w-full max-w-md mx-auto p-6 bg-white bg-opacity-20 backdrop-blur-md shadow-2xl rounded-2xl">
            <div class="flex justify-center mb-6">
                <a href="/">
                    <x-application-logo class="w-30 h-30 text-white" />
                </a>
            </div>

            <!-- Blade slot content (login form, etc.) -->
            {{ $slot }}
        </div>
    </div>

</body>
</html>
