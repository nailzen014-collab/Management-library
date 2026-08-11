@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-violet-500 text-start text-base font-medium text-violet-400 bg-violet-600/10 focus:outline-none focus:text-violet-300 focus:bg-violet-600/20 focus:border-violet-400 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-stone-400 hover:text-white hover:bg-stone-800 hover:border-stone-600 focus:outline-none focus:text-white focus:bg-stone-800 focus:border-stone-600 transition duration-150 ease-in-out'
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>