@extends('layouts.main')

@section('title', 'Створення петиції')

@section('content')
    <form class="edit-petition" method="post" action="{{ route($petition->exists ? 'petitions.update' : 'petitions.store', [
        'petition' => $petition
    ]) }}">
        {{ csrf_field() }}
        @if($petition->exists)
            {{ method_field('PUT') }}
        @endif

        <fieldset>
            <legend>Створення нової петиції</legend>
            <div class="form-group @if($errors->has('title')) has-error @endif ">
                <label for="title" class="col-lg-2 control-label">Тема</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $petition->title) }}">
                    <span class="help-block">{{ $errors->first('title') }}</span>
                </div>
            </div>
            <div class="form-group @if($errors->has('content')) has-error @endif ">
                <label for="content" class="col-lg-2 control-label">Зміст</label>
                <div class="col-lg-10">
                    <textarea class="form-control" rows="10" name="content" id="content">{{ old('content', $petition->content) }}</textarea>
                    <span class="help-block">Опишіть докладно суть запропонованих змін.</span>
                    <span class="help-block">{{ $errors->first('content') }}</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-10 col-lg-offset-2">
                    <button type="submit" class="btn btn-success">Зберегти</button>
                </div>
            </div>
        </fieldset>
    </form>
@endsection