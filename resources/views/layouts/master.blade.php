<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="referrer" content="always">
        {{-- <link rel="canonical" href="{{ request()->url() }}"> --}}

        {{-- <meta name="description" content="{{ $page->description }}"> --}}

        <title>SIPEGAWAI</title>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- <script src="{{ mix('js/main.js', 'assets/build') }}"></script> --}}
        
        <!-- Tambahkan CDN untuk jQuery, Alpine.js, Bootstrap, dan Tailwind -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200 font-roboto">
            @include('layouts.sidebar')
            
            <div class="flex-1 flex flex-col overflow-hidden">
                @include('layouts.header')

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container mx-auto px-6 py-8">
                        @yield('body')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
