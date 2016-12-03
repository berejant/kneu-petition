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

<div class="petition-resolution petition-resolution_{{$petition->getResolution()}}">
    <i class="petition-resolution__icon"></i>
    @lang('petition.resolution.' . $petition->getResolution())
</div>

@if(!$petition->is_closed)
    @if (Auth::check() && $petition->hasUserVoted(Auth::id()))
        <button class="vote-button" data-petition-id="{{ $petition->id }}" disabled>
            @lang('petition.alreadyVoted')
        </button>

    @else
        <button class="vote-button require-authentication" data-petition-id="{{ $petition->id }}">
            @lang('petition.vote')
        </button>

    @endif
@endif