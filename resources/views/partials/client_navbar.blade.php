<style>
    .navbar a:not(:last-child) {
        border-right: 2px solid black;
    }

    .navbar a {
        padding-inline: 1rem;
    }
</style>

<nav class='navbar flex flex-row gap-y-4 flex-wrap font-bold text-xl my-8 w-full'>
    <a href="{{ route('user_profile_page') }}" class='hover:text-[#007BFF]'>Mans profils</a>
    <a href="{{ route('our_memberships') }}" class="hover:text-[#007BFF]">Abonementu veidi</a>
    <a href="{{ route('our_coaches') }}" class="hover:text-[#007BFF]">Mūsu treneri</a>
    <a href="{{ route('our_gyms') }}" class='hover:text-[#007BFF]'>Mūsu sporta zāles</a>
    <a href="{{ route('our_group_trainings') }}" class="hover:text-[#007BFF]">Mūsu grupu nodarbības</a>
    <a href="{{ route('my_group_trainings_client') }}" class="hover:text-[#007BFF]">Manas grupu nodarbības</a>
    <a href="{{ route('group_trainings_calendar') }}" class="hover:text-[#007BFF]">Grupu nodarbību kalendārs</a>
</nav>