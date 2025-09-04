@extends('admin.layouts.admin')
@section('title','ویرایش محصول')

@section('content')
<div class="row">
  <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
    <h5 class="font-weight-bold mb-4">ویرایش: {{ $product->name }}</h5>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="form-group col-md-4">
          <label>نام</label>
          <input type="text" name="name" class="form-control"
                 value="{{ old('name', $product->name) }}">
        </div>

        <div class="form-group col-md-4">
          <label>برند</label>
          <select name="brand_id" class="form-control">
            @foreach($brands as $b)
              <option value="{{ $b->id }}" {{ (old('brand_id', $product->brand_id)==$b->id)?'selected':'' }}>
                {{ $b->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md-4">
          <label>دسته‌بندی</label>
          <select name="category_id" class="form-control" id="categorySelect">
            @foreach($categories as $c)
              <option value="{{ $c->id }}" {{ (old('category_id', $product->category_id)==$c->id)?'selected':'' }}>
                {{ $c->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group col-md-4">
          <label>وضعیت</label>
          <select name="is_active" class="form-control">
            <option value="1" {{ old('is_active', $product->getRawOriginal('is_active'))==1?'selected':'' }}>فعال</option>
            <option value="0" {{ old('is_active', $product->getRawOriginal('is_active'))==0?'selected':'' }}>غیرفعال</option>
          </select>
        </div>

        <div class="form-group col-md-4">
          <label>هزینه ارسال</label>
          <input type="text" name="delivery_amount" class="form-control"
                 value="{{ old('delivery_amount', $product->delivery_amount) }}">
        </div>

        <div class="form-group col-md-4">
          <label>هزینه ارسال به ازای محصول اضافی</label>
          <input type="text" name="delivery_amount_per_product" class="form-control"
                 value="{{ old('delivery_amount_per_product', $product->delivery_amount_per_product) }}">
        </div>

        <div class="form-group col-md-12">
          <label>توضیحات</label>
          <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group col-md-6">
        <label>تگ‌ها</label>

        @php
            $selected = old('tag_ids', $product->tags->pluck('id')->toArray());
        @endphp

        <select name="tag_ids[]"
                class="selectpicker"
                multiple
                data-live-search="true"
                data-actions-box="true"
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



      </div>

      <button class="btn btn-primary mt-3">ذخیره تغییرات</button>
      <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">انصراف</a>
    </form>
  </div>
</div>
@endsection

@push('scripts')
    {{-- 2) بعد فایل‌های Vite تو --}}
    @vite(['resources/js/admin/admin.js'])
@endpush
