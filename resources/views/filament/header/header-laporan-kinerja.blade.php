<div class="text-xl font-bold">
    Laporan Kinerja
</div>
<form action="{{ route('cetak.laporan') }}" method="post" onsubmit="target_popup(this)" class="flex flex-col">
    @csrf
    <input type="text" id="data-tanggal"  name="tanggal">
    <x-filament::button id="cetak-pdf" type="submit">
    Cetak
</x-filament::button>
</form>


<script>
    function target_popup(form) {
        window.open('', 'formpopup', 'width=800,height=400,resizeable,scrollbars');
        form.target = 'formpopup';
    }
</script>