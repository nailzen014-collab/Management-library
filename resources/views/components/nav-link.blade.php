@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-violet-500 text-sm font-medium leading-5 text-white focus:outline-none focus:border-violet-400 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-stone-400 hover:text-white hover:border-stone-500 focus:outline-none focus:text-white focus:border-stone-500 transition duration-150 ease-in-out'
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>