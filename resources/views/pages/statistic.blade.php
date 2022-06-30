@extends('layouts.master')
@section('content')
    <div class="container mt-5">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">потребител</th>
                <th scope="col">дума</th>
                <th scope="col">опити</th>
                <th scope="col">резултат</th>
                <th scope="col">пълен опит</th>
            </tr>
            </thead>
            <tbody>
            @forelse($userStats as $stat)
                <tr>
                    <th scope="row">{{$stat->id}}</th>
                    <th>{{$stat->user->name}}</th>
                    <td>{{$stat->word->word}}</td>
                    <td>{{$stat->attempts}}</td>
                    <td>{{$stat->victory?'отгатната':'не отгатната'}}</td>
                    <td>{{$stat->full_attempt?'да':'не'}}</td>
                </tr>
            @empty
                <tr>
                    <th scope="row">-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                    <th>-</th>
                </tr>
            @endforelse
            </tbody>
        </table>
        {{ $userStats->links('pagination::bootstrap-4') }}
    </div>
@stop
