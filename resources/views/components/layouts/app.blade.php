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
    </head>
    @auth
        @dump('authed')
        <body class="font-sans antialiased">
            <div class="h-screen overflow-hidden bg-gray-100 dark:bg-gray-900">
                <livewire:layout.navigation />
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="mx-5 py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="h-full">
                    {{ $slot }}
                </main>
                
            </div>
        </body>        
    @endauth

    @guest
        @dump('Not authed')
        <body class="font-sans text-gray-900 antialiased">
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
                <div>
                    <a href="/" wire:navigate>
                        <x-application-logo-large class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>

                @if (session()->has('failed'))
                    <div class="item-center flex w-full sm:max-w-md mt-6 px-6 py-4 bg-red-200 dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                                
                        <div class="m-auto alert alert-success text-red-700 text-center h-14 w-14 mr-5">
                            <div class="mt-2">
                                <svg class="m-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ff0000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 2H8L2 8V16L8 22H16L22 16V8L16 2Z" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 8V12" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 16.0195V16" stroke="#ff0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                            </div>
                        </div>    
                        
                        <div class="m-auto alert alert-success text-red-700 dark:text-red-500">
                
                            PVE Authentication Failed - Please contact the support team.
                            {{-- {{ session('failed') }} --}}
                        </div>
                        
                    </div>
                @endif

                <x-flash-error :messages="$errors->get('email')" class="mt-2" />

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </body>
    @endguest
</html>
