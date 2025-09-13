@extends('admin.layouts.admin')

@section('title', 'Show Banner')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">جزئیات بنر: {{ $banner->title }}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="form-group col-md-4">
                    <label>تصویر بنر</label>
                    <div>
                        <img src="{{ asset($banner->image) }}" class="img-fluid" alt="{{ $banner->title }}">
                    </div>
                </div>

                <div class="form-group col-md-8">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <strong>عنوان:</strong> {{ $banner->title }}
                        </div>
                        <div class="form-group col-md-6">
                            <strong>متن:</strong> {{ $banner->text }}
                        </div>
                        <div class="form-group col-md-6">
                            <strong>نوع:</strong> {{ $banner->type }}
                        </div>
                        <div class="form-group col-md-6">
                            <strong>اولویت:</strong> {{ $banner->priority }}
                        </div>
                        <div class="form-group col-md-6">
                            <strong>وضعیت:</strong>
                            @if ($banner->is_active)
                                <span class="badge badge-success">فعال</span>
                            @else
                                <span class="badge badge-danger">غیرفعال</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <strong>متن دکمه:</strong> {{ $banner->button_text ?? 'ندارد' }}
                        </div>
                        <div class="form-group col-md-6">
                            <strong>لینک دکمه:</strong> {{ $banner->button_link ?? 'ندارد' }}
                        </div>
                        <div class="form-group col-md-6">
                            <strong>آیکون دکمه:</strong> {{ $banner->button_icon ?? 'ندارد' }}
                        </div>
                         <div class="form-group col-md-6">
                            <strong>تاریخ ایجاد:</strong> {{ $banner->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-dark mt-5">بازگشت</a>
        </div>
    </div>
@endsection
