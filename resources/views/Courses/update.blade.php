@extends('layouts.simple')

@section('body')


    <style>
        .content {
            width: 40%;
            background-color: white;
            margin: 20px auto;
            border-radius: 20px;
            padding: 10px 20px;
            box-shadow: 5px 5px 5px #ccc;

        }

    </style>
    <h1 class="text-center" style="margin-top: -30px;">Cập nhật bài thi trắc nghiệm</h1>

    <div class="content">

        <form action="/Courses/update/{{ $course->id }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="from-group" style="margin-top: 10px">
                <label for="Subject"> Tiêu đề bài thi </label>
                <input type="text" name="Subject" required id="Subject" class="form-control"
                    value="{{ $course->subject }}" />
            </div>
            @foreach ($errors->get('Subject') as $error)
                <label style="color: red">
                    {{ $error }}
                </label>
            @endforeach
            <br>
            <div class="from-group">
                <label for="level"> Cấp độ khó </label>

                <select name="level" id="level" required class="form-control">

                    <option value="..." readonly>...</option>
                    <option value="Beginner" <?php if ($course->level == 'Beginner') {
    echo 'selected';
} ?>>
                        Beginner
                    </option>
                    <option value="Intermediate" <?php if ($course->level == 'Intermediate') {
    echo 'selected';
} ?>>
                        Intermediate
                    </option>
                    <option value="Advanced" <?php if ($course->level == 'Advanced') {
    echo 'selected';
} ?>>
                        Advanced
                    </option>
                    <option value="Master" <?php if ($course->level == 'Master') {
    echo 'selected';
} ?>>
                        Master
                    </option>
                    <option value="PhD" <?php if ($course->level == 'PhD') {
    echo 'selected';
} ?>>
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
                <input type="datetime-local" name="start_date"
                    value="{{ $course->start_date ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->start_date)->toDateTimeLocalString() : '' }}"
                    min="2020-01-01T00:00">
            </div>
            <div class="form-group"><label for="level"> Thời gian kết thúc </label>
                <input type="datetime-local" name="end_date"
                    value="{{ $course->end_date ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $course->end_date)->toDateTimeLocalString() : '' }}"
                    min="2020-01-01T00:00">
            </div>
            <div class="from-group">
                <input type="hidden" value="0" name="cost" id="cost" required class="form-control" placeholder="In Dollars"
                    value="{{ $course->cost }}" />
            </div>
            @foreach ($errors->get('cost') as $error)

                <label style="color: red">
                    {{ $error }}
                </label>
            @endforeach
            <br>

            <div class="from-group">
                <input type="hidden" value="1" name="NumberOfHours" required id="NumberOfHours" class="form-control"
                    value="{{ $course->numOfHours }}" />
            </div>
            @foreach ($errors->get('NumberOfHours') as $error)

                <label style="color: red">
                    {{ $error }}
                </label>
            @endforeach


            <div class="from-group">
                <label for="coursePic"> File excel *.xls chứa các câu hỏi </label>
                <input type="file" name="coursePic" id="coursePic" class="form-control"
                    value="{{ $course->coursePic }}"
                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
            </div>
            @foreach ($errors->get('coursePic') as $error)

                <label style="color: red">
                    {{ $error }}
                </label>
            @endforeach
            <br>

            <input type="submit" value="Lưu " class="btn btn-primary">

        </form>

    </div>
    <br><br><br>
@endsection
