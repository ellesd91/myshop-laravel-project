@extends('admin.layouts.admin')

@section('title', 'لیست ویژگی‌ها')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">لیست ویژگی‌ها ({{ $attributes->total() }})</h1>
        <a href="{{ route('admin.attributes.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            ایجاد ویژگی
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
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attributes as $key => $attribute)
                            <tr>
                                <td>{{ $attributes->firstItem() + $key }}</td>
                                <td>{{ $attribute->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-warning" href="{{route('admin.attributes.show' , ['attribute' => $attribute->id])}}">نمایش</a>
                                    <a class="btn btn-sm btn-outline-info mr-3" href="{{route('admin.attributes.edit' , ['attribute' => $attribute->id])}}">ویرایش</a>
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
                {{ $attributes->links() }}
            </div>
        </div>
    </div>


@endsection
