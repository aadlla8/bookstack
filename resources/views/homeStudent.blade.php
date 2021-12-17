@extends('layouts.simple')

@section('body')
    <div class="container small">
        <main class="card content-wrap">
            <div class="flex-container-row wrap justify-space-between items-center">
                <h1 class="list-heading">{{ trans('entities.courses') }}</h1>
                <div>
                    <div class="block inline mr-xs">
                        <form method="get" action="{{ url('/homeStudent') }}">
                            @foreach (collect($listDetails)->except('search') as $name => $val)
                                <input type="hidden" name="{{ $name }}" value="{{ $val }}">
                            @endforeach
                            <input type="text" name="search" placeholder="search" @if ($listDetails['search']) value="{{ $listDetails['search'] }}" @endif>
                            @if (userCan('settings-manage'))
                                <a href="/Courses/create/" class="outline button mt-none">Thêm bài trắc nghiệm</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="row ">
                @foreach ($courses as $course)
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <strong><a href="/courses/{{ $course->id }}"> {{ $course->subject }}</a></strong><br>
                                Thêm bởi: {{ $course->lecturer->name }}
                                , thêm lúc: {{-- {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->format("F j, Y, g:i a")}} --}}
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->diffForHumans() }}
                                , cập nhật lúc
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->diffForHumans() }}
                                <br>
                                @if ($course->start_date)
                                    ngày bắt đầu
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->start_date) }}
                                @endif
                                @if ($course->end_date)
                                    ngày kết thúc
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->end_date) }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <div>
                {{ $courses->appends(['search' => $listDetails['search']])->links() }}
            </div>
        </main>
    </div>
@endsection
