<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GLC ATTENDANCE</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Tailwind CSS (Assuming you're using a CDN) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body {
                background-image: url('{{ asset('images/glc3.jpg') }}'); /* Add your image path here */
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-repeat: no-repeat;
            }
        </style>
    </head>
    <body class="font-sans text-white">

        <div class="min-h-screen flex items-center justify-center">
            <div class="bg-gray-200 bg-opacity-50 text-black p-8 rounded-xl shadow-2xl w-full sm:w-96 overflow-hidden">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">GLC ATTENDANCE</h1>
                    <p class="mt-2 text-gray-600">Login to view your schedule and manage student attendance.</p>
                </div>
        
                <!-- Navbar -->
                @if (Route::has('login'))
                    <nav class="flex justify-center gap-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md transition-all duration-300">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-gray-900 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 border-gray-900 hover:border-gray-900 text-white hover:text-gray-900 rounded-lg transition ease-in duration-300">
                                Log in
                            </a>
                            
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-gray-900 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 border-gray-900 hover:border-gray-900 text-white hover:text-gray-900 rounded-lg transition ease-in duration-300">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
        
    </body>
</html>
