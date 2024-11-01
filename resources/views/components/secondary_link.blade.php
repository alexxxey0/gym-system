<a href="{{ $href }}" {{ $attributes->merge(['class' => 'text-black text-lg hover:underline']) }}>
    {{ $slot }}
</a>