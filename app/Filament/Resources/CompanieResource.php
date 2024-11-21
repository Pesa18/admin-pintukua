<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Companie;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProfileCompany;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Stevebauman\Location\Facades\Location;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\CompanieResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanieResource\RelationManagers;
use App\Filament\Resources\CompanieResource\Pages\EditCompanie;
use App\Filament\Resources\CompanieResource\Pages\ListCompanies;
use App\Filament\Resources\CompanieResource\Pages\CreateCompanie;

class CompanieResource extends Resource
{
    protected static ?string $model = ProfileCompany::class;
    protected static bool $isScopedToTenant = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getDefaultLocation()
    {
        $location = Location::get(request()->ip());

        return [
            'latitude' => $location->latitude ?? '0',
            'longitude' => $location->longitude ?? '0',
        ];
    }

    public static function form(Form $form): Form
    {
        $location = self::getDefaultLocation();
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
                    ])->unique(column: 'id_kua', ignoreRecord: true),
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
                            return collect(self::getProvinsi())->pluck('name', 'id');
                        }
                    )->label('Provinsi')->live()->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Provinsi Harus diisi!',
                    ])->lazy(),
                    Select::make('id_kabupaten')->options(
                        function (Get $get) {
                            $provinsiId = $get('id_provinsi');
                            if ($provinsiId) {
                                // Mengambil data kota dari API berdasarkan provinsi yang dipilih
                                return collect(self::getKabupaten($get('id_provinsi')))->pluck('name', 'id');
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
                                return collect(self::getKecamatan($get('id_kabupaten')))->pluck('name', 'id');
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
                        if ($record) {
                            $set('location', ['lat' => json_decode($record->coordinates)->latitude, 'lng' => json_decode($record->coordinates)->longitude]);
                        } else {
                            $set('location', ['lat' => self::getDefaultLocation()['latitude'], 'lng' => self::getDefaultLocation()['longitude']]);
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

        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompanie::route('/create'),
            'edit' => Pages\EditCompanie::route('/{record}/edit'),
        ];
    }

    public static function provinsi($id): string
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
    public static function kabupaten($id): string
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
    public  static  function kecamatan($id): string
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
    public static  function getProvinsi()
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
    public static function getKabupaten($id)
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
    public static function getKecamatan($id)
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
}
