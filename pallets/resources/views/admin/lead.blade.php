<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lead Product Configurations List') }}
        </h2>
    </x-slot>

    <livewire:admin-lead lead="{{ $lead }}"/>
</x-app-layout>
