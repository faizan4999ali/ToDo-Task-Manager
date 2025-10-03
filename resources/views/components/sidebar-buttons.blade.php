@props(['href', 'icon', 'text', 'active' => false])

@php
    // The active state should mimic the hover state:
    // 1. Link <a> tag background: becomes bg-white
    $activeBgClass = $active ? 'bg-white' : '';

    // 2. Icon <i> and Text <h3> color: becomes text-[#FF6767] (overriding the default text-white)
    $activeTextColor = $active ? 'text-[#FF6767]' : 'text-white';

    // The base classes for the <a> tag, excluding the text-white which is handled below.
    $linkBaseClasses = 'group flex flex-row justify-start items-center gap-4 p-2 rounded-lg hover:bg-white';
@endphp

<!-- Sidebar buttons component -->
<a 
    href="{{$href ?? '#'}}" 
    {{ $attributes->merge(['class' => "{$linkBaseClasses} {$activeBgClass}"]) }}
>
    {{-- Icon: Conditionally set the text color based on $active --}}
    <i class="{{$icon}} {{ $activeTextColor }} group-hover:text-[#FF6767] w-[25px] h-[25px] flex items-center justify-center"></i>
    
    {{-- Text: Conditionally set the text color based on $active --}}
    <h3 class="{{ $activeTextColor }} text-sm group-hover:text-[#FF6767]" >{{$text}}</h3> 
</a>





<!-- Sidebar buttons component -->
<!-- <a href="{{$attributes->get('href', '#') }}" {{ $attributes->merge(['class' => 'group flex flex-row justify-start items-center gap-4 p-2 rounded-lg hover:bg-white']) }}> -->
    <!-- <i class="{{$icon}} text-white group-hover:text-[#FF6767] w-[25px] h-[25px] flex items-center justify-center"></i> -->
    <!-- <h3 class="text-white text-sm group-hover:text-[#FF6767]" >{{$text}}</h3>  -->
<!-- </a> -->