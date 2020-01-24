@extends('layout')

@section('content')
    <br> These are users : {{ $text }}
    <br>
    <br>
    <br>
    @foreach($users as $user)
        <p>{{ $user->name }}</p>
    @endforeach
@stop