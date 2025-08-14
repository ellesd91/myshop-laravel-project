@extends('admin.layouts.admin')
@section('title')
     create categories
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ایجاد ویژگی</h5>
            </div>
                <hr>
                @include('admin.sections.errors')
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="name">نام </label>
                            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="slug">نام انگلیسی </label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{old('slug')}}">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="parent_id">والد</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="0">بدون والد</option>
                                @foreach($parentCategories as $parentCategory)
                                    {{-- در اینجا از متغیر تکی $parentCategory استفاده می‌کنیم --}}
                                    <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="is_active">وضعیت</label>
                            <select class="form-control" id="is_active" name="is_active" >
                                <option value="1" selected>فعال</option>
                                <option value="0">غیرفعال</option>
                            </select>
                        </div>
                          <div class="form-group col-md-3">
                            <label for="attribute_ids">ویژگی</label>
                            <select id="attributeSelect"  title="انتخاب ویژگی" name="attribute_ids[]" class="form-control selectpicker" multiple data-live-search="true">
                                @foreach ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="attribute_is_filter_ids">انتخاب ویژگی های قابل فیلتر</label>
                            <select id="attributeIsFilterSelect" title="انتخاب ویژگی" name="attribute_is_filter_ids[]" class="form-control selectpicker" multiple data-live-search="true">

                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="attribute_is_filter_ids">انتخاب ویژگی متغیر</label>
                            <select id="variationsSelect"  title="انتخاب ویژگی" name="variation_id" class="form-control selectpicker"  data-live-search="true">

                            </select>
                        </div>
                         <div class="form-group col-md-3">
                            <label for="icon">آیکون</label>
                            <input type="text" class="form-control" id="icon" name="icon" value="{{old('icon')}}" >
                        </div>

                        <div class="form-group col-md-12">
                            <label for="description">  توضیحات</label>
                            <textarea  class="form-control" id="description" name="description" >{{old('description')}}</textarea>
                        </div>


                    </div>
                    <button type="submit" class="btn btn-outline-primary mt-5">ثبت</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark mt-5">بازگشت</a>
                </form>
        </div>
     </div>
@endsection
