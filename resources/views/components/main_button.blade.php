<button {{ $attributes->merge(['class' => 'bg-[#007BFF] active:bg-[#0056b3] text-white py-2 rounded-md']) }}>
    {{ $slot }}
</button>