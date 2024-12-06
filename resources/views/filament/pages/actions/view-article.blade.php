
<div class="container text-center mx-auto">
    <img src="{{ asset( $record->image)  }}" alt="" class="w-full">
</div>

<div class="font-bold text-2xl ">
    {{ $record->title }}
</div>

<div class="mx-auto container dark:bg-dark" style="background-color: black">
    {!! $record->content !!}
</div>
