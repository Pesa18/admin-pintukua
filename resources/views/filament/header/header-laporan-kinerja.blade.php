<div class="text-xl font-bold">
    Laporan Kinerja
</div>
<form action="{{ route('cetak.laporan') }}" method="post" onsubmit="target_popup(this)" class="flex flex-row justify-end">
    @csrf
    <input type="text" id="data-tanggal" hidden name="tanggal">
    <x-filament::button id="cetak-pdf" type="submit" icon="heroicon-m-printer">
    Cetak Laporan
</x-filament::button>
</form>


<script>
    function target_popup(form) {
        window.open('', 'formpopup', 'width=800,height=400,resizeable,scrollbars');
        form.target = 'formpopup';
    }
</script>