
<x-filament-panels::page>



<script type='importmap'>
  {
    "imports": {
      "@fullcalendar/core": "https://cdn.skypack.dev/@fullcalendar/core@6.1.15",
      "@fullcalendar/core/locales/id": "https://cdn.skypack.dev/@fullcalendar/core@6.1.15/locales/id",
      "@fullcalendar/daygrid": "https://cdn.skypack.dev/@fullcalendar/daygrid@6.1.15",
      "@fullcalendar/interaction": "https://cdn.skypack.dev/@fullcalendar/interaction@6.1.15"
    }
  }
</script>
<script type='module'>
  import { Calendar } from '@fullcalendar/core'
  import dayGridPlugin from '@fullcalendar/daygrid'
  import interactionPlugin from '@fullcalendar/interaction'
  import idLocale from '@fullcalendar/core/locales/id'

  document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar')
    const calendar = new Calendar(calendarEl, {
      plugins: [dayGridPlugin,interactionPlugin],
      initialDate: '{{ request()->get("date")?? \Carbon\Carbon::now('UTC')->toDateString() }}',
      showNonCurrentDates:false,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth'
      },
      selectable:true,
       locales: [ idLocale ],
  locale: 'id',
  events: [{
        title: "Laporan",
        start: new Date(),
      }, ],
  eventClick: function (info) {
    let form = document.getElementById('form-edit')
      form.reset()
   console.log(info)
    let inputId = document.querySelectorAll('#id-kinerja')[1]
    let inputTanggal = document.querySelectorAll('#tanggal-kinerja')[1]
    let inputKegiatan = document.querySelectorAll('#id-kegiatan')[1]
    let inputDeskripsi = document.querySelectorAll('#id-deskripsi')[1]
   
    inputKegiatan.value =info.event.title
    inputDeskripsi.value =info.event.extendedProps.description
    inputTanggal.value =info.event.startStr
    inputId.value =info.event.id
    
    Livewire.dispatch('open-modal', { id:'edit-kegiatan' })
  },
  datesSet: function (info) {
                // Ambil informasi bulan yang ditampilkan
                const startDate = info.start; // Tanggal awal (dari view)
                const endDate = info.end; // Tanggal akhir (dari view)

                console.log(info);
                console.log('End Date:', endDate);
                const button = document.getElementById('cetak-pdf');
                button.setAttribute('wire:click', `cetakPdf('${new Date(startDate).toISOString().split('T')[0]}')`);

                // Kirim data ke server atau gunakan sesuai kebutuhan
            },
  dateClick: function (info) {

    console.log(info)
    Livewire.dispatch('open-modal', { id:'buat-kegiatan' })
    
      let form = document.getElementById('form-buat')
      form.reset()

      let tanggal = document.getElementById('tanggal-kinerja')
      let id = document.getElementById('id-kegiatan')
      tanggal.value = info.dateStr
      },
      eventSources: [
        {
          url: '{{ route("laporan.kinerja") }}',
          method: "POST",
          extraParams: {
            _token: "{{ csrf_token() }}" // Sertakan CSRF token untuk keamanan
        },
          color: "yellow",
          format: "json",
        },
        // {
        //   googleCalendarId: "id.indonesian#holiday@group.v.calendar.google.com",
        //   className: "bg-danger",
        // },
        // {
        //   url: "/dashboard/getCalendarAll",
        //   method: "POST",
        // },
      ],
    });
    calendar.render()

    

    function cetakPdf($tgl){
      fetch('{{ route('api.data') }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); // Mengonversi response ke JSON
                })
                .then(data => {
                    // Menampilkan data di halaman
                    dataContainer.innerHTML = `
                        <h3>Message: ${data.message}</h3>
                        <ul>
                            ${data.data.map(item => `<li>${item}</li>`).join('')}
                        </ul>
                    `;
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
    }
  })

 
</script>

<div id="calendar"></div>


<x-filament::modal id="buat-kegiatan">
  <x-slot name="heading">
    Buat Laporan
</x-slot>

<x-filament-panels::form wire:submit="save" id="form-buat">
  {{ $this->form }}

  <x-filament::button
 type="submit"
>
  Simpan
</x-filament::button>
</x-filament-panels::form>
</x-filament::modal>

<x-filament::modal id="edit-kegiatan">
  <x-slot name="heading">
    Detail Laporan
</x-slot>

<x-filament-panels::form wire:submit="update" id="form-edit">
  {{ $this->form }}

  <x-filament::button
 type="submit"
>
  Edit
</x-filament::button>
</x-filament-panels::form>
</x-filament::modal>

</x-filament-panels::page>

