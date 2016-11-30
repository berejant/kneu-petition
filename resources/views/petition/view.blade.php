@extends('layouts.main')

@section('title', 'Створення петиції')

@section('content')
    <h3>{{ $petition->title }}</h3>
    <div>
        {!! nl2br(e($petition->content)) !!}
    </div>

@endsection