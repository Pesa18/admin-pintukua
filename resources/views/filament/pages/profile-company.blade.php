<x-filament-panels::page>
@if ($data)
    <div class="">
{{ $this->profileInfolist }} 
    </div>
    <x-filament::button wire:click="openEditUserModal">
        Edit Profile
    </x-filament::button>
    @else
    <div>Silahkan Mengisi Profil KUA</div>
    <x-filament::button wire:click="openNewUserModal">
    Buat Profile
</x-filament::button>
@endif

<x-filament::modal id="edit-profile" width="6xl">
    <x-slot name="heading">
        Edit Profil
    </x-slot>
    <x-filament-panels::form wire:submit="edit">
    {{ $this->form }}

    <x-filament::button
   type="submit"
>
    Simpan
</x-filament::button>
</x-filament-panels::form>
   
</x-filament::modal>
<x-filament::modal id="create-profile" width="6xl">
    <x-slot name="heading">
        Buat Profil
    </x-slot>
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
