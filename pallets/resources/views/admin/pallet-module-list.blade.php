<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pallet Modules List') }}
        </h2>
    </x-slot>

    <livewire:admin-pallet-module-list />
</x-app-layout>
