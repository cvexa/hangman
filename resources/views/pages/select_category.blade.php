@extends('layouts.master')
@section('content')
    <div class="container text-center">
        <div class="mt-5 row">
            @foreach($categories as $category)
                <div class="col-4">
                    <a href="{{route('start_game',$category->id)}}">
                        <img src="{{ asset('images/'.$category->image) }}" alt="cities" width="150px" height="150px">
                    </a>
                    <div class="text-center">{{ $category->category }}</div>
                </div>
            @endforeach
        </div>
    </div>
@stop
