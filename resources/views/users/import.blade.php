@extends('layouts.simple')

@section('body')

    <div class="container small">

        <div class="py-m">
            @include('settings.parts.navbar', ['selected' => 'users'])
        </div>

        <main class="card content-wrap">
            <h1 class="list-heading">{{ trans('settings.users_add_new') }}</h1>

            <form action="{{ url('/settings/users/import') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}

                <div class="setting-list">
                    <input type="file" id='userfileimport' name="userfile">
                </div>

                <div class="form-group text-right">
                    <a href="{{ url(userCan('users-manage') ? '/settings/users' : '/') }}"
                        class="button outline">{{ trans('common.cancel') }}</a>
                    <button class="button" type="submit">{{ trans('common.save') }}</button>
                </div>

            </form>

        </main>
    </div>

@stop