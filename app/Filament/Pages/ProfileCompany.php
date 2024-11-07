<?php

namespace App\Filament\Pages;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Stevebauman\Location\Facades\Location;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\ProfileCompany as ModelsProfileCompany;

class ProfileCompany extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.profile-company';
    public ?array $location = null;
    public ?string $latitude = null;
    public ?string $longitude = null;
    public ?string $id_provinsi = null;
    public ?string $id_kabupaten = null;
    public ?string $id_kecamatan = null;
    public ?string $email = null;
    public ?string $contact = null;

    public static function canAccess(): bool
    {

        return auth()->user()->isUserkua();
    }

    protected function getViewData(): array
    {
        $user_id = auth()->user()->id;
        $query = ModelsProfileCompany::all();
        $data = $query->where('user_id', $user_id)->toArray();

        return [
            'data' => $data
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('latitude')
                ->hiddenLabel(),
            TextInput::make('longitude')
                ->hiddenLabel(),
            TextInput::make('email')->rules('required|email')->markAsRequired(true)->validationMessages([
                'required' => 'Email Harus diisi!',
                'email' => 'Harus Berupa Email'
            ]),
            TextInput::make('contact')->numeric(),
            TextInput::make('facebook'),
            TextInput::make('instagram'),
            TextInput::make('tiktok'),
            TextInput::make('youtube'),
            Map::make('location')
                ->label('Location')
                ->columnSpanFull()
                ->defaultLocation(latitude: Location::get(request()->ip)->latitude, longitude: Location::get(request()->ip)->longitude)
                ->afterStateUpdated(function (Set $set, ?array $state): void {
                    $set('latitude',  $state['lat']);
                    $set('longitude', $state['lng']);
                })
                ->afterStateHydrated(function ($state, $record, Set $set): void {
                    $set('location', ['lat' => $record?->latitude, 'lng' => $record?->longitude]);
                })
                ->extraStyles([
                    'min-height: 50vh',
                    'border-radius: 10px'
                ])
                ->liveLocation(true, true, 5000)
                ->showMarker()
                ->markerColor("#22c55eff")
                ->showFullscreenControl()
                ->showZoomControl()
                ->draggable()
                ->tilesUrl("https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}")
                ->zoom(15)
                ->setColor('#3388ff')
                ->setFilledColor('#cad9ec'),
            Select::make('id_provinsi')->options(
                function () {
                    return collect($this->getProvinsi())->pluck('name', 'id');
                }
            )->live()->afterStateUpdated(fn(Set $set) => $set('id_kabupaten', null))->required(),
            Select::make('id_kabupaten')->options(
                function (Get $get) {
                    $provinsiId = $get('id_provinsi');
                    if ($provinsiId) {
                        // Mengambil data kota dari API berdasarkan provinsi yang dipilih
                        return collect($this->getKabupaten($get('id_provinsi')))->pluck('name', 'id');
                    }

                    return [];
                }
            )->live(),
            Select::make('id_kecamatan')->options(
                function (Get $get) {
                    $kabupatenId = $get('id_kabupaten');
                    if ($kabupatenId) {
                        // Mengambil data kota dari API berdasarkan provinsi yang dipilih
                        return collect($this->getKecamatan($get('id_kabupaten')))->pluck('name', 'id');
                    }

                    return [];
                }
            )
        ]);
    }

    public function openNewUserModal()
    {
        // Beberapa logika atau proses
        return $this->dispatch('open-modal', id: 'create-profile');
    }

    public function getProvinsi()
    {
        try {
            $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

            return $response->json();
        } catch (\Throwable $th) {
            return [];
        }
    }
    public function getKabupaten($id)
    {
        $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/regencies/' . $id . '.json');


        return $response->json();
    }
    public function getKecamatan($id)
    {
        $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/districts/' . $id . '.json');

        return $response->json();
    }


    public function create()
    {
        dd($this->form->getState());
    }
}
