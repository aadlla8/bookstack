@extends('layouts.simple')


@section('body')
<main class="container small card content-wrap">
        <div class="add-exam-course">
            <div class="subject-header"> Subject </div>
            <div class="subject-body container">
                {{ Session::get('subject') }}
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">Add an Exam</div>
            <div class="panel-body add-exam-div">
                <form id="add-quest-form" action="{{ url('add-questions') }}" method="get">
                    <div class="add-exam-form">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Title" name="title" required/>
                        </div>
                        <div class="form-group">
                            <!-- <input type="text" class="form-control" placeholder="Duration" name="duration"
                                   onfocus="(this.type='time')" onblur="(this.type='text')"  min="9:00" max="18:00" required/> -->
                                   <label for='h'>Duration</label>
                                   <input id='h' name='h' type='number' min='0' max='24' required>
                                    <label for='h'>h</label>
                                    <input id='m' name='m' type='number' min='0' max='59' required>
                                    <label for='m'>m</label>
                        </div>
                        <div class="submit-group">
                            <input type="submit" id="btn-add" class="btn btn-primary" value="Add New Exam">
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </main>
@endsection
