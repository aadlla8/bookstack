@extends('layouts.simple')

@section('body')

    {{--Course Information=====================================================================================--}}

    <main class="container small card content-wrap">
    <h3 style="margin-top: 20px; margin-bottom: 50px;" class="text-center">{{$course->subject}}</h3>
        <div class="row">            
            <div class="col-md-8 course-info">                 
                <p>{{$course->level}} Level</p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added by</span>: <a href="/user/{{$course->lecturer->name}}">{{$course->lecturer->name}}</a>
                    </li>
                    <li>
                        <i class="fa fa-calendar-alt fa-fw"></i>
                        <span>Added Date</span>:
                        {{--{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->format("F j, Y, g:i a")}} --}}
                        {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->format("F j, Y")}}
                    </li>                    
                </ul>
            </div>
        </div>      

        {{--Course Control==========================================================================--}}

        @if(!empty(user()) && user()->id==$course->lec_id)           

            {{--sitting --}}
            <div class="btn-group my-info pull-right">
                        <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-cog"></i>
                            <span class="caret"></span>
                        </span>
                <ul class="">
                    <li>
                        <a href="/Courses/update/{{$course->id}}">
                        @icon('edit') {{ trans('entities.course_edit') }}
                        </a>
                    </li>
                    <li>
                        <a 
                            component="delete-button"
                            option:delete-button:message="Do you want to delete?"
                            option:delete-button:url="/deleteCourse/{{$course->id}}"
                            >
                            @icon('delete')   {{ trans('entities.course_delete') }}
                        </a>
                    </li>
                    @if(empty($course->exam))
                    <li><a href="/add-exam"> {{Session::put("courseId", $course->id)}}
                            {{Session::put("subject", $course->subject)}}
                            @icon('add') </i>  {{ trans('entities.exam_add') }}
                        </a>
                    </li>
                    @else 
                    <li><a  component="delete-button" option:delete-button:message="Do you want to edit?"
                        option:delete-button:url="/edit-exam/{{$course->id}}"
                       >
                        @icon('edit')  {{ trans('entities.exam_edit') }}
                        </a>
                    </li>
                    <li><a 
                        component="delete-button"
                        option:delete-button:message="Do you want to delete?"
                        option:delete-button:url="/deleteExam/{{$course->id}}"
                       >
                        @icon('delete')  {{ trans('entities.exam_delete') }}
                        </a>
                    </li>
                    @endif
                    <li>
                        <a 
                        component="delete-button"
                        option:delete-button:type='export'
                       >
                        @icon('export')  export
                        </a>
                    </li>
                </ul>
            </div>
        @endif
 

        {{--Check Enrollment==================================================================================--}}
        
        @if( !empty(user()) && !$enrolled && user()->id!=$course->lec_id && user()->id !=2)
            <!-- <a class="btn btn-success btn-lg enroll" href="">Watch Videos</a> -->
            @if($course->cost == 0)
            <a class="btn btn-success btn-lg enroll" href="/enrollCourse/{{$course->id}}">
                Enroll Now
                <span>For Free</span>
            </a>
            @else
            <a class="btn btn-success btn-lg enroll" href="/stripe/{{$course->id}}">
                Enroll Now
                <span>${{$course->cost}}</span>
            </a>
            @endif
            <br>
        @endif
        
        {{--Start Exam===========================================================================================--}}
        @if( !empty(user()) && $enrolled && !$examFinished && !empty($course->exam))
            <a href="/startExam/{{$course->id}}" class="start-exam">Start {{$course->subject}} Exam!</a>
            <br>
        @elseif($enrolled && $examFinished)
            <span>You have finished this exam.</span>
            <br>
        @endif      

        @if(!empty(user())&& user()->id==$course->lec_id)
            @if(!$course->students->isEmpty())
                <strong>Danh sách người dùng.</strong>               
                <br>
                    <table id='student_results'>
                        <tr><th>Tên người dùng</th><th>Bắt đầu lúc</th><th>Kết thúc lúc</th><th>Thời gian(phút)</th><th>% Passed</th><th>Hủy kết quả</th></tr>
                        @foreach($course->students as $student)
                           <tr>
                           <td>{{$student->name}}</td>
                           
                           <td>
                               @if(!empty($student->pivot->created_at))
                               
                                   {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->created_at)->format('d-m-y H:i:s')}}
                               @endif
                        </td>
                           <td>
                               @if(!empty($student->pivot->updated_at))
                                    {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->updated_at)->format('d-m-y H:i:s')}}
                                @endif
                        </td>
                        <td>
                            {{
                                number_format(Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->created_at)
                                ->diffInSeconds(Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->updated_at))/60,1)
                            }}                             
                        </td>
                        <td>                           
                            {{ number_format($student->pivot->commulativeGrade) }}%
                           </td>
                           <td><a component="delete-button"
                                option:delete-button:message="Do you want to reset score of this student?"
                                option:delete-button:url="/reset-result/{{$course->id}}"
                            >reset</a> 
                            </td>
                        </tr>
                        @endforeach
                    </table>
                
                @endif
        @endif
        
    </main>
@endsection