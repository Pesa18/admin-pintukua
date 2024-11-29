<?php

namespace App\Filament\Pages;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Filament\Infolists\Infolist;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Components\TextInput;
use Dotswan\MapPicker\Infolists\MapEntry;
use Filament\Forms\Components\FileUpload;
use Stevebauman\Location\Facades\Location;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\ProfileCompany as ModelsProfileCompany;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Components\Section as ComponentsSection;

class ProfileCompany extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.profile-company';
    public  $location = null;
    public ?string $id_provinsi = null;
    public ?string $id_kabupaten = null;
    public ?string $id_kecamatan = null;
    public ?array $dataForm = [];


    public function getDefaultLocation()
    {
        $location = Location::get(request()->ip());

        return [
            'latitude' => $location->latitude ?? '0',
            'longitude' => $location->longitude ?? '0',
        ];
    }

    public static function canAccess(): bool
    {

        return auth()->user()->isUserkua();
    }

    public function mount(): void
    {


        $data = auth()->user()->kua()->first();

        if ($data) {
            $this->form->fill($data->toArray());
        }
    }

    protected function getViewData(): array
    {
        $data = auth()->user()->kua()->first();

        // dd($user_id);
        return [
            'data' => $data
        ];
    }

    public function form(Form $form): Form
    {
        $location = $this->getDefaultLocation();
        return $form->schema([
            Group::make()->schema([
                Section::make('Data KUA')->schema([
                    TextInput::make('name')->label('Nama KUA')->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Nama Harus diisi!',
                    ]),
                    TextInput::make('address')->label('Alamat KUA')->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Alamat Harus diisi!',
                    ]),
                    TextInput::make('id_kua')->label('Kode KUA')->numeric()->rules('required|numeric')->markAsRequired(true)->validationMessages([
                        'required' => 'Kode KUA Harus diisi!',
                        'numeric' => 'Harus Berupa Angka!',
                        'unique' => "Kode KUA Sudah ada dalam database"
                    ])->unique(modifyRuleUsing: function (Unique $rule) {

                        return $rule->ignore(auth()->user()->kua()->first()->id_kua, 'id_kua');
                    }),
                    TextInput::make('email')->rules('required|email')->markAsRequired(true)->validationMessages([
                        'required' => 'Email Harus diisi!',
                        'email' => 'Harus Berupa Email'
                    ]),
                    TextInput::make('contact')->label('No Telp/Whatsapp')->numeric()->rules('required|numeric')->markAsRequired(true)->validationMessages([
                        'required' => 'No Telp/Whatsapp Harus diisi!',
                        'numeric' => 'Harus Berupa Angka!',
                    ]),
                ])->columns(2),
            ])->columns(2)->columnSpan(2),

            Group::make()->schema([
                Section::make('Foto KUA')->schema([
                    FileUpload::make('foto')->image()->directory('profile')->label('Foto KUA')->required()
                ])
            ]),
            Group::make()->schema([
                Section::make('Alamat')->schema([
                    Select::make('id_provinsi')->options(
                        function () {
                            return collect($this->getProvinsi())->pluck('name', 'id');
                        }
                    )->label('Provinsi')->live()->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Provinsi Harus diisi!',
                    ])->lazy(),
                    Select::make('id_kabupaten')->options(
                        function (Get $get) {
                            $provinsiId = $get('id_provinsi');
                            if ($provinsiId) {
                                // Mengambil data kota dari API berdasarkan provinsi yang dipilih
                                return collect($this->getKabupaten($get('id_provinsi')))->pluck('name', 'id');
                            }

                            return [];
                        }
                    )->live()->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Kabupaten Harus diisi!',
                    ])->label('Kabupaten')->lazy(),
                    Select::make('id_kecamatan')->options(
                        function (Get $get) {
                            $kabupatenId = $get('id_kabupaten');
                            if ($kabupatenId) {
                                // Mengambil data kota dari API berdasarkan provinsi yang dipilih
                                return collect($this->getKecamatan($get('id_kabupaten')))->pluck('name', 'id');
                            }

                            return [];
                        }
                    )->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Kecamatan Harus diisi!',
                    ])->label('Kecamatan')->lazy()
                ])
            ]),
            Group::make()->schema([
                Map::make('location')
                    ->label('Location')
                    ->columnSpanFull()
                    ->defaultLocation(
                        latitude: $location['latitude'],
                        longitude: $location['longitude']
                    )
                    ->afterStateUpdated(function (Set $set, ?array $state): void {
                        $set('latitude',  $state['lat']);
                        $set('longitude', $state['lng']);
                        $set('coordinates', json_encode(['latitude' => $state['lat'], 'longitude' => $state['lng']]));
                    })
                    ->afterStateHydrated(function ($state, $record, Set $set): void {
                        if ($this->getViewData()['data']) {
                            $set('location', ['lat' => json_decode($this->getViewData()['data']->coordinates)->latitude, 'lng' => json_decode($this->getViewData()['data']->coordinates)->longitude]);
                        } else {
                            $set('location', ['lat' => $this->getDefaultLocation()['latitude'], 'lng' => $this->getDefaultLocation()['longitude']]);
                        }
                    })
                    ->extraStyles([
                        'border-radius: 10px'
                    ])
                    ->liveLocation(true, false, 1000)
                    ->showMarker()
                    ->markerColor("#22c55eff")
                    ->showFullscreenControl()
                    ->showZoomControl()
                    ->draggable()
                    ->tilesUrl("https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}")
                    ->zoom(15)
                    ->setColor('#3388ff')
                    ->setFilledColor('#cad9ec'),
                TextInput::make('coordinates'),
                TextInput::make('latitude')->disabled(),
                TextInput::make('longitude')->disabled(),
            ])->columnSpan(2),

            Group::make()->schema([
                Section::make('Media Sosial')->schema([
                    TextInput::make('facebook')->prefixIcon('icon-facebook'),
                    TextInput::make('instagram')->prefixIcon('icon-instagram'),
                    TextInput::make('tiktok')->prefixIcon('icon-tiktok'),
                    TextInput::make('youtube')->prefixIcon('icon-youtube'),
                ])->columns(2)
            ])->columnSpanFull()

        ])->columns(3)->statePath('dataForm')->model(ModelsProfileCompany::class);
    }

    public function profileInfolist(Infolist $infolist): Infolist
    {
        if ($this->getViewData()['data']) {
            return $infolist
                ->record(auth()->user()->kua()->with('kepala')->first())
                ->state($this->getViewData()['data']->toArray())
                ->schema([
                    Grid::make()->schema([
                        ComponentsSection::make('Data KUA')->schema([
                            TextEntry::make('name'),
                            TextEntry::make('id_kua'),
                            TextEntry::make('address'),
                            TextEntry::make('kepala')->state(fn(Model $record) => $record->kepala->first()?->name)->default('Belum Diatur'),
                            TextEntry::make('id_provinsi')->formatStateUsing(fn(string $state): string => $this->provinsi($state))->label('Provinsi'),
                            TextEntry::make('id_kabupaten')->formatStateUsing(fn(string $state): string => $this->kabupaten($state))->label('Kabupaten'),
                            TextEntry::make('id_kecamatan')->formatStateUsing(fn(string $state): string => $this->kecamatan($state))->label('Kecamatan'),
                        ])->columns(2)->columnSpan(2),
                        ImageEntry::make('foto')
                    ])->columns(3),


                    ComponentsSection::make('Location')->schema([
                        MapEntry::make('location')
                            ->extraStyles([
                                'border-radius: 10px'
                            ])
                            ->state(fn($record) => $record ? ['lat' => json_decode($record?->coordinates)->latitude, 'lng' => json_decode($record?->coordinates)->longitude] : [])
                            ->showMarker()
                            ->markerColor("#22c55eff")
                            ->showFullscreenControl()
                            ->draggable(false)
                            ->zoom(15),
                        TextEntry::make('coordinates')
                    ]),


                ]);
        }
        return $infolist->schema([]);
    }


    public function openNewUserModal()
    {
        // Beberapa logika atau proses
        return $this->dispatch('open-modal', id: 'create-profile');
    }
    public function openEditUserModal()
    {
        // Beberapa logika atau proses
        return $this->dispatch('open-modal', id: 'edit-profile');
    }

    public function provinsi($id): string
    {
        return Cache::remember("province_{$id}", now()->addHours(24), function () use ($id) {
            try {
                $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/province/{$id}.json");
                $response->throw();

                return $response->json('name') ?? 'Nama provinsi tidak ditemukan';
            } catch (\Throwable $th) {
                return 'Terjadi kesalahan saat mengambil data provinsi';
            }
        });
    }
    public function kabupaten($id): string
    {
        return Cache::remember("kabupaten_{$id}", now()->addHours(24), function () use ($id) {
            try {
                $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regency/{$id}.json");
                $response->throw();

                return $response->json('name') ?? 'Nama provinsi tidak ditemukan';
            } catch (\Throwable $th) {
                return 'Terjadi kesalahan saat mengambil data provinsi';
            }
        });
    }
    public function kecamatan($id): string
    {
        return Cache::remember("kecamatan_{$id}", now()->addHours(24), function () use ($id) {
            try {
                $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/district/{$id}.json");
                $response->throw();

                return $response->json('name') ?? 'Nama provinsi tidak ditemukan';
            } catch (\Throwable $th) {
                return 'Terjadi kesalahan saat mengambil data provinsi';
            }
        });
    }
    public function getProvinsi()
    {


        return Cache::remember("province_all", now()->addHours(24), function () {
            try {
                $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json");
                $response->throw();

                return $response->json() ?? [];
            } catch (\Throwable $th) {
                return [];
            }
        });
    }
    public function getKabupaten($id)
    {
        return Cache::remember("kabupaten_all_{$id}", now()->addHours(24), function () use ($id) {
            try {
                $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/regencies/' . $id . '.json');
                $response->throw();

                return $response->json() ?? [];
            } catch (\Throwable $th) {
                return [];
            }
        });
    }
    public function getKecamatan($id)
    {
        return Cache::remember("kecamatan_all_{$id}", now()->addHours(24), function () use ($id) {
            try {
                $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/districts/' . $id . '.json');
                $response->throw();

                return $response->json() ?? [];
            } catch (\Throwable $th) {
                return [];
            }
        });
    }


    public function create()
    {

        $data = $this->form->getState();

        $data['user_id'] = auth()->user()->id;

        ModelsProfileCompany::create($data);

        return redirect(static::getUrl());
    }
    public function edit()
    {

        $data = $this->form->getState();
        unset($data['location']);
        $id = auth()->user()->kua()->first()->id;

        ModelsProfileCompany::where('id', $id)->update($data);

        return redirect(static::getUrl());
    }
}
