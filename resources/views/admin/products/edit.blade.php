@extends('admin.layouts.admin')
@section('title','ویرایش محصول')

@section('content')
<div class="row">
  <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
    <h5 class="font-weight-bold mb-4">ویرایش: {{ $product->name }}</h5>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
      @csrf
      @method('PUT')
        @include('admin.sections.errors')
      <div class="row">
        <div class="form-group col-md-3">
          <label>نام</label>
          <input type="text" name="name" class="form-control"
                 value="{{ old('name', $product->name) }}">
        </div>

        <div class="form-group col-md-3">
          <label>برند</label>
          <select name="brand_id" class="form-control">
            @foreach($brands as $b)
              <option value="{{ $b->id }}" {{ (old('brand_id', $product->brand_id)==$b->id)?'selected':'' }}>
                {{ $b->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md-3">
          <label>دسته‌بندی</label>
          <select name="category_id" class="form-control" id="categorySelect">
            @foreach($categories as $c)
              <option value="{{ $c->id }}" {{ (old('category_id', $product->category_id)==$c->id)?'selected':'' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md-3">
          <label>وضعیت</label>
          <select name="is_active" class="form-control">
            <option value="1" {{ old('is_active', $product->getRawOriginal('is_active'))==1?'selected':'' }}>فعال</option>
            <option value="0" {{ old('is_active', $product->getRawOriginal('is_active'))==0?'selected':'' }}>غیرفعال</option>
          </select>
        </div>

        <div class="form-group col-md-3">
          <label>هزینه ارسال</label>
          <input type="text" name="delivery_amount" class="form-control"
                 value="{{ old('delivery_amount', $product->delivery_amount) }}">
        </div>

        <div class="form-group col-md-3">
          <label>هزینه ارسال به ازای محصول اضافی</label>
          <input type="text" name="delivery_amount_per_product" class="form-control"
                 value="{{ old('delivery_amount_per_product', $product->delivery_amount_per_product) }}">
        </div>

        <div class="form-group col-md-12">
          <label>توضیحات</label>
          <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group col-md-4">
        <label>تگ‌ها</label>

        @php
            $selected = old('tag_ids', $product->tags->pluck('id')->toArray());
        @endphp

        <select name="tag_ids[]"
        class="selectpicker"
        multiple
        data-live-search="true"
        data-width="100%"
        title="انتخاب تگ‌ها">

            @foreach($tags as $tag)
            <option value="{{ $tag->id }}" {{ in_array($tag->id, $selected) ? 'selected' : '' }}>
                {{ $tag->name }}
            </option>
            @endforeach
        </select>

        @error('tag_ids') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <hr>

      </div>
        {{-- Attributes Section --}}
        <div class="col-md-12">
            <hr>
            <p>ویژگی‌ها :</p>
        </div>

        <div class="row"> {{-- یا: form-row در Bootstrap 4 --}}
            @foreach ($productAttributes as $productAttribute)
                <div class="col-12 col-md-4">
                    <div class="form-group mb-3">
                        <label>{{ $productAttribute->attribute->name }}</label>
                        <input class="form-control"
                            type="text"
                            name="attribute_values[{{ $productAttribute->id }}]"
                            value="{{ old("attribute_values.{$productAttribute->id}", $productAttribute->value) }}">
                    </div>
                </div>
            @endforeach
        </div>


        {{-- Variations Section --}}
        @foreach ($productVariations as $variation)
            <div class="col-md-12">
                <hr>
                <div class="d-flex">
                    <p class="mb-0">قیمت و موجودی برای متغیر ( {{ $variation->value }} ) :</p>
                    <p class="mb-0 mr-3">
                        <button class="btn btn-sm btn-primary"
                                type="button"
                                data-toggle="collapse"
                                data-target="#collapse-{{ $variation->id }}">
                            نمایش
                        </button>
                    </p>
                </div>
            </div>

            <div class="col-md-12">
                <div class="collapse mt-2" id="collapse-{{ $variation->id }}">
                    <div class="card card-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>قیمت</label>
                                <input type="text"
                                    name="variation_values[{{ $variation->id }}][price]"
                                    value="{{ old("variation_values.{$variation->id}.price", $variation->price) }}"
                                    class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label>تعداد</label>
                                <input type="text"
                                    name="variation_values[{{ $variation->id }}][quantity]"
                                    value="{{ old("variation_values.{$variation->id}.quantity", $variation->quantity) }}"
                                    class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label>SKU</label>
                                <input type="text"
                                    name="variation_values[{{ $variation->id }}][sku]"
                                    value="{{ old("variation_values.{$variation->id}.sku", $variation->sku) }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-12">
                                <p>حراج:</p>
                            </div>

                            <div class="form-group col-md-3">
                                <label>قیمت حراجی</label>
                                <input type="text"
                                    name="variation_values[{{ $variation->id }}][sale_price]"
                                    value="{{ old("variation_values.{$variation->id}.sale_price", $variation->sale_price) }}"
                                    class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                            <label>تاریخ شروع حراجی</label>
                            <input type="text"
                                name="variation_values[{{ $variation->id }}][date_on_sale_from]"
                                value="{{ old("variation_values.{$variation->id}.date_on_sale_from", $variation->date_on_sale_from ? verta($variation->date_on_sale_from) : '') }}"
                                class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label>تاریخ پایان حراجی</label>
                                <input type="text"
                                    name="variation_values[{{ $variation->id }}][date_on_sale_to]"
                                    value="{{ old("variation_values.{$variation->id}.date_on_sale_to", $variation->date_on_sale_to ? verta($variation->date_on_sale_to) : '') }}"
                                    class="form-control">
                            </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

      <button class="btn btn-primary mt-3">ذخیره تغییرات</button>
      <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">انصراف</a>

    </form>
  </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/admin/admin.js','resources/scss/admin.scss'])
@endpush
