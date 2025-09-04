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
                <table class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>نام برند</th>
                            <th>نام دسته بندی</th>
                            <th>وضیعت</th>
                            <th>تگ</th>

                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $key => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $key }}</td>
                                <td>
                                    <a href="{{route('admin.products.show' ,['product' => $product->id])}}">{{$product->name}}</a>
                                </td>
                                <td>
                                    <a href="{{route('admin.brands.show' ,['brand' => $product->brand->id])}}">{{$product->brand->name}}</a>
                                </td>

                                <td>{{ $product->category->name }}</td>

                               <td>
                                    <span class="badge {{ $product->getRawOriginal('is_active') ? 'badge-success' : 'badge-danger' }}">
                                        {{ $product->is_active }}
                                    </span>
                                </td>


                                <td>
                                    @foreach($product->tags as $tag)
                                        <span class="badge badge-info">{{ $tag->name }}</span>
                                    @endforeach
                                </td>



                               <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            عملیات
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right text-right">
                                            <a href="{{ route('admin.products.edit', ['product' => $product->id]) }}" class="dropdown-item">ویرایش محصول</a>
                                            <a href="#" class="dropdown-item">ویرایش تصاویر</a>
                                            <a href="#" class="dropdown-item">ویرایش دسته بندی و ویژگی</a>
                                        </div>
                                    </div>
                                </td>
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
                 {{ $products->links() }}
            </div>
        </div>
    </div>


@endsection
