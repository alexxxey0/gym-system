<style>
    header {
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
    }
</style>
<header class="bg-[#007BFF] text-white p-4">
    <nav class="container mx-auto">
        <ul class="flex flex-row items-center gap-x-4 text-xl">
            <li>
                <a class='font-bold text-4xl' href="{{ route('admin_homepage') }}">FitLife</a>
            </li>

            @auth
                <li class='ml-auto mr-4 flex flex-row items-center gap-x-12'>
                    @if (Auth::user()->role === 'admin')
                        <span class='text-base'>Autentificēts kā: Sporta zāles administrators</span>
                    @else
                        <span class='text-base'>Autentificēts kā: {{ Auth::user()->name }}  {{Auth::user()->surname }}</span>
                    @endif

                    <form id="logout-form" action="{{ route('logout_' . Auth::user()->role) }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    <a class='text-white font-bold' href="{{ route('logout_' . Auth::user()->role) }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Iziet
                    </a>
                </li>
            @endauth
        </ul>
    </nav>
</header>
