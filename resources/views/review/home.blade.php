@extends('layouts.simple')
@section('head')
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />   
    <link href="/css/select.dataTables.min.css" rel="stylesheet" type="text/css" />   
@endsection
@section('body')
    <div class="container-fluid">       
        <main class="card content-wrap">
        <h1 class="list-heading">Câu hỏi ôn tập</h1>                 
            
        <div class="row ">
            <fieldset>
                <legend></legend>
                <form action="{{route('reviewUpload')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="file" name="file">
                    <input type="submit" value="Upload câu hỏi" class="btn btn-primary">
                </form>     
            </fieldset>                          
        </div>  
        <br>      
        <div class="row">
            <table id="example" class="table table-striped responsive" style="width:100%">
                <thead>
                    <tr class="nowrap">
                        <th>id</th>   
                        <th>stt</th>                      
                        <th>Chủ đề</th>
                        <th>Tiêu đề</th>
                        <th>Câu hỏi</th>
                        <th>Đáp án</th>
                        <th>opt1</th>
                        <th>opt2</th>
                        <th>opt3</th>
                        <th>opt4</th>                             
                    </tr>
                </thead>                     
            </table>         
        </div>            
        </main>
    </div>
    <div class="modal" tabindex="-1" id='exampleModalToggle'>
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Thêm mới câu hỏi</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="panel-body add-exam-div">
                    <form name='questionForm' id="questionForm" action="" method="post">
                        @csrf
                        <div class="add-exam-form">
                            <div class="form-group">
                                <input type="hidden" id='id' name='id'>
                                <input type="text" id='topic' class="form-control " placeholder="Chủ đề" name="topic" style="width:100%;"  /></div>
                                <div class="form-group">
                                    <input type="text" id='title' name='title' class="form-control " placeholder="Tiêu đề" name="" style="width:100%;"  /></div>
                                <div class="form-group">
                                    <textarea class="form-control" id='question' name="question" placeholder="Câu hỏi" style="width: 100%"></textarea>
                                </div>
                           
                                 <div class="form-group">
                                    <input type="text" id='a' class="form-control " placeholder="A." name="A" style="width:100%;height:60px"  /></div>
                            
                                <div class="form-group">
                                    <input type="text" id='b' class="form-control " placeholder="B." name="B" style="width:100%;height:60px"  /></div>
                            
                                <div class="form-group"> 
                                    <input type="text" id='c' class="form-control " placeholder="C." name="C"  style="width:100%;height:60px" /></div>
                            
                                <div class="form-group">
                                    <input type="text" id='d' class="form-control " placeholder="D." name="D" style="width:100%;height:60px" /></div>  
    
                            <div class="form-group">
                                <select class="form-control" id="correctAns" name="correctAns" required>
                                    <option value="-1">- Select The Correct Answer -</option>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                </select>
                               
                            </div> 
                             
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id='btn-save'>Save changes</button>
            </div>
          </div>
        </div>
      </div>
@endsection
@section('scripts')
<script src="{{ url('/libs/bootstrap.bundle.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/jquery.dataTables.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/dataTables.bootstrap5.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/dataTables.responsive.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/responsive.bootstrap5.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/dataTables.buttons.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/jszip.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/pdfmake.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/vfs_fonts.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/buttons.html5.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/buttons.print.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script src="{{ url('/libs/dataTables.select.min.js')}}" nonce="{{ $cspNonce }}"></script>
<script nonce="{{ $cspNonce }}">
    $(document).ready(function () {
       var myModal = new bootstrap.Modal(document.getElementById('exampleModalToggle'), {
                            keyboard: true,
                            backdrop: false,
                        });
      $('#btn-save').on('click',function(){
          let data = {
              id: (questionForm.id.value),
              topic: (questionForm.topic.value),
              title: (questionForm.title.value),
              question: (questionForm.question.value),
              correct_ans: (questionForm.correctAns.value),
              option1: (questionForm.A.value),
              option2: (questionForm.B.value),
              option3: (questionForm.C.value),
              option4: (questionForm.D.value),              
          };
          console.log(JSON.stringify(data));
          let url = '/api/questions';
          if(data.id)
            url = '/api/questions/' + data.id;
        fetch(url, { 
            method: data.id?'PUT':'POST', 
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(data),                                  
        })
        .then(res=> res.json())
        .then(result=> {
            console.log(result); 
            location.href='/review';
        });
      })
       var table = $('#example').DataTable( {
            select: {
                style: 'multi'
            },
            dom: 'Bfrtilp',
            buttons: [
                {
                    text: 'Chọn tất',
                    action: function () {
                        table.rows().select();
                    }
                },
                {
                    text: 'Hủy chọn',
                    action: function () {
                        table.rows().deselect();
                    }
                },
                'excel',  {
                    text: 'Thêm',
                    action: function () {
                       
                        myModal.show();
                    }
                },               
                {
                    text: 'Sửa',
                    action: function () {
                        var count = table.rows( { selected: true } ).count();    
                        if(count==1) {
                            let elm = table.rows( { selected: true } ).data().toArray()[0];
                            questionForm.id.value = elm.id;
                            questionForm.title.value= elm.title;
                            questionForm.topic.value= elm.topic;
                            questionForm.question.value= elm.question;
                            questionForm.correctAns.value= elm.correct_ans;
                            questionForm.A.value= elm.option1;
                            questionForm.B.value= elm.option2;
                            questionForm.C.value= elm.option3;
                            questionForm.D.value= elm.option4;
 
                            myModal.show();
                        }
                        else if(count>0){
                            alert('Chỉ chọn 1 câu hỏi để sửa')
                        }
                        else {
                            alert('Bạn chưa chọn câu hỏi nào')
                        }
                    }
                },
                {
                    text: 'Xóa',
                    action: function () {
                        var count = table.rows( { selected: true } ).count();
                        let promisses = []
                        if(count>0 && confirm('Bạn muốn xóa '+ count + ' câu hỏi?')) {

                          let requests =  table.rows( { selected: true } ).data().toArray().map(element =>                                 
                               fetch('/api/questions/'+element.id, { 
                                    method: 'DELETE',                                    
                                })
                            );
                            Promise.all(requests)
                            .then(responses => {
                                // all responses are resolved successfully
                                location.href='/review'
                            }) 
                        }
                        else {
                            alert('Bạn chưa chọn câu hỏi nào')
                        }
                    }
                }
            ],
            "ajax": '/api/questions',
            "processing": true,
            "columns": [
                { "data": "id" },
                { "data": "stt" },
                { "data": "topic" },
                { "data": "title" },
                { "data": "question" },
                { "data": "correct_ans" },
                { "data": "option1" },
                { "data": "option2" },
                { "data": "option3" },
                { "data": "option4" },                
            ],
             "columnDefs": [
                    {
                        "targets": [ 0,1 ],
                        "visible": false,
                        "searchable": false
                    },
                     
                ],
            "deferRender": true,
            
        } );
    });

</script>
@endsection
