@extends('admin.layouts.admin')
@section('title')
     create banners
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ایجاد بنر</h5>
            </div>
                <hr>
                @include('admin.sections.errors')
                <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-row">

                        <div class="form-group col-md-3">
                            <label for="image">انتخاب تصویر</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="image">
                                <label class="custom-file-label" for="image">انتخاب فایل</label>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="title">عنوان</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="text">متن</label>
                            <input type="text" class="form-control" id="text" name="text" value="{{ old('text') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="priority">اولویت</label>
                            <input type="number" class="form-control" id="priority" name="priority" value="{{ old('priority', 1) }}" min="1">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="is_active">وضعیت</label>
                            <select class="form-control" id="is_active" name="is_active">
                                <option value="1" selected>فعال</option>
                                <option value="0">غیرفعال</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="type">نوع بنر</label>
                            <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="button_text">متن دکمه</label>
                            <input type="text" class="form-control" id="button_text" name="button_text" value="{{ old('button_text') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="button_link">لینک دکمه</label>
                            <input type="text" class="form-control" id="button_link" name="button_link" value="{{ old('button_link') }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="button_icon">آیکون دکمه</label>
                            <input type="text" class="form-control" id="button_icon" name="button_icon" value="{{ old('button_icon') }}">
                        </div>

                    </div> <button type="submit" class="btn btn-outline-primary mt-5">ثبت</button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-dark mt-5">بازگشت</a>
                </form>

        </div>
     </div>
@endsection
