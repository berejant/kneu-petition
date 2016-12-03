@extends('layouts.main')

@section('title', 'Перелік петиції')

@section('content')

    <h1>Всі петиції</h1>

    <div class="petition-list">
        @foreach($petitions as $petition)
            <div class="petition-list-item">
                <div class="petition-list-item__header">
                    <a class="petition-list-item__id" href="{{ route('petitions.show', ['petition' => $petition]) }}">
                        Петиція #{{ $petition->id }}
                    </a>

                    <div class="petition-list-item__title">
                        Тема: {{ $petition->title }}
                    </div>
                </div>


                <div class="petition-list-item__description">
                    <div class="petition-list-item__content">
                        {!! nl2br(e($petition->content)) !!}
                    </div>

                    <div class="petition-list-item__description-bottom">
                        {{ $petition->user->first_name }} {{ $petition->user->last_name }}
                        {{ $petition->updated_at->diffForHumans() }}
                    </div>
                </div>


                <div class="petition-list-item__progress">
                    @include('petition.progress')
                </div>
            </div>
        @endforeach
    </div>

    {{ $petitions->links() }}

@stop