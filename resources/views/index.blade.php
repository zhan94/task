@extends('layouts.master')
@section('content')

<div class="container">
    @foreach($articleList as $article)
    <div class="row">
        <div class="col-md-12">
            <h2><a href='{{ $article->link }}'>{{ $article->title }}</a></h2>
            <p> {{ $article->excerpt }}</p>
            @if($article->author !== '0')
            <p><b>Von: <i>{{ $article->author }}</i></b></p>
            @endif
            <p><b>Datum: {{ $article->date }} Urh</b></p>
            <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#show_{{ $article->id }}">Volltext anzeigen</button>
            <div id="show_{{ $article->id }}" class="collapse wave">{{ $article->fullText }}</div>
        </div>

    </div> <hr>
    @endforeach


</div>
@endsection