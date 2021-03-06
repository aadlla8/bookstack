@extends('layouts.simple')

@section('body')
    <main class="container small card content-wrap">
        <h1 class="text-center">Thêm mới bài thi trắc nghiệm</h1>
        <form action="/Courses/create" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group" style="margin-top: 10px">
                <label for="Subject"> Tiêu đề bài thi trắc nghiệm </label>
                <input type="text" name="Subject" required id="Subject" class="form-control" />
            </div>
            @foreach ($errors->get('Subject') as $error)
                <label style="color: red">
                    {{ $error }}
                </label>
            @endforeach
            <br>
            <div class="form-group">
                <label for="level"> Level </label>
                <select name="level" id="level" required class="form-control">
                    <option value="..." readonly>...</option>
                    <option value="Beginner">
                        Beginner
                    </option>
                    <option value="Intermediate">
                        Intermediate
                    </option>
                    <option value="Advanced">
                        Advanced
                    </option>
                    <option value="Master">
                        Master
                    </option>
                    <option value="PhD">
                        PhD
                    </option>
                </select>
            </div>
            <br>
            @foreach ($errors->get('level') as $error)
                <label style="color: red">
                    {{ $error }}
                </label>
            @endforeach
            <div class="form-group"><label for="level"> Thời gian bắt đầu </label>
                <input type="datetime-local" name="start_date" min="2020-01-01T00:00">
            </div>
            <div class="form-group"><label for="level"> Thời gian kết thúc </label>
                <input type="datetime-local" name="end_date" min="2020-01-01T00:00">
            </div>
            <div class="form-group">
                <input type="hidden" name="cost" id="cost" value="0" required class="form-control"
                    placeholder="In Dollars" />
            </div>
            @foreach ($errors->get('cost') as $error)
                <label style="color: red">
                    {{ $error }}
                </label>
            @endforeach
            <br>
            <div class="form-group">
                <input type="hidden" value="2" name="NumberOfHours" required id="NumberOfHours" class="form-control" />
            </div>
            @foreach ($errors->get('NumberOfHours') as $error)
                <label style="color: red">
                    {{ $error }}
                </label>
            @endforeach
            <br>
            <input type="submit" value="Thêm bài thi" class="btn btn-primary">
        </form>
    </main>
@endsection
