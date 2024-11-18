<nav class='flex flex-col p-4 gap-y-4 w-1/5 font-bold text-xl border-r-2 border-black'>
    <a href="{{ route('register_client') }}" class='hover:text-[#007BFF]'>Reģistrēt jaunu klientu</a>
    <a href="{{ route('register_coach') }}" class='hover:text-[#007BFF]'>Reģistrēt jaunu treneri</a>
    <a href="{{ route('clients_list') }}" class='hover:text-[#007BFF]'>Visi klienti</a>
    <a href="{{ route('coaches_list') }}" class='hover:text-[#007BFF]'>Visi treneri</a>
    <a href="{{ route('our_group_trainings') }}" class="hover:text-[#007BFF]">Visas grupu nodarbības</a>
    <a href="{{ route('create_new_group_training_page') }}" class="hover:text-[#007BFF]">Izveidot jaunu grupu nodarbības veidu</a>
    <a href="{{ route('our_memberships') }}" class="hover:text-[#007BFF]">Abonementu veidi</a>
    <a href="{{ route('group_trainings_calendar') }}" class="hover:text-[#007BFF]">Grupu nodarbību kalendārs</a>
</nav>