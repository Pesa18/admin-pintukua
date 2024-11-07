<x-filament-panels::page>
@if ($data)
    <div class="">
Data
    </div>
@endif

<div>Silahkan Mengisi Profil KUA</div>
<x-filament::button wire:click="openNewUserModal">
    Buat Profile
</x-filament::button>
<x-filament::modal id="create-profile" width="5xl">
    {{-- Modal content --}}
    <x-filament-panels::form wire:submit="create">
    {{ $this->form }}

    <x-filament::button
   type="submit"
>
    Buat
</x-filament::button>
</x-filament-panels::form>
</x-filament::modal>
</x-filament-panels::page>
