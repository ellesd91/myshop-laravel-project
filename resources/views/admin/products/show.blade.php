@extends('admin.layouts.admin')
@section('title', 'نمایش محصول')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">محصول : {{ $product->name }}</h5>
            </div>
            <hr>
            <div class="row">
               <div class="form-group col-md-3">
                    <label>نام</label>
                    <input class="form-control" type="text" value="{{ $product->name }}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label>نام برند</label>
                    <input class="form-control" type="text" value="{{ $product->brand->name }}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label>نام دسته بندی</label>
                    <input class="form-control" type="text" value="{{ $product->category->name }}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label>وضعیت</label>
                    <input class="form-control" type="text" value="{{ $product->is_active }}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label>تگ ها</label>
                    <input class="form-control" type="text" value="{{ implode(', ', $product->tags->pluck('name')->toArray()) }}" disabled>0
                </div>

                <div class="form-group col-md-3">
                    <label>تاریخ ایجاد</label>
                    <input class="form-control" type="text" value="{{ verta($product->created_at) }}" disabled>
                </div>
                <div class="form-group col-md-12">
                    <label>توضیحات</label>
                    <textarea class="form-control" rows="3" disabled>{{ $product->description }}</textarea>
                </div>
            </div>

            {{-- Delivery Section --}}
           <div class="col-md-12">
                <hr>
                <p>هزینه ارسال :</p>
            </div>

            <div class="row"> {{-- 👈 اینجا سطر ساختیم --}}
                <div class="form-group col-md-3">
                    <label>هزینه ارسال</label>
                    <input class="form-control" type="text"
                        value="{{ number_format((int)$product->delivery_amount) }}"
                        disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>هزینه ارسال به ازای محصول اضافی</label>
                    <input class="form-control" type="text"
                        value="{{ $product->delivery_amount_per_product !== null ? number_format((int)$product->delivery_amount_per_product) : '—' }}"
                        disabled>
                </div>
            </div>


            {{-- Attributes Section --}}
           <div class="col-md-12">
                <hr>
                <p>ویژگی‌ها :</p>
            </div>

            <div class="row"> {{-- 👈 این باعث میشه colها کنار هم قرار بگیرن --}}
                @forelse(($product->productAttributes ?? collect()) as $pa)
                    <div class="form-group col-md-3">
                        <label>{{ $pa->attribute->name ?? 'ویژگی' }}</label>
                        <input class="form-control" type="text" value="{{ $pa->value }}" disabled>
                    </div>
                @empty
                    <div class="col-md-12">
                        <small class="text-muted">ویژگی‌ای ثبت نشده است.</small>
                    </div>
                @endforelse
            </div>


            {{-- Variations Section --}}
                <div class="col-md-12">
                    <hr>
                    <p>قیمت و موجودی متغیّرها :</p>
                </div>

                @forelse(($product->variations ?? collect()) as $v)
                    {{-- ردیف عنوان + دکمه نمایش --}}
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2">
                        <div class="text-muted">
                            قیمت و موجودی برای متغیّر ( {{ $v->value }} )
                        </div>

                        <button class="btn btn-sm btn-primary"
                                type="button"
                                data-toggle="collapse"
                                data-target="#var-{{ $v->id }}"
                                aria-expanded="false"
                                aria-controls="var-{{ $v->id }}">
                            نمایش
                        </button>
                    </div>
                </div>

                    {{-- باکس بازشونده --}}
                <div class="collapse mb-3" id="var-{{ $v->id }}">
                    <div class="border rounded p-3 bg-light">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>قیمت</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ number_format((int)($v->price ?? 0)) }}" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label>تعداد</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ (int)($v->quantity ?? 0) }}" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label>شناسه انبار (SKU)</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $v->sku ?? '—' }}" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label>قیمت فروش ویژه</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $v->sale_price ? number_format((int)$v->sale_price) : '—' }}" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label>شروع فروش ویژه</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $v->date_on_sale_from ? verta($v->date_on_sale_from) : '—' }}" disabled>
                            </div>
                            <div class="form-group col-md-2">
                                <label>پایان فروش ویژه</label>
                                <input class="form-control form-control-sm" type="text"
                                    value="{{ $v->date_on_sale_to ? verta($v->date_on_sale_to) : '—' }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-md-12">
                        <small class="text-muted">هیچ متغیّری ثبت نشده است.</small>
                    </div>
                @endforelse


               {{-- Product Image Section (فقط یک بار) --}}
                <div class="col-md-12 mt-4">
                    <hr>
                    <p>تصویر محصول :</p>
                    <img src="{{ rtrim(env('PRODUCT_IMAGES_UPLOAD_PATH'), '/') . '/' . $product->primary_image }}"
                        alt="{{ $product->name }}"
                        class="img-thumbnail"
                        style="max-width: 200px; height: auto;">
                </div>

                @if($product->images->count())
                    <div class="col-md-12 mt-3">
                        <p>گالری تصاویر:</p>
                    </div>
                    <div class="col-md-12 d-flex flex-wrap">
                        @foreach($product->images as $pi)
                            <img src="{{ rtrim(env('PRODUCT_IMAGES_UPLOAD_PATH'), '/') . '/' . $pi->image }}"
                                alt="gallery"
                                class="img-thumbnail mr-2 mb-2"
                                style="max-width: 180px; height: auto;">
                        @endforeach
                    </div>
                @endif


                {{-- دکمه بازگشت --}}
                <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-5">بازگشت</a>

        </div>
    </div>



@endsection
