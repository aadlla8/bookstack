@extends('layouts.simple')

@section('body')

    {{--Course Information=====================================================================================--}}

    <main class="container small card content-wrap">
    <h3 style="margin-top: 20px; margin-bottom: 50px;" class="text-center">{{$course->subject." Course"}}</h3>
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
                        @icon('edit') Edit Course
                        </a>
                    </li>
                    <li>
                        <a 
                            component="delete-button"
                            option:delete-button:message="Do you want to delete?"
                            option:delete-button:url="/deleteCourse/{{$course->id}}"
                            >
                            @icon('delete')   Delete Course
                        </a>
                    </li>
                    @if(empty($course->exam))
                    <li><a href="/add-exam"> {{Session::put("courseId", $course->id)}}
                            {{Session::put("subject", $course->subject)}}
                            @icon('add') </i>  Add Exam
                        </a>
                    </li>
                    @else 
                    <li><a  component="delete-button" option:delete-button:message="Do you want to edit?"
                        option:delete-button:url="/edit-exam/{{$course->id}}"
                       >
                        @icon('edit')  Edit Exam
                        </a>
                    </li>
                    <li><a 
                        component="delete-button"
                        option:delete-button:message="Do you want to delete?"
                        option:delete-button:url="/deleteExam/{{$course->id}}"
                       >
                        @icon('delete')  Delete Exam
                        </a>
                    </li>
                    @endif

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
       
        {{--Delete Course=======================================================================================--}}

        @if(!empty(user()) && Session::get('type')=='lecturer' && user()->id==$course->lec_id)
            <span class="pull-right confirm"><a href="/deleteCourse/{{$course->id}}"> Delete This Course </a></span>
            <br>
        @endif
        
        @if(!empty(user())&& user()->id==$course->lec_id)
            @if(!$course->students->isEmpty())
                <strong>Users have enrolled this course.</strong><br>
                    <table>
                        <tr><th>Name</th><th>Start time</th><th>End time</th><th>% Passed</th><th></th></tr>
                        @foreach($course->students as $student)
                           <tr>
                           <td>{{$student->name}}</td>
                           
                           <td>
                               @if(!empty($student->pivot->created_at))
                               
                                   {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->created_at)->toDateTimeString()}}
                               @endif
                        </td>
                           <td>
                               @if(!empty($student->pivot->updated_at))
                                    {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->updated_at)->toDateTimeString()}}
                                @endif
                        </td>
                        <td>                           
                            {{ $student->pivot->commulativeGrade }}%
                           </td>
                           <td><a href="/reset-result/{{ $student->id}}">reset</a></td>
                        </tr>
                        @endforeach
                    </table>
                
                @endif
        @endif
    </main>
@endsection