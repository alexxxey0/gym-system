<style>
    .navbar a:not(:last-child) {
        border-right: 2px solid black;
    }

    .navbar a {
        padding-inline: 1rem;
    }
</style>

<nav class='navbar flex flex-row gap-y-4 flex-wrap font-bold text-xl my-8 w-full'>
    <a href="{{ route('register_client') }}" class='hover:text-[#007BFF]'>Reģistrēt jaunu klientu</a>
    <a href="{{ route('register_coach') }}" class='hover:text-[#007BFF]'>Reģistrēt jaunu treneri</a>
    <a href="{{ route('clients_list') }}" class='hover:text-[#007BFF]'>Visi klienti</a>
    <a href="{{ route('coaches_list') }}" class='hover:text-[#007BFF]'>Visi treneri</a>
    <a href="{{ route('our_gyms') }}" class='hover:text-[#007BFF]'>Visas sporta zāles</a>
    <a href="{{ route('create_new_gym_page') }}" class='hover:text-[#007BFF]'>Izveidot jaunu sporta zāli</a>
    <a href="{{ route('our_group_trainings') }}" class="hover:text-[#007BFF]">Visas grupu nodarbības</a>
    <a href="{{ route('create_new_group_training_page') }}" class="hover:text-[#007BFF]">Izveidot jaunu grupu nodarbības veidu</a>
    <a href="{{ route('our_memberships') }}" class="hover:text-[#007BFF]">Abonementu veidi</a>
    <a href="{{ route('create_new_membership_page') }}" class="hover:text-[#007BFF]">Izveidot jaunu abonementa veidu</a>
    <a href="{{ route('group_trainings_calendar') }}" class="hover:text-[#007BFF]">Grupu nodarbību kalendārs</a>
    <a href="{{ route('gym_statistics') }}" class="hover:text-[#007BFF]">Sporta zāļu statistika</a>
</nav>