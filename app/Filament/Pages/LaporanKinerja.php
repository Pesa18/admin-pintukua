<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\LaporanKinerja as ModelsLaporanKinerja;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKinerja extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.laporan-kinerja';
    public ?array $dataForm = [
        'deskripsi_pekerjaan' => null
    ];
    public ?string $deskripsi_pekerjaan = null;

    public static function canAccess(): bool
    {

        return auth()->user()->is_pegawai()->first()?->status === 'PPPK';
    }

    public function getHeader(): ?View
    {
        return View('filament.header.header-laporan-kinerja');
    }

    protected function getViewData(): array
    {
        $data['url'] = static::getUrl();


        return [
            'data' => $data
        ];
    }

    public function cetakPdf(Request $request)
    {

        $bulan = Carbon::parse($request->input('tanggal'))->format('m');
        $tahun = Carbon::parse($request->input('tanggal'))->format('Y');
        $query = ModelsLaporanKinerja::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('user_id', auth()->user()->id)->orderBy('tanggal', 'ASC')->get();
        $data = [
            'query' => $query,
            'data_pegawai' => auth()->user()->is_pegawai()?->first(),
            'data_kua' => auth()->user()->kua()->first(),
            'kepala' => Employee::where('is_kepala', true)->where('id_kua', auth()->user()->kua()->first()?->id_kua)->first(),
            'titimangsa' => $request->input('tanggal')
        ];
        $pdf = Pdf::loadView('pdf.laporan_kinerja', $data);

        return $pdf->stream('example.pdf');
        // return response()->streamDownload(
        //     fn() => print($pdf->stream()),
        //     'laporan.pdf',
        //     ['Content-Type' => 'application/pdf']
        // );
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('id')->id('id-kinerja'),
            Hidden::make('tanggal')->id('tanggal-kinerja'),
            TextInput::make('kegiatan')->required()->id('id-kegiatan')->label('Judul Kegiatan')->rules('required')->markAsRequired(true)->validationMessages([
                'required' => 'Judul Kegiatan Harus diisi!',
            ]),
            Textarea::make('deskripsi_pekerjaan')->required()->id('id-deskripsi')->label('Pekerjaan')->rules('required')->markAsRequired(true)->validationMessages([
                'required' => 'Pekerjaan Harus diisi!',
            ])
        ])->statePath('dataForm');
    }

    public function save()
    {

        $data = $this->form->getState();
        $data['user_id'] = auth()->user()->id;
        $data['id_kua'] = auth()->user()->kua()->first()?->id_kua;
        ModelsLaporanKinerja::create($data);
        $this->form->fill();

        return redirect(static::getUrl() . '?date=' . $data['tanggal']);
    }
    public function update()
    {


        $data = $this->form->getState();
        ModelsLaporanKinerja::where('id', $data['id'])->update($data);
        $this->form->fill();

        return redirect(static::getUrl() . '?date=' . $data['tanggal']);
    }
    public function delete()
    {
        $data = $this->form->getState();
        ModelsLaporanKinerja::where('id', $data['id'])->delete();
        $this->form->fill();

        return redirect(static::getUrl() . '?date=' . $data['tanggal']);
    }



    public function getall(Request $request)
    {

        $start_date = Carbon::parse($request->input('start'))->format('Y-m-d');
        $end_date = Carbon::parse($request->input('end'))->format('Y-m-d');

        $data = ModelsLaporanKinerja::where('user_id', auth()->user()->id)->whereBetween('tanggal', [$start_date, $end_date])->get()->toArray();


        foreach ($data  as $row) {
            $events[] =
                [
                    'id' => $row['id'],
                    'title' => $row['kegiatan'],
                    'start' => $row['tanggal'],
                    'allDay' => true,
                    'color' => 'blue',
                    'description' => $row['deskripsi_pekerjaan']
                ];
        };
        return response()->json($events ?? $data);
    }
}
