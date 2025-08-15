@extends('admin.layouts.admin')

@section('title', 'ویرایش دسته‌بندی')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="mb-4">
                <h5 class="font-weight-bold">ویرایش دسته‌بندی: {{ $category->name }}</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.categories.update', ['category' => $category->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    {{-- Name --}}
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}">
                    </div>

                    {{-- Slug --}}
                    <div class="form-group col-md-3">
                        <label for="slug">نام انگلیسی (اسلاگ)</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
                    </div>

                    {{-- Parent Category --}}
                    <div class="form-group col-md-3">
                        <label for="parent_id">والد</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="0">بدون والد</option>
                            @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}"
                                    {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" {{ $category->getRawOriginal('is_active') ? 'selected' : '' }}>فعال</option>
                            <option value="0" {{ !$category->getRawOriginal('is_active') ? 'selected' : '' }}>غیرفعال</option>
                        </select>
                    </div>

                    {{-- Attributes --}}
                    <div class="form-group col-md-3">
                        <label for="attributeSelect">ویژگی‌ها</label>
                        <select id="attributeSelect" name="attribute_ids[]" class="form-control selectpicker" multiple data-live-search="true" title="ویژگی‌ها را انتخاب کنید...">
                            @foreach ($attributes as $attribute)
                                <option value="{{ $attribute->id }}"
                                    {{ in_array($attribute->id, $category->attributes->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $attribute->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filterable Attributes --}}
                    <div class="form-group col-md-3">
                        <label for="attributeIsFilterSelect">ویژگی‌های قابل فیلتر</label>
                        <select id="attributeIsFilterSelect" name="attribute_is_filter_ids[]" class="form-control selectpicker" multiple
                            data-live-search="true" title="ویژگی‌های قابل فیلتر را انتخاب کنید..."
                            data-selected-ids="{{ json_encode($categoryFilterableAttributeIds) }}">
                            {{-- گزینه‌ها با جاوااسکریپت پر می‌شوند --}}
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="variationsSelect">ویژگی متغیر</label>
                        <select id="variationsSelect" name="variation_id" class="form-control selectpicker" data-live-search="true"
                            title="ویژگی متغیر را انتخاب کنید..."
                            data-selected-id="{{ $categoryVariationAttributeId }}">
                            {{-- گزینه‌ها با جاوااسکریپت پر می‌شوند --}}
                        </select>
                    </div>
                                        {{-- Variation Attribute --}}


                     {{-- Icon --}}
                     <div class="form-group col-md-3">
                        <label for="icon">آیکون</label>
                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $category->icon) }}">
                    </div>

                    {{-- Description --}}
                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-primary mt-5">ویرایش</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark mt-5">بازگشت</a>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- ما همان فایل جاوااسکریپت فرم را برای صفحه ویرایش هم استفاده می‌کنیم --}}
    @vite(['resources/js/admin/admin.js', 'resources/js/category-form.js'])
@endpush
