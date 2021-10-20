@extends('layouts.simple')

@section('body')
         
        <main class="container small card content-wrap">
        <h2>Courses </h2>
            <div class="row ">
                @foreach ($courses as $course)
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <div class="caption">
                            <strong><a href="/courses/{{$course->id}}"> {{$course->subject}}</a></strong><br>
                            Added by: {{$course->lecturer->name}} 
                            , at: {{--{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->format("F j, Y, g:i a")}} --}}
                            {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->diffForHumans()}}
                            , updated at 
                            {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->diffForHumans()}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </main>
    
@endsection

