
<a href="{{ $attributes->get('href', '#') }}" {{ $attributes->merge(['class' => 'flex justify-center items-center bg-[#FF6767] w-[35px] h-[35px] rounded-md hover:bg-[#e65c5c]']) }}>
    {{ $slot }}
</a>