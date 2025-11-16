{{-- resources/views/components/sidebar-link.blade.php --}}
@props(['href', 'active' => false, 'icon'])

@php
    $baseClasses = 'nav-item flex items-center text-gray-600 dark:text-gray-400 px-6 py-4 text-decoration-none transition duration-300 gap-3';
    $activeClasses = 'active bg-indigo-600 text-white border-l-4 border-indigo-400';
    $inactiveClasses = 'hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white';
@endphp

<a 
    href="{{ $href }}" 
    {{ $attributes->merge(['class' => $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses)]) }}
>
    <!-- Icon -->
    @switch($icon)
        @case('home')
            <i class="fas fa-home w-5 h-5 text-center {{ $active ? 'text-white' : 'text-gray-500' }}"></i>
            @break
        @case('chart-bar')
            <i class="fas fa-chart-bar w-5 h-5 text-center {{ $active ? 'text-white' : 'text-gray-500' }}"></i>
            @break
        @case('credit-card')
            <i class="fas fa-credit-card w-5 h-5 text-center {{ $active ? 'text-white' : 'text-gray-500' }}"></i>
            @break
        @case('key')
            <i class="fas fa-key w-5 h-5 text-center {{ $active ? 'text-white' : 'text-gray-500' }}"></i>
            @break
        @case('wrench')
            <i class="fas fa-wrench w-5 h-5 text-center {{ $active ? 'text-white' : 'text-gray-500' }}"></i>
            @break
        @case('bullhorn')
            <i class="fas fa-bullhorn w-5 h-5 text-center {{ $active ? 'text-white' : 'text-gray-500' }}"></i>
            @break
        @case('academic-cap')
            <i class="fas fa-user-graduate w-5 h-5 text-center {{ $active ? 'text-white' : 'text-gray-500' }}"></i>
            @break
        @default
            <i class="fas fa-home w-5 h-5 text-center {{ $active ? 'text-white' : 'text-gray-500' }}"></i>
    @endswitch
    
    <!-- Text -->
    <span class="font-medium">{{ $slot }}</span>
</a>