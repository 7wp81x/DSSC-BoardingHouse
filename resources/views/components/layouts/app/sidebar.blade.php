{{-- resources/views/components/layouts/app/sidebar.blade.php --}}
<div>
    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('student.dashboard') }}" 
       class="me-5 flex items-center space-x-2 rtl:space-x-reverse" 
       wire:navigate>
        <x-app-logo />
    </a>

    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Platform')" class="grid">
            <flux:navlist.item 
                icon="home" 
                :href="auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('student.dashboard')" 
                :current="request()->routeIs('admin.dashboard') || request()->routeIs('student.dashboard')" 
                wire:navigate>
                {{ __('Dashboard') }}
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer />
</div>