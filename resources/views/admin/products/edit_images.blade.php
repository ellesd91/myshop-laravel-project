@extends('admin.layouts.admin')
@section('title','ویرایش تصویر محصول')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <h5 class="font-weight-bold mb-4">ویرایش تصویر محصول {{ $product->name }}</h5>

            @include('admin.sections.errors')

            @php
                // مسیر پایه تصاویر از .env
                $base = trim(env('PRODUCT_IMAGES_UPLOAD_PATH','/upload/files/products/images/'),'/').'/';
            @endphp

            {{-- بخش "تصویر اصلی" --}}
            <div class="mb-4">
                <h6 class="mb-2">تصویر اصلی :</h6>
                <div class="card p-3" style="max-width: 360px;">
                    @if($product->primary_image)
                        <img src="{{ asset($base . ltrim($product->primary_image,'/')) }}"
                             alt="primary image"
                             class="img-fluid rounded">
                    @else
                        <p class="text-muted mb-0">تصویر اصلی ثبت نشده است.</p>
                    @endif
                </div>
            </div>

            {{-- گالری تصاویر --}}
            <div class="mb-4">
                <h6 class="mb-3">تصاویر :</h6>

                @if($product->images->count() === 0)
                    <p class="text-muted">هیچ تصویری وجود ندارد.</p>
                @else
                    <div class="row">
                        @foreach($product->images as $img)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                <div class="card h-100 p-2 d-flex flex-column">
                                    <img src="{{ asset($base . ltrim($img->image,'/')) }}"
                                         alt="image"
                                         class="img-fluid rounded mb-2"
                                         style="max-height:220px; object-fit:contain;">

                                    @php
                                        $prim = $product->primary_image ? basename($product->primary_image) : null;
                                        $curr = basename($img->image);
                                        $isPrimary = $prim && $prim === $curr;
                                    @endphp

                                    {{-- حذف تصویر (فقط برای گالری) --}}
                                    <form action="{{ route('admin.products.images.destroy', $product->id) }}"
                                          method="POST" class="mt-auto">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="image_id" value="{{ $img->id }}">
                                        <button class="btn btn-danger btn-sm btn-block" type="submit">
                                            حذف
                                        </button>
                                    </form>

                                    {{-- انتخاب به عنوان تصویر اصلی --}}
                                    <form action="{{ route('admin.products.images.set_primary', $product->id) }}"
                                          method="POST" class="mt-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="image_id" value="{{ $img->id }}">
                                        <button class="btn btn-primary btn-sm btn-block" @if($isPrimary) disabled @endif>
                                            انتخاب به عنوان تصویر اصلی
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- فرم آپلود پایین صفحه: تصویر اصلی (تکی) + تصاویر گالری (چندتایی) --}}
            <form action="{{ route('admin.products.images.add', $product->id) }}"
                  method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="primary_image">انتخاب تصویر اصلی</label>
                        <div class="custom-file">
                            <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                            <label class="custom-file-label" for="primary_image">انتخاب فایل</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="images">انتخاب تصاویر</label>
                        <div class="custom-file">
                            <input type="file" name="images[]" multiple class="custom-file-input" id="images">
                            <label class="custom-file-label" for="images">انتخاب فایل‌ها</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-success">ثبت ویرایش</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">بازگشت</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
  @vite(['resources/js/admin/admin.js','resources/scss/admin.scss'])
@endpush
