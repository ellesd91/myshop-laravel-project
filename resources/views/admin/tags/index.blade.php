@extends('admin.layouts.admin')

@section('title', 'لیست تگ‌ها')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">لیست تگ‌ها ({{ $tags->total() }})</h1>
        <a href="{{ route('admin.tags.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            ایجاد تگ
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
                        @forelse ($tags as $key => $tag)
                            <tr>
                                <td>{{ $tags->firstItem() + $key }}</td>
                                <td>{{ $tag->name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-outline-warning" href="{{route('admin.tags.show' , ['tag' => $tag->id])}}">نمایش</a>
                                    <a class="btn btn-sm btn-outline-info mr-3" href="{{route('admin.tags.edit' , ['tag' => $tag->id])}}">ویرایش</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">هیچ تگی یافت نشد.</td>
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
