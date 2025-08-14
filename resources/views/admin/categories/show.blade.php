@extends('admin.layouts.admin')
@section('title')
     show categories
 @endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold"> دسته بندی :{{$category->name}}</h5>
            </div>
                <hr>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name">نام </label>
                            <input type="text" class="form-control" value="{{$category->name}}" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="slug">نام انگلیسی</label>
                            <input type="text" class="form-control" value="{{$category->slug}}" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>والد</label>
                            <input type="text" class="form-control" value="{{$category->parent ? $category->parent->name : 'ندارد'}}" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت</label>
                            <input type="text" class="form-control" value="{{$category->is_active}}" disabled>
                        </div>
                         <div class="form-group col-md-3">
                            <label for="icon">آیکون </label>
                            <input type="text" class="form-control" value="{{$category->icon}}" disabled>
                        </div>

                        <div class="form-group col-md-3">
                            <label>تاریخ ایجاد </label>
                            <input type="text" class="form-control" value="{{ verta($category->created_at)->format('Y-n-j H:i')}}" disabled>
                        </div>
                         <div class="form-group col-md-12">
                            <label for="description">توضیحات </label>
                            <textarea class="form-control" disabled>{{$category->description}}</textarea>
                        </div>

                        <div class="col-md-12">
                           <hr>
                           <div class="row">
                              <div class="col-md-3">
                                    <label>ویژگی ها</label>
                                    <div class="form-control div-disabled">
                                        @foreach ($category->attributes as $attribute)
                                            {{ $attribute->name }}{{ $loop->last ? '' : '،' }}
                                        @endforeach
                                    </div>
                                </div>

                                    <div class="col-md-3">
                                        <label>ویژگی های قابل فیلتر</label>
                                        <div class="form-control div-disabled">
                                            @foreach ($category->attributes()->wherePivot('is_filter' , 1)->get() as $attribute)
                                                {{ $attribute->name }}{{ $loop->last ? '' : '،' }}
                                            @endforeach
                                        </div>
                                    </div>

                                <div class="col-md-3">
                                        <label>ویژگی متغیر</label>
                                    <div class="form-control div-disabled">
                                        @foreach ($category->attributes()->wherePivot('is_variation' , 1)->get() as $attribute)
                                            {{ $attribute->name }}{{ $loop->last ? '' : '،' }}
                                        @endforeach
                                    </div>
                                </div>


                           </div>
                        </div>

                    </div>


                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark mt-5">بازگشت</a>

        </div>
     </div>
@endsection
