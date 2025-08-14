@extends('admin.layouts.admin')

@section('title', 'لیست ویژگی‌ها')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">لیست دسته بندی ها ({{ $categories->total() }})</h1>
        <a href="{{ route('admin.categories.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            ایجاد دسته بندی
        </a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>نام</th>
                            <th>نام انگلیسی</th>
                            <th>والد</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $key => $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $key }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>
    {{-- اگر والد وجود داشت نامش را، وگرنه کلمه 'ندارد' را نمایش بده --}}
                                    {{ $category->parent ? $category->parent->name : 'ندارد' }}
                                </td>
                                <td>
                                    <span class="badge {{ $category->is_active == 'فعال' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $category->is_active }}
                                    </span>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-outline-warning" href="{{route('admin.categories.show' , ['category' => $category->id])}}">نمایش</a>
                                    <a class="btn btn-sm btn-outline-info mr-3" href="{{route('admin.categories.edit' , ['category' => $category->id])}}">ویرایش</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">هیچ ویژگی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- نمایش لینک‌های صفحه‌بندی --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        </div>
    </div>


@endsection
