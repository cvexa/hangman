@extends('layouts.master')
@section('content')
    <style>
        .disabled-li{
            background-color: #8e8e8e54 !important;
        }
    </style>
    <div class="container text-center">
        <div class="game-img-holder mt-5">
            <img id="hangman-pic" src="{{asset('images/h0.png')}}" alt="initial-hangmen" style="margin-left:85px" data-error1="{{asset('images/h1.png')}}" data-error2="{{asset('images/h2.png')}}" data-error3="{{asset('images/h3.png')}}" data-error4="{{asset('images/h4.png')}}" data-error5="{{asset('images/h5.png')}}">
        </div>
        <div class="game-word mt-5row ">
            <div id="masked-word">
                {{!empty($refreshedWord)?$refreshedWord:$maskedWord}}
            </div>
            <div id="word-description">
                {{$wordDescription}}
            </div>
            <div id="full-attempt" class="mt-5">
                <input type="text" name="fullAttempt" id="fullAttempt" placeholder="опитай цялата дума">
                <br>
                <button class="btn btn-success mt-2" id="fullAttemptTrigger" data-url="{{route('checkWholeWord')}}">опитай</button>
            </div>
        </div>
        <div class="container mt-5 flex">
            <ul id="characters" class="list-group list-group-horizontal flex-row justify-content-center flex-wrap" data-url="{{route('checkIfCharExists')}}" data-redirect-url="{{route('statistic')}}">
                @foreach($alphabet as $char)

                    <li class="list-group-item {{in_array(mb_strtolower($char),session()->get('usedLetters'))?'disabled disabled-li':''}}" style="cursor: pointer">{{$char}}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <script src="{{asset('js/game.js')}}"></script>
@stop
