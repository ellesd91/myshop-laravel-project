@extends('admin.layouts.admin')

@section('title', 'لیست برندها')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">لیست برندها ({{ $brands->total() }})</h1>
        <a href="{{ route('admin.brands.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            ایجاد برند جدید
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
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $key => $brand)
                            <tr>
                                <td>{{ $brands->firstItem() + $key }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>
                                    <span class="badge {{ $brand->is_active == 'فعال' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $brand->is_active }}
                                    </span>

                                </td>
                                <td>
                                    <a class="btn btn-sm btn-outline-warning" href="{{route('admin.brands.show' , ['brand' => $brand->id])}}">نمایش</a>
                                    <a class="btn btn-sm btn-outline-info mr-3" href="{{route('admin.brands.edit' , ['brand' => $brand->id])}}">ویرایش</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">هیچ برندی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- نمایش لینک‌های صفحه‌بندی --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $brands->links() }}
            </div>
        </div>
    </div>


@endsection
