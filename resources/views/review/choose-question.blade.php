@extends('layouts.simple')
@section('head')
<link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('body')
<div class="container small">       
    <main class="card content-wrap">
        <h1>Ôn tập</h1>
        <div class="col-6">
            <form action="/review/choose-question/" id='chooseQuestionForm' method="POST"> 
                {{ csrf_field() }}               
                <div class="mb-3">
                    <label class="form-label">Chủ đề</label>
                    <select class="form-control" name='topic' id='topic'>
                        <option>Chọn chủ đề</option>
                        @foreach ($topics as $topic)
                            <option value="{{$topic->topic}}">{{$topic->topic}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số lượng câu hỏi</label>
                    <input type="number" name="noQuestion" class="form-control" style="width:100%">
                </div>
                <div class="mb-3">
                    <label id='totalQuestions'>Tổng số câu hỏi trong chủ đề: </label>
                </div>
                <button type="submit" class="btn btn-primary">Bắt đầu</button>
            </form>
        </div>
    </main>
</div>
@endsection

@section('scripts')
    
@endsection