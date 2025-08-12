@extends('admin.layouts.admin')
@section('title')
     cerate brands
 @endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ایجاد برند</h5>
            </div>
                <hr>
                @include('admin.sections.errors')
                <form action="{{ route('admin.brands.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="name">نام برند</label>
                            <input type="text" class="form-control" id="name" name="name" >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="is_active">وضعیت</label>
                            <select class="form-control" id="is_active" name="is_active" >
                                <option value="1" selected>فعال</option>
                                <option value="0">غیرفعال</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary mt-5">ثبت</button>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-dark mt-5">بازگشت</a>
                </form>
        </div>
     </div>
@endsection
