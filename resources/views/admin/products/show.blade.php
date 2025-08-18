@extends('admin.layouts.admin')
@section('title')
     show tags
 @endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold"> تگ :{{$tag->name}}</h5>
            </div>
                <hr>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">نام </label>
                            <input type="text" class="form-control" value="{{$tag->name}}" disabled>
                        </div>

                        <div class="form-group col-md-3">
                            <label>تاریخ ایجاد </label>
                            <input type="text" class="form-control" value="{{ verta($tag->created_at)->format('Y-n-j H:i')}}" disabled>
                        </div>
                    </div>

                    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-dark mt-5">بازگشت</a>

        </div>
     </div>
@endsection
