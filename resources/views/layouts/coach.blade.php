<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    <link rel="shortcut icon" href="{{ asset('storage/images/fitlife_icon.png') }}" type="image/x-icon">
    @vite('resources/css/app.css')
</head>
<body>

    @if (session('message'))
        <div class='message fixed bottom-4 right-4 bg-[#50C878] text-white p-4 rounded-md flex flex-row gap-x-4 items-center shadow-md z-50'>
            <i class="fa-solid fa-circle-info text-2xl"></i>
            <p class='text-lg'>{{ session('message') }}</p>
        </div>
    @endif
    

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content and Navbar -->
    <div class='flex flex-col h-full'>
        @include('partials.coach_navbar')
        <div class="container mx-auto">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <script>
        // Check if the success message exists
        const message = document.querySelector('.message');
        if (message) {
            // Set a timeout to hide the message after 5 seconds
            setTimeout(() => {
                message.classList.add('hidden');
            }, 5000);
        }
    </script>

</body>
</html>