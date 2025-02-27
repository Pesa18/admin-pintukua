<x-filament-panels::page>

@livewire('Filament\Widgets\AccountWidget')

@if (auth()->user()->isSuperAdmin())
@livewire('App\Filament\Widgets\SuperAdminStats')
    
@endif

</x-filament-panels::page>