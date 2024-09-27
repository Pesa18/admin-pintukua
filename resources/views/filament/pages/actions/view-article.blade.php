
<div class="container text-center mx-auto">
    <img src="{{ Storage::url( $record->image)  }}" alt="" class="w-20 h-20 mx-auto">
</div>

<div class="font-bold text-2xl ">
    {{ $record->title }}
</div>

<div class="mx-auto container dark:bg-dark" style="background-color: black">
    {!! $record->content !!}
</div>
