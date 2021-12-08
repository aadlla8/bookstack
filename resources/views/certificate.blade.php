@extends('layouts.simple')


@section('body')
<style>
    

    .header-1 {
        display: block;
        font-size: 2em;
        margin-block-start: 0.67em;
        margin-block-end: 0.67em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
        margin: 0px;
    }

    .header-2 {
        display: block;
        font-size: 1.5em;
        margin-block-start: 0.83em;
        margin-block-end: 0.83em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        font-weight: bold;
        margin: 0px;
    }

    .certificate-frame {
        width: 700px;
        
        border: 1px solid black;
        box-shadow: 5px 10px 8px #888888;
        margin: auto auto;
        padding: 50px;
        background-image: url("{{ asset('images/certificate-background.png') }}");
        background-size: cover;
        background-repeat: no-repeat;
    }

    .certificate-panel {
        text-align: center;
        padding-top: 50px;
    }

    .student-name {
        font-family: 'yellowtailregular';
        font-size: 32px;
        color: #ff5722;
    }

    .lecturer-name {
        font-family: 'yellowtailregular';
        font-size: 24px;
        color: #2196f3;
        margin: 0px;
        padding: 0px;
    }
</style>
<div class="certificate-frame">
    <div class="certificate-panel">
        <div>
            <p class="header-2">Yêu cầu của bạn đã thực hiện thành công <br>{{ $exam->course->subject }} với kết quả</p>
                  
            <p style="text-align: left; padding-left: 100px;"> 
                <b>% đúng:</b> {{ number_format($percent) }}%<br>
                    <b>% sai:</b> {{ 100 - number_format($percent) }}%<br>
                        <b>Số câu đúng:</b> {{ $correctCount}} / {{$total_question}} <br>
                            <b>Số điểm:</b> {{ $totalMark}} / {{ $totalGrade}}<br>
                                <b>Họ và Tên:</b> {{ $student->name }}          
            </p>
        </div>
        <div>
            <div>
                Cung cấp bởi: KMS System
            </div>             
        </div>       
    </div>
    @if($type==2 || $type==1)
        <div>
            <u>Chi tiết kết quả làm bài trả lời đúng:</u> {{$correctCount}} / {{count($checkresults)}}  
             thời gian làm bài: 
             {{ 
                Carbon\Carbon::now()->diffInMinutes(session()->get('beginAnswerQuestion'))==0?
                "dưới 1": Carbon\Carbon::now()->diffInMinutes(session()->get('beginAnswerQuestion'))
             }} phút
             <br><br>
            @foreach ($checkresults as $i => $question)            
                <strong>{{ ($i+1).".".$question->title }}</strong>  
                @foreach($question->options as $option)
                    <div class="form-check">                            
                        <input type="radio" class="form-check" name="{{ $question->id }}" disabled
                            value="{{ $option->id }}" id="{{ $option->id }}" {{ $question->userchoose == $option->id? "checked":"" }}>
                        <label class="form-check-label" for="{{ $option->id }}" style="display:inline">
                            <span {{ $option->id == $question->correct_ans?"style=color:green;font-weight:bold;":"" }}>{{ $option->value }} </span>
                        </label>                         
                    </div>                        
                @endforeach
                <br>
            @endforeach
        </div>
    @endif
</div>

@endsection