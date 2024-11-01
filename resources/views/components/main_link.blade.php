<a href="{{ $href }}" {{ $attributes->merge(['class' => "bg-[#007BFF] active:bg-[#0056b3] text-white p-4 rounded-md text-center cursor-pointer"]) }}>
    {{ $slot }}
</a>