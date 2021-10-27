@extends('layouts.simple')

@section('body')


    <style>

        .content{
            width: 40%;
            background-color:white;
            margin: 20px auto;
            border-radius: 20px;
            padding:10px 20px;
            box-shadow: 5px 5px 5px #ccc;

        }

    </style>
     <h1 class="text-center" style="margin-top: -30px;">Import Data</h1>

    <div class="container small card content-wrap" >

        <form action="/DataImport/create" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <div class="from-group">
                <label for="coursePic"> File excel </label>
                <input type="file" name="coursePic" id="coursePic" class="form-control" value="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
            </div>
            @foreach ($errors->get('coursePic') as $error)
    
            <label style="color: red">
                {{$error}}
            </label>
            @endforeach
             
            <br>

            <input type="submit" value="Import" class="btn btn-primary">

        </form>

    </div>
  
@endsection
