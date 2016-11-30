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
                    <div class="petition-votes">
                         <div class="petition-votes__title">Голосів</div>
                         <div class="petition-votes__count">
                             <span class="petition-votes__count-current">{{ $petition->votes }}</span>
                             / <span class="petition-votes__count-max">{{ config('petition.votes_count_for_success') }}</span>
                             (<span class="petition-votes__progress-value">{{ $petition->getProgress() }}%</span>)
                         </div>
                        <div class="petition-votes__progress">
                            <div class="petition-votes__progress-bar" style="width: {{ $petition->getProgress() }}%"></div>
                        </div>
                    </div>

                    <div class="petition-list-item__resolution petition-list-item__resolution_{{$petition->getResolution()}}">
                        <i class="petition-list-item__resolution-icon"></i>
                        @lang('petition.resolution.' . $petition->getResolution())
                    </div>

                    @if(!$petition->is_closed)
                        @if ($petition->hasUserVoted(Auth::id()))
                            <button class="vote-button" data-petition-id="{{ $petition->id }}" disabled>
                                @lang('petition.alreadyVoted')
                            </button>

                        @else
                            <button class="vote-button" data-petition-id="{{ $petition->id }}">
                                @lang('petition.vote')
                            </button>

                        @endif
                    @endif

                </div>
            </div>
        @endforeach
    </div>

    {{ $petitions->links() }}

@stop