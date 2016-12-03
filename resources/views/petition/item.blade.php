@extends('layouts.main')

@section('title', 'Петиція №' . $petition->id . ' - ' . $petition->title)

@section('content')

    <div class="petition-item">
        <div class="petition-item__header">
            <h3>{{ $petition->title }}</h3>

            <div class="">

            </div>
        </div>

        <div class="petition-item__content">
            {!! nl2br(e($petition->content)) !!}
        </div>

        <div class="petition-item__progress">
            @include('petition.progress')
        </div>


        <div class="petition-item__bottom">
            <span class="petition-item__author">
                Автор:
               {{ $petition->user->getName() }}
            </span>

            <span class="petition-item__datetime">
                {{ $petition->created_at->format('d.m.Y H:i') }}
            </span>

            @can('update', $petition)
                <div class="petition-item__actions">
                    <a class="petition-item__edit" href="{{ route('petitions.edit', ['petition' => $petition]) }}">
                        <i></i> Редагувати
                    </a>

                    @can('delete', $petition)
                    <button class="petition-item__remove" data-petition-id="{{ $petition->id }}"
                            data-confirm-text="Ви дійсно бажаєте видалити цю петицію?">
                        <i></i> Видалити
                    </button>
                    @endcan

                </div>
            @endcan


        </div>
    </div>

    <div class="petition-comments-list">

        <div class="petition-comments-list__header">

            <div class="petition-comments-list__header-title">
                Коментарі
            </div>

            <div class="petition-comments-list__actions">
                @if(!$petition->is_closed)
                <div class="petition-comments-list__add-button require-authentication">
                    Додати коментар
                </div>
                @endif
            </div>
        </div>

        <form class="add-comment-form" method="post" data-petition-id="{{ $petition->id }}">
            <fieldset>
                <legend class="add-comment-form__legend">Додати коментар</legend>
                <legend class="edit-comment-form__legend">Редагувати коментар</legend>
                <div class="form-group">
                    <textarea class="form-control" rows="5" name="content" id="content" required minlength="5" placeholder="Ваш коментар"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Зберегти</button>
                </div>
            </fieldset>
        </form>

        @forelse($petition->petitionComments as $petitionComment)
        <div class="petition-comment-item">
            <div class="petition-comment-item__header">
                <span class="petition-comment-item__author">
                    {{ $petitionComment->user->getName() }}
                </span>

                <span class="petition-comment-item__datetime">
                    {{ $petitionComment->created_at->diffForHumans() }}
                </span>

                @can('update', $petitionComment)
                <span class="petition-comment-item__actions">
                    <button type="button" class="petition-comment-item__edit"
                            data-petition-id="{{ $petition->id }}" data-petition-comment-id="{{ $petitionComment->id }}"
                    >
                        <i></i> Редагувати
                    </button>

                    @can('delete', $petitionComment)
                    <button type="button" class="petition-comment-item__remove"
                            data-petition-id="{{ $petition->id }}" data-petition-comment-id="{{ $petitionComment->id }}"
                            data-confirm-text="Ви дійсно бажаєте видалити цей коментар?"
                    >
                        <i></i> Видалити
                    </button>
                    @endcan
                </span>
                @endcan
            </div>

            <div class="petition-comment-item__content">
                {!! nl2br(e($petitionComment->content)) !!}
            </div>
        </div>
        @empty
            <div class="petition-comments-list__empty">
                Коментарі відсутні
            </div>
        @endforelse

    </div>

@endsection