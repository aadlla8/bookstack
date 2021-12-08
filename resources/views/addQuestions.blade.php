@extends('layouts.simple')

<link rel="stylesheet" href="{{ asset('css/add-questions-style.css') }}">
<style>
    .form-control.option {
        margin: 3px;
    }
    </style>
@section('body')
<main class="container small card content-wrap">
        <div class="add-exam-course">
            <div class="subject-header"><strong>Tên bài thi</strong></div>
            <div class="subject-body container">
                {{ Session::get('subject') }}
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading"><strong> Thêm các câu hỏi cho bài</strong> </div>
            <div class="exam-title">
                {{ Session::get("exam_title") }} Exam
            </div>
            <div class="panel-body add-exam-div">
                <form id="add-quest-form" action="{{ url('store-question') }}" method="post">
                    @csrf
                    <div class="add-exam-form">
                        <div class="form-group">
                            <textarea class="form-control" name="title" form="add-quest-form" placeholder="Question" style="width: 100%"></textarea>
                        </div>
                        <div class="row form-group options-group">
                            <div class="col-md-10 options">
                                <input type="text" class="form-control option" placeholder="A." name="A" required/>
                                <input type="text" class="form-control option" placeholder="B." name="B" required/>
                                <br>
                                <input type="text" class="form-control option" placeholder="C." name="C" required/>
                                <input type="text" class="form-control option" placeholder="D." name="D" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <select class="form-control" id="correct-ans" name="correct-ans" required>
                                <option value="-1">- Select The Correct Answer -</option>
                                <option value="0">A</option>
                                <option value="1">B</option>
                                <option value="2">C</option>
                                <option value="3">D</option>
                            </select>
                            <input type="number" class="form-control" placeholder="Mark" name="mark" required/>
                        </div>
                        <div class="form-group">
                           
                        </div>
                        <div class="submit-group">
                            <input type="submit" id="btn-add" class="btn btn-primary" value="Thêm câu hỏi">
                            <a id="btn-submit" class="btn btn-outline-primary" href="{{ url('/') }}">
                                Hoàn thành bài thi
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>Các câu hỏi đã thêm</strong></div>
            <div class="panel-body add-exam-div">
                <div class='row'>
                @if(!empty($questions) && !$questions->isEmpty())
                @php
                $i = 1;
                @endphp
                    @foreach($questions as $key=>$question)
                        <div class="col-sm-6 col-md-4">
                            <div class="thumbnail item-box" style="border: 1px solid #03A9F4;">
                                <div class="question-header">
                                    <strong>{{$i++}}.</strong> 
                                    <span>
                                        {{ $question->title }}                                        
                                    </span>
                                </div>

                                <div class="question-body" style="padding: 10px;">                                   
                                    <div class="options-panel">
                                        @foreach($question->options as $option)
                                        <div>
                                            {{ $option->value }}
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="question-ans">
                                        <div>
                                            <strong style="color: #0030ff">Correct answer: </strong> {{ $question->correctAnswer->value }}
                                        </div>
                                        <div class="question-mark">
                                           Mark: {{ $question->mark }} 
                                        </div>
                                        <div class="question-mark">
                                            ID: {{ $question->id }} 
                                         </div>
                                    </div>
                                    <div> 
                                        <a component="delete-button"
                                            option:delete-button:message="Do you want to delete?"
                                            option:delete-button:url="/delete-question/{{ $question->id }}"
                                            >
                                            @icon('delete')   Delete question
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    No Questions Added
                @endif
                </div>
            </div>
        </div>
    </main>

@endsection
