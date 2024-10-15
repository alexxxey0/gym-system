<!-- resources/views/partials/header.blade.php -->
<header class="bg-[#007BFF] text-white p-4">
    <nav class="container mx-auto">
        <ul class="flex flex-row gap-x-4 text-xl">
            <li><a href="{{ route('admin_homepage') }}" class="hover:underline">Galvenā lapa</a></li>
            <li class='ml-auto flex flex-row gap-x-12'>
                @if (Auth::user()->role === 'admin')
                    <span class='text-base'>Autentificēts kā: Sporta zāles administrators</span>
                @else
                    <span class='text-base'>Autentificēts kā: {{ Auth::user()->name }}  {{Auth::user()->surname }}</span>
                @endif

                <form id="logout-form" action="{{ route('logout_' . Auth::user()->role) }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <a class='text-white' href="{{ route('logout_' . Auth::user()->role) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Iziet
                </a>
            </li>
        </ul>
    </nav>
</header>
