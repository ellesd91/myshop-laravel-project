{{-- _form.blade.php --}}
@csrf

{{-- اگر create است، method = POST و اگر edit است از @method('PUT') در صفحه والد استفاده کن --}}

<div class="row">
  {{-- مثال: نام --}}
  <div class="form-group col-md-6">
    <label>نام</label>
    <input type="text" class="form-control"
           name="name"
           value="{{ old('name', $product->name ?? '') }}">
  </div>

  {{-- مثال: تگ‌ها (bootstrap-select) --}}
  <div class="form-group col-md-6">
    <label>تگ‌ها</label>
    @php
      $selected = old('tag_ids', isset($product) ? $product->tags->pluck('id')->toArray() : []);
    @endphp
    <select name="tag_ids[]" class="selectpicker" multiple
            data-live-search="true" data-width="100%">
      @foreach($tags as $tag)
        <option value="{{ $tag->id }}" {{ in_array($tag->id, $selected) ? 'selected' : '' }}>
          {{ $tag->name }}
        </option>
      @endforeach
    </select>
  </div>

  {{-- ... بقیه فیلدها ... --}}

  {{-- فقط در حالت ویرایش: پیش‌نمایش تصویر اصلی و گالری --}}
  @if(!empty($product?->id))
    <div class="col-md-12"><hr><p>تصویر محصول :</p></div>
    <div class="form-group col-md-3">
      <img src="{{ rtrim(env('PRODUCT_IMAGES_UPLOAD_PATH'), '/') . '/' . $product->primary_image }}"
           class="img-thumbnail" style="max-width:200px;height:auto;">
    </div>

    {{-- آپلود تصویر جدید/گالری هم می‌تونه برای هر دو حالت فعال باشه، اما نمایش گالری فعلی فقط در edit معنی دارد --}}
    @if($product->images->count())
      <div class="col-md-12">
        <div class="d-flex flex-wrap">
          @foreach($product->images as $pi)
            <div class="mr-2 mb-2 text-center" style="width:120px">
              <img src="{{ rtrim(env('PRODUCT_IMAGES_UPLOAD_PATH'), '/') . '/' . $pi->image }}"
                   class="img-thumbnail mb-1" style="max-width:100%;height:auto;">
              {{-- دکمه حذف تصویر گالری (اختیاری) --}}
            </div>
          @endforeach
        </div>
      </div>
    @endif
  @endif
</div>
