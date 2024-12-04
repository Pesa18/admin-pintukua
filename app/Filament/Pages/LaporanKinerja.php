<?php

namespace App\Filament\Pages;

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

    public function cetakPdf($date)
    {
        $data = ['tanggal' => $date];
        $pdf = Pdf::loadView('pdf.laporan_kinerja', $data)->setOption('isHtml5ParserEnabled', true);;

        return $pdf->stream('example.pdf');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('id')->id('id-kinerja'),
            Hidden::make('tanggal')->id('tanggal-kinerja'),
            TextInput::make('kegiatan')->id('id-kegiatan'),
            Textarea::make('deskripsi_pekerjaan')->id('id-deskripsi')->label('deskripsi')
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
