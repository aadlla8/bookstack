@extends('layouts.simple')

@section('body')
<style>
    label {display: inline-block;}
    </style>
    <main class="container small card content-wrap">
        <div class="start-exam-course">
            <div class="subject-header"><strong>Thông tin đề bài thi</strong></div>
            <div class="subject-body container">
                {{ $exam->course->subject }} <br>
                 {{ $exam->title }}  
            </div>           
        </div>
        <hr>
        <div class="panel panel-primary"> 
            <div class="panel-body start-exam-div">
                <form id="myForm" action="{{ url('check-result/'.$exam->id) }}" method="post">
                    @csrf
                    @foreach($exam->questions as $key => $question)
                        <div class="start-exam-form">
                            <div>
                                <p class="question-head" name="title"><strong>{{$key+1}}.</strong> {{ $question->title }} </p>
                            </div>
                            <div class="form-group options-group">
                                @foreach($question->options as $option)
                                    <div class="form-check">
                                        <input type="radio" class="form-check" name="{{ $question->id }}"
                                               value="{{ $option->id }}" id="{{ $option->id }}">
                                        <label class="form-check-label" for="{{ $option->id }}" style="display:inline">
                                            {{ $option->value }}
                                        </label>
                                    </div>                                    
                                @endforeach
                            </div>                            
                        </div>
                        <hr>
                    @endforeach
                    <div class="submit-questions">
                        <input type="submit" id="btn-add" class="btn btn-danger" value="Nộp bài thi">
                    </div>
                </form>
            </div>
        </div>
    </main>
 
@endsection
@section('scripts')
    <script nonce="{{ $cspNonce }}">
        $(document).ready(function () {
            $('#myForm').on('submit',function(){

                let totalQ=({{$exam->questions->count()}});
                let totalChecked=0;
                let arr = $('.form-check[type=radio]');
                for(let i=0;i<arr.length;i++) {
                    if(arr[i].checked) totalChecked++;
                }
                
                if(totalChecked < totalQ) {                        
                    return confirm(`Bạn chưa hoàn thành bài thi (${totalChecked}/${totalQ}), nhưng vẫn muốn nộp bài bấm Ok.\nTiếp tục làm bài bấm Cancel?`);                        
                }                    
            });
            var duration = "{{ $exam->duration }}";
            duration = duration.split(":");
            var hours = duration[0];
            var mins = duration[1];
            var sec = duration[2];

            var countDown = setInterval(function() {
                if(sec - 1 < 0) {
                    sec = 59;
                    if(mins - 1 < 0 ) {
                        mins = 59;
                        if(hours - 1 < 0) {
                            clearInterval(countDown);
                            $(window).unbind('beforeunload');
                            $("#myForm").submit();
                            return;
                        } else {
                            hours--;
                        }
                    } else {
                        mins--;
                    }
                } else {
                    sec--;
                }

                $('.exam-title strong').text(hours + ":" + mins + ":" + sec);
            }, 1000);

                
        });
    </script>   
@endsection