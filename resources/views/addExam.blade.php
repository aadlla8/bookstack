@extends('layouts.simple')


@section('body')
<main class="container small card content-wrap">
        <div class="add-exam-course">
            <div class="subject-header"> Bài thi trắc nghiệm </div>
            <div class="subject-body container">
                {{ Session::get('subject') }}
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">Thêm bài bài</div>
            <div class="panel-body add-exam-div">
                <form id="add-quest-form" action="{{ url('add-questions') }}" method="get">
                    <div class="add-exam-form">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Title" name="title" required/>
                        </div>
                        <div class="form-group">
                            <!-- <input type="text" class="form-control" placeholder="Duration" name="duration"
                                   onfocus="(this.type='time')" onblur="(this.type='text')"  min="9:00" max="18:00" required/> -->
                                   <label for='h'>Thời gian thi</label>
                                   <input id='h' name='h' type='number' min='0' max='24' required>
                                    <label for='h'>Số giờ</label>
                                    <input id='m' name='m' type='number' min='0' max='59' required>
                                    <label for='m'>Số phút</label>
                        </div>
                        <div class="submit-group">
                            <input type="submit" id="btn-add" class="btn btn-primary" value="Tiếp">
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </main>
@endsection
