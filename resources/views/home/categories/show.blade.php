@extends('home.layouts.home')

@section('title')
    صفحه ای فروشگاه
@endsection

@section('content')

    <div class="breadcrumb-area pt-35 pb-35 bg-gray" style="direction: rtl;">
        <div class="container">
            <div class="breadcrumb-content text-center">
                <ul>
                    <li>
                        <a href="{{ route('home.index') }}">صفحه ای اصلی</a>
                    </li>
                    <li class="active">فروشگاه </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- <form  id="filter-form"> --}}
    <form id="filter-form" method="GET" action="{{ route('home.categories.show', ['category' => $category->slug]) }}">

        @foreach ($attributes as $attribute)
        <input id="filter-attribute-{{ $attribute->id }}" type="hidden" name="attribute[{{ $attribute->id }}]">
        @endforeach

        <input id="filter-variation" type="hidden" name="variation">
        <input id="filter-sort-by" type="hidden" name="sortBy">
        <input id="filter-search" type="hidden" name="search">

        <div class="shop-area pt-95 pb-100">
            <div class="container">
                <div class="row flex-row-reverse text-right">

                    <!-- sidebar -->
                    <div class="col-lg-3 order-2 order-sm-2 order-md-1">
                        <div class="sidebar-style mr-30">
                            <div class="sidebar-widget">
                                <h4 class="pro-sidebar-title">جستجو </h4>
                                <div class="pro-sidebar-search mb-50 mt-25">
                                    <form class="pro-sidebar-search-form" action="#">
                                        <input type="text"  id="search-input" placeholder="... جستجو "
                                        value="{{ request()->has('search') ? request()->search : '' }}">
                                        <button type="button" onclick="filter()">
                                            <i class="sli sli-magnifier"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="sidebar-widget">
                                <h4 class="pro-sidebar-title">دسته بندی</h4>
                                <div class="sidebar-widget-list mt-30">
                                    <ul>
                                        @php
                                            // اگر دستهٔ فعلی فرزند است، والدش را نشان بده؛
                                            // اگر والد ندارد، خودش را به‌عنوان ریشه بگیر.
                                            $parent   = $category->parent ?: $category;
                                            $children = $parent->children ?? collect();
                                        @endphp

                                        {{-- عنوان ریشه (مثلاً "مردانه") --}}
                                        <li class="font-weight-bold mb-2">{{ $parent->name }}</li>

                                        {{-- لیست زیر‌دسته‌ها (خواهر/برادرهای دستهٔ فعلی + خودش) --}}
                                        @foreach ($children as $childCategory)
                                            <li>
                                                <a href="{{ route('home.categories.show', ['category' => $childCategory->slug]) }}"
                                                style="{{ $childCategory->slug === $category->slug ? 'color:#ff3535' : '' }}">
                                                    {{ $childCategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <hr>

                            @foreach ($attributes as $attribute)
                                {{-- داخل حلقهٔ values هر attribute --}}
                                @php
                                    // مقادیر انتخاب‌شده‌ی همین اتربیوت از URL (مثلاً "آبی-مشکی")
                                    $selected = collect(explode('-', request('attribute.'.$attribute->id, '')))
                                                ->filter()->all();
                                @endphp

                                <div class="sidebar-widget mt-30">
                                    <h4 class="pro-sidebar-title">{{ $attribute->name }} </h4>
                                    <div class="sidebar-widget-list mt-20">
                                        <ul>
                                            @foreach ($attribute->values as $value)
                                            <li>
                                                    <div class="sidebar-widget-list-left">
                                                            <input
                                                            type="checkbox"
                                                            class="attr"
                                                            data-attr-id="{{ $attribute->id }}"
                                                            value="{{ $value->value }}"
                                                            onchange="filter()"
                                                            {{ in_array($value->value, explode('-', request('attribute.'.$attribute->id, ''))) ? 'checked' : '' }}
                                                            >



                                                        <a href="#" onclick="this.previousElementSibling.click(); return false;">{{ $value->value }}</a>
                                                                <span class="checkmark"></span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <hr>
                            @endforeach
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">{{ $variation->name }} </h4>
                                <div class="sidebar-widget-list mt-20">
                                    <ul>
                                        @foreach ($variation->variationValues as $value)
                                            <li>
                                                <div class="sidebar-widget-list-left">
                                                    <input type="checkbox" class="variation" value="{{ $value->value }}"
                                                    onchange="filter()"
                                                    {{ ( request()->has('variation') && in_array( $value->value , explode('-' , request('variation') ) ) ) ? 'checked' : '' }}
                                                    > <a href="#" onclick="this.previousElementSibling.click(); return false;"> {{ $value->value }} </a>
                                                    <span class="checkmark"></span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- content -->
                    <div class="col-lg-9 order-1 order-sm-1 order-md-2">
                        <!-- shop-top-bar -->
                        <div class="shop-top-bar" style="direction: rtl;">

                            <div class="select-shoing-wrap">
                                <div class="shop-select select-caret">
                                    <select id="sort-by" onchange="filter()">
                                        <option value="default"> مرتب سازی </option>
                                        <option value="max"
                                            {{ request()->has('sortBy') && request()->sortBy == 'max' ? 'selected' : '' }}>
                                            بیشترین قیمت </option>
                                        <option value="min"
                                            {{ request()->has('sortBy') && request()->sortBy == 'min' ? 'selected' : '' }}> کم
                                            ترین قیمت </option>
                                        <option value="latest"
                                            {{ request()->has('sortBy') && request()->sortBy == 'latest' ? 'selected' : '' }}>
                                            جدیدترین </option>
                                        <option value="oldest"
                                            {{ request()->has('sortBy') && request()->sortBy == 'oldest' ? 'selected' : '' }}>
                                            قدیمی ترین
                                        </option>
                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="shop-bottom-area mt-35">
                            <div class="tab-content jump">

                                <div class="row ht-products" style="direction: rtl;">
                                    @foreach($products as $product)
                                    <div class="col-xl-4 col-md-6 col-lg-6 col-sm-6">
                                        <!--Product Start-->
                                        <div class="ht-product ht-product-action-on-hover ht-product-category-right-bottom mb-30">
                                            <div class="ht-product-inner">
                                                <div class="ht-product-image-wrap">
                                                    <a href="product-details.html" class="ht-product-image">
                                                        <img src="{{asset(env('PRODUCT_IMAGES_UPLOAD_PATH') . $product->primary_image) }}" alt="{{ $product->name }}" />
                                                    </a>
                                                    <div class="ht-product-action">
                                                        <ul>
                                                            <li>
                                                                <a href="#" data-toggle="modal" data-target="#productModal{{ $product->id }}"><i
                                                                        class="sli sli-magnifier"></i><span
                                                                        class="ht-product-action-tooltip"> مشاهده سریع
                                                                    </span></a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="sli sli-heart"></i><span
                                                                        class="ht-product-action-tooltip"> افزودن به
                                                                        علاقه مندی ها </span></a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="sli sli-refresh"></i><span
                                                                        class="ht-product-action-tooltip"> مقایسه
                                                                    </span></a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="ht-product-content">
                                                    <div class="ht-product-content-inner">
                                                        <div class="ht-product-categories">
                                                            <a href="#">{{ $product->category->name }}</a>
                                                        </div>
                                                        <h4 class="ht-product-title text-right">
                                                            <a href="#">{{ $product->name }}</a>
                                                        </h4>
                                                        <div class="ht-product-price">
                                                            @if($product->quantity_check)
                                                                @if($product->sale_check)
                                                                    <span class="new">
                                                                        {{number_format($product->sale_check->sale_price)}}
                                                                        تومان
                                                                    </span>
                                                                    <span class="old">
                                                                        {{number_format($product->sale_check->price)}}
                                                                        تومان
                                                                    </span>
                                                                @else
                                                                    <span class="new">
                                                                        {{number_format($product->price_check->price)}}
                                                                        تومان
                                                                    </span>
                                                                @endif
                                                            @else
                                                            <div class="not-in-stock">
                                                                <p class="text-white">ناموجود</p>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="ht-product-ratting-wrap">
                                                            <div data-rating-stars="5"
                                                            data-rating-readonly="true"
                                                            data-rating-value="{{ ceil($product->rates->avg('rate')) }}">
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                         </div>
                                        <!--Product End-->
                                    </div>
                                    @endforeach

                                </div>

                            </div>

                            <div class="pro-pagination-style text-center mt-30">

                                {{ $products->links() }}

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>

     <!-- Modal -->
    @foreach ($products as $product)
        <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7 col-sm-12 col-xs-12" style="direction: rtl;">
                                <div class="product-details-content quickview-content">
                                    <h2 class="text-right mb-4"> {{ $product->name }} </h2>
                                    <div class="product-details-price  variation-price">
                                        @if($product->quantity_check)
                                            @if($product->sale_check)
                                                <span class="new">
                                                    {{number_format($product->sale_check->sale_price)}}
                                                    تومان
                                                </span>
                                                <span class="old">
                                                    {{number_format($product->sale_check->price)}}
                                                    تومان
                                                </span>
                                            @else
                                                <span class="new">
                                                    {{number_format($product->price_check->price)}}
                                                    تومان
                                                </span>
                                            @endif
                                        @else
                                        <div class="not-in-stock">
                                            <p class="text-white">ناموجود</p>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="pro-details-rating-wrap">

                                        <div data-rating-stars="5"
                                            data-rating-readonly="true"
                                            data-rating-value="{{ ceil($product->rates->avg('rate')) }}">
                                        </div>
                                        <span class="mx-3">|</span>
                                        <span>3 دیدگاه</span>
                                    </div>
                                    <p class="text-right">
                                        {{ $product->description }}
                                    </p>
                                    <div class="pro-details-list text-right">
                                       <ul class="text-right">
                                        @foreach($product->productAttributes as $attr)
                                            <li>
                                                {{ $attr->attribute->name }} : {{ $attr->value }}
                                            </li>
                                        @endforeach
                                        </ul>
                                    </div>

                                   @if($product->quantity_check)
                                        @php
                                            // 1)  : از اولین ورییشن بخوان (اگر لود شده باشد)
                                            $firstVar = $product->variations->first();
                                            $label = $firstVar && $firstVar->attribute ? $firstVar->attribute->name : null;

                                            // 2) اگر به هر دلیل خالی بود، از پیوت دسته (is_variation=1) بگیر
                                            if (!$label) {
                                                $varAttr = optional($product->category->attributes->firstWhere('pivot.is_variation', 1));
                                                $label = $varAttr->name ?? null;
                                            }
                                        @endphp

                                        <div class="pro-details-size-color text-right">
                                            <div class="pro-details-size w-50">
                                                {{-- برچسب (بدون fallbackِ «سایز») --}}
                                                <span>{{ $label ?? '—' }}</span>


                                                <select class="form-control variation-select">
                                                @foreach ($product->variations()->where('quantity' , '>' , 0)->get() as $variation)
                                                    <option value="{{ json_encode($variation->only(['id' , 'quantity','is_sale' , 'sale_price' , 'price'])) }}">{{ $variation->value }}</option>
                                                @endforeach
                                               </select>


                                            </div>
                                        </div>

                                        <div class="pro-details-quality">
                                            <div class="cart-plus-minus">
                                                <input class="cart-plus-minus-box quantity-input" type="text" name="qty" value="1" data-max="5" />
                                            </div>
                                            <div class="pro-details-cart">
                                                <button type="button">افزودن به سبد خرید</button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="not-in-stock"><p class="text-white">ناموجود</p></div>
                                    @endif




                                    <div class="pro-details-meta">
                                        <span>دسته بندی :</span>
                                        <ul>
                                            <li>
                                                {{ optional($product->category->parent)->name ? optional($product->category->parent)->name . ' : ' : '' }}
                                                {{ optional($product->category)->name ?? '—' }}
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pro-details-meta">
                                       <span>تگ ها :</span>
                                    <ul>
                                        @foreach ($product->tags as $tag)
                                        <li><a href="#">{{ $tag->name }}{{ $loop->last ? '' : '،' }}</a></li>
                                        @endforeach
                                    </ul>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-5 col-sm-12 col-xs-12">
                            <div class="tab-content quickview-big-img">
                                <div id="pro-primary-{{$product->id}}" class="tab-pane fade show active">
                                    <img src="{{ asset(env('PRODUCT_IMAGES_UPLOAD_PATH') . $product->primary_image) }}" alt="" />
                                </div>
                                @foreach ($product->images as $img)
                                    <div id="pro-{{$img->id}}" class="tab-pane fade">
                                        <img src="{{ asset(env('PRODUCT_IMAGES_UPLOAD_PATH') . $img->image) }}" alt="" />
                                    </div>
                                @endforeach
                            </div>
                            <!-- Thumbnail Large Image End -->
                            <!-- Thumbnail Image End -->
                            <div class="quickview-wrap mt-15">
                                <div class="quickview-slide-active owl-carousel nav nav-style-2" role="tablist">
                                    <a class="active" data-toggle="tab" href="#pro-primary-{{$product->id}}">
                                        <img src="{{ asset(env('PRODUCT_IMAGES_UPLOAD_PATH') . $product->primary_image) }}" alt="" />
                                    </a>
                                    @foreach ($product->images as $img)
                                    <a data-toggle="tab" href="#pro-{{$img->id}}">
                                        <img src="{{ asset(env('PRODUCT_IMAGES_UPLOAD_PATH') . $img->image) }}" alt="" />
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                             </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal end -->

@endsection



