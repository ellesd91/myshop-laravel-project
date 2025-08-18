@extends('admin.layouts.admin')

@section('title', 'لیست محصولات')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">لیست محصولات ({{ $products->total() }})</h1>
        <a href="{{ route('admin.products.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            ایجاد محصول
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
                            <th>برند</th>
                            <th>دسته‌بندی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $key => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $key }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->brand->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-warning" href="{{route('admin.products.show' , ['product' => $product->id])}}">نمایش</a>
                                    <a class="btn btn-sm btn-outline-info mr-3" href="{{route('admin.products.edit' , ['product' => $product->id])}}">ویرایش</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">هیچ محصولی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- نمایش لینک‌های صفحه‌بندی --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $tags->links() }}
            </div>
        </div>
    </div>


@endsection
