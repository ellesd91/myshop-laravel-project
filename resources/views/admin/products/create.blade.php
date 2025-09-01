@extends('admin.layouts.admin')

@section('title', 'ایجاد محصول')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ایجاد محصول</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- ... بخش‌های بالایی فرم (نام، برند، وضعیت، تگ‌ها، توضیحات) ... --}}
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="brand_id">برند</label>
                        <select id="brand_id" name="brand_id" class="form-control selectpicker" data-live-search="true" title="انتخاب برند">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" selected>فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="tag_ids">تگ‌ها</label>
                        <select id="tag_ids" name="tag_ids[]" class="form-control selectpicker" multiple data-live-search="true" title="انتخاب تگ‌ها" >
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">توضیحات</label>
                    <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-12"><p>تصاویر محصول را انتخاب کنید:</p></div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="primary_image">تصویر اصلی</label>
                        <div class="custom-file">
                            <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                            <label class="custom-file-label" for="primary_image" > انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="images"> انتخاب تصاویر</label>
                        <div class="custom-file">
                            <input type="file" name="images[]" multiple class="custom-file-input" id="images" >
                            <label class="custom-file-label" for="images"> انتخاب فایل‌ها </label>
                        </div>
                    </div>
                </div>
                {{-- ... بخش‌های پایینی فرم ... --}}
                <hr>
                <h5 class="my-4">دسته بندی و ویژگی ها</h5>

                <div class="col-md-12">
                    <div class="row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="category_id">دسته بندی</label>
                            <select id="categorySelect" name="category_id" class="form-control selectpicker" data-live-search="true" title="انتخاب دسته بندی">
                            <option value="">انتخاب دسته بندی</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} @if($category->parent) - {{ $category->parent->name }} @endif</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                </div>

                {{-- ما فقط به یک نگهدارنده نیاز داریم --}}
                <div id="attributes_section" class="form-row">
                    {{-- ویژگی‌ها در اینجا با جاوااسکریپت ساخته می‌شوند --}}
                </div>

                <hr>
               <div class="col-md-12" id="attributesContainer">
                    <div id="attributes" class="row">
                            <div class="col-md-12">
                                <p>افزودن قیمت و موجودی برای متغییر <span id="variationName" class="font-weight-bold"></span>:</p>
                            </div>
                        <div id="czContainer">
                            <div id="first">
                                <div class="recordset">
                                    <div class="row">
                                        <div class="col-12 col-md-3 mb-3 form-group">
                                        <label class="form-label" for="value">نام</label>
                                        <input type="text" class="form-control" name="variation_values[value][]" value="{{ old('variation_values.value.0') }}">
                                        </div>
                                        <div class="col-12 col-md-3 mb-3 form-group">
                                        <label class="form-label" for="price">قیمت</label>
                                        <input type="text" class="form-control" name="variation_values[price][]" value="{{ old('variation_values.price.0') }}">
                                        </div>
                                        <div class="col-12 col-md-3 mb-3 form-group">
                                        <label class="form-label">تعداد</label>
                                        <input type="text" class="form-control" name="variation_values[quantity][]" value="{{ old('variation_values.quantity.0') }}">
                                        </div>
                                        <div class="col-12 col-md-3 mb-3 form-group">
                                        <label class="form-label">شناسه انبار</label>
                                        <input type="text" class="form-control" name="variation_values[sku][]" value="{{ old('variation_values.sku.0') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

               </div>
                {{-- delivery section --}}
                <div class="col-12">
                    <hr>
                    <p class="mb-2">هزینه ارسال</p>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-3">
                        <label for="delivery_amount">هزینه ارسال</label>
                        <input class="form-control" id="delivery_amount" name="delivery_amount" type="text" value="{{ old('delivery_amount') }}">
                    </div>

                    <div class="form-group col-12 col-md-3">
                        <label for="delivery_amount_per_product">هزینه ارسال به ازای محصول اضافی</label>
                          <input class="form-control" id="delivery_amount_per_product" name="delivery_amount_per_product" type="text" value="{{ old('delivery_amount_per_product') }}">

                    </div>
                </div>





                <button type="submit" class="btn btn-outline-primary mt-5">ثبت</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark mt-5">بازگشت</a>
            </form>
        </div>
    </div>
@endsection


@push('scripts')
    {{-- 2) بعد فایل‌های Vite تو --}}
    @vite(['resources/js/admin/admin.js'])
@endpush
<style>
  /* آیکن‌ها (همان قبلی‌ها) */
  #czContainer + #btnPlus, .btnPlus{
    display:inline-block;width:25px;height:25px;
    background:url('/img/add.png') center no-repeat; background-size:contain;
    border:0;cursor:pointer;text-indent:-9999px;overflow:hidden;
    margin-bottom: 8px;
  }
  .btnMinus{
    width:25px;height:25px;
    background:url('/img/remove.png') center no-repeat; background-size:contain;
    border:0;cursor:pointer;text-indent:-9999px;overflow:hidden;
    position:absolute; /* ⬅️ مهم */
    top: 6px;          /* هرجا بهتر بود تنظیم کن */
    right: -34px;      /* اگر RTL نیست left بذار */
  }

  /* خود رکورد را نسبی کن تا absolute روی همان ردیف پین شود */
  #czContainer .recordset{ position: relative; }

  /* کمک: هر ستون label+input یک ستون عمودی منظم باشد */
  #czContainer .recordset .row > [class*="col-"]{
    display:flex; flex-direction:column;
  }
</style>

