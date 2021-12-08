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
                        <span>Thêm bởi</span>: <a href="/user/{{$course->lecturer->name}}">{{$course->lecturer->name}}</a>
                    </li>
                    <li>
                        <i class="fa fa-calendar-alt fa-fw"></i>
                        <span>Ngày thêm</span>:
                        {{--{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->format("F j, Y, g:i a")}} --}}
                        {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->created_at)->format("F j, Y")}}
                    </li>                    
                </ul>
            </div>
        </div>      

        {{--Course Control==========================================================================--}}

        @if(!empty(user()) && userCan('settings-manage'))           

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
                            option:delete-button:message="Bạn muốn xóa?"
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
                        <li><a  component="delete-button" option:delete-button:message="Bạn muốn sửa?"
                            option:delete-button:url="/edit-exam/{{$course->id}}"
                        >
                            @icon('edit')  {{ trans('entities.exam_edit') }}
                            </a>
                        </li>
                        <li><a 
                            component="delete-button"
                            option:delete-button:message="Bạn muốn xóa?"
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
                        @icon('export')  Xuất báo cáo kết quả thi
                        </a>
                    </li>
                </ul>
            </div>
        @endif
 

        {{--Check Enrollment==================================================================================--}}
        
        @if( !empty(user()) && !$enrolled && user()->id != $course->lec_id && user()->id !=2)
            <!-- <a class="btn btn-success btn-lg enroll" href="">Watch Videos</a> -->
            @if($course->cost == 0)
                <a class="btn btn-success btn-lg enroll" href="/enrollCourse/{{$course->id}}">
                    Vào làm bài trắc nghiệm                 
                </a>
            @else
                <a class="btn btn-success btn-lg enroll" href="/stripe/{{$course->id}}">
                    Vào làm bài trắc nghiệm             
                </a>
            @endif
            <br>
        @endif
        
        {{--Start Exam===========================================================================================--}}
        @if( !empty(user()) && $enrolled && !$examFinished && !empty($course->exam))
            <a href="/startExam/{{$course->id}}" class="start-exam">Bắt đầu {{$course->subject}} Exam!</a>
            <br>
        @elseif(empty($course->exam))
            <span>Chưa có câu hỏi nào. Hãy thêm câu hỏi.</span>
            <br>
        @elseif($enrolled && $examFinished)
            <span>Bạn đã hoàn thành bài thi này rồi.</span>
            <br>
        @endif      
            <br>
        @if(!empty(user())&& userCan('settings-manage'))
            @if(!$course->students->isEmpty())
                <strong>Danh sách người dùng.</strong>               
                <br>
                    <table id='student_results'>
                        <tr style="background-color: gold">
                            <th>Stt</th>
                            <th>Tên người dùng</th>
                            <th>Tài khoản</th>
                            <th>Kỳ thi</th>
                            <th>Làm bài</th>   
                            <th>% đúng</th>
                            <th>% sai</th>
                            <th>Điểm</th>
                            <th>TG thực hiện</th>
                            <th>Câu hỏi sai</th>
                            <th>Hủy kết quả</th>
                        </tr>                         
                        @foreach($course->students->sortBy('name') as $key=>$student)
                           <tr>
                               <td> {{ $key + 1 }}</td>
                                <td>{{$student->name}}</td>
                                <td>{{$student->email}}</td>  
                                <td>{{$course->subject}}</td>                              
                                <td>
                                    @if(!empty($student->pivot->created_at))                                    
                                        {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->created_at)->format('d-m-y H:i:s')}}
                                    @endif
                                </td> 
                                <td> 
                                    @if(!@empty($student->pivot->commulativeGrade))
                                        {{ number_format($student->pivot->commulativeGrade) }}
                                    @endif
                                </td>
                                <td> 
                                    @if(!@empty($student->pivot->commulativeGrade))
                                        {{ number_format( 100-$student->pivot->commulativeGrade) }}
                                    @endif
                                </td>
                                <td>
                                    {{$student->pivot->total_mark}}
                                </td>
                                <td>
                                    @if(!empty($student->pivot->created_at) && !empty($student->pivot->updated_at))
                                        {{
                                            number_format(
                                                Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->created_at)
                                                    ->diffInSeconds(Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$student->pivot->updated_at))/60,1)
                                        }}  
                                    @endif                           
                                </td>
                                <td>{{ $student->pivot->fail_questions }}</td>
                                <td>
                                    <a component="delete-button"
                                        option:delete-button:message="Bạn muốn xóa kết quả thi của người dùng này?"
                                        option:delete-button:url="/reset-result/{{$course->id}}/{{$student->id}}"
                                    >reset</a> 
                                </td>
                            </tr>
                        @endforeach
                    </table>
                
                @endif
        @endif
        
    </main>
@endsection