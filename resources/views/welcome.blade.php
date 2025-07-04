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

        {{-- <style>
            body {
                background-image: url('{{ asset('images/glc3.jpg') }}'); /* Add your image path here */
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-repeat: no-repeat;
            }
        </style> --}}
    </head>
    <body class="font-sans text-white bg-cover bg-center bg-fixed bg-no-repeat" style="background-image: url('{{ asset('images/glc2.jpg') }}');">

    <!-- Overlay with dark tint -->
    <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

    <!-- Centered content -->
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4">
        <div class="bg-white bg-opacity-20 backdrop-blur-md text-black p-8 rounded-2xl shadow-2xl w-full sm:w-96">

            <!-- Title and subtitle -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-extrabold text-white tracking-wide drop-shadow-md">GLC ATTENDANCE</h1>
                <p class="mt-2 text-gray-200 text-sm">Login to view your schedule and manage student attendance.</p>
            </div>

            <!-- Navbar Buttons -->
            @if (Route::has('login'))
                <nav class="flex flex-col gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium text-center transition duration-300 shadow-md">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-gray-900 hover:bg-transparent hover:text-gray-900 text-white px-5 py-2 border border-gray-900 rounded-lg font-medium text-center transition duration-300 shadow-sm hover:shadow-lg">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-gray-900 hover:bg-transparent hover:text-gray-900 text-white px-5 py-2 border border-gray-900 rounded-lg font-medium text-center transition duration-300 shadow-sm hover:shadow-lg">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

        </div>
    </div>

</body>

        
    </body>
</html>
