@extends('admin.layouts.admin')

@section('title', 'Banners List')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-md-5 bg-white">
            <div class="d-flex justify-content-between mb-4">
                <h5 class="font-weight-bold">لیست بنرها ({{ $banners->total() }})</h5>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.banners.create') }}">
                    <i class="fa fa-plus"></i> ایجاد بنر
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>تصویر</th>
                            <th>عنوان</th>
                            <th>متن</th>
                            <th>اولویت</th>
                            <th>وضعیت</th>
                            <th>نوع</th>
                            <th>لینک دکمه</th>
                            <th>متن دکمه</th>
                            <th>آیکون دکمه</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $key => $banner)
                            <tr>
                                <td>{{ $banners->firstItem() + $key }}</td>
                                <td>

                                    <a href="{{ route('admin.banners.show', ['banner' => $banner->id]) }}">
                                        {{ basename($banner->image) }}
                                    </a>
                                </td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ $banner->text }}</td>
                                <td>{{ $banner->priority }}</td>
                                <td>
                                    @if ($banner->is_active)
                                        <span class="badge badge-success">فعال</span>
                                    @else
                                        <span class="badge badge-danger">غیرفعال</span>
                                    @endif
                                </td>
                                <td>{{ $banner->type }}</td>
                                <td>{{ $banner->button_link }}</td>
                                <td>{{ $banner->button_text }}</td>
                                <td>{{ $banner->button_icon }}</td>
                                <td class="d-flex justify-content-center">
                                    <a class="btn btn-sm btn-outline-info mr-3"
                                    href="{{ route('admin.banners.edit', ['banner' => $banner->id]) }}">
                                    <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.banners.destroy', ['banner' => $banner->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('آیا از حذف این بنر مطمئن هستید؟')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="mt-4">
                {{ $banners->links() }}
            </div>
        </div>
    </div>
@endsection
