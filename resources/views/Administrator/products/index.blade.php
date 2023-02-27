@extends('layouts.admin')
@section('content')
<div class="col-md-3">
    <a href="{{ route('Products') }}">
        <div class="card-counter success">
            <i class="fab fa-product-hunt"></i>
            <span class="count-numbers">{{ DB::table('products')->count() }}</span>
            <span class="count-name">@lang('admin.routes.Products')</span>
        </div>
    </a>
</div>
<div class="col-md-3">
    <a href="{{ route('ProductCategories') }}">
        <div class="card-counter warning">
            <i class="fa fa-list"></i>
            <span class="count-numbers">{{ DB::table('product_categories')->count() }}</span>
            <span class="count-name">@lang('admin.categories')</span>
        </div>
    </a>
</div>
<div class="col-md-3">
    <a href=" {{ route('Brands') }}">
        <div class="card-counter info">
            <i class="fa fa-tag"></i>
            <span class="count-numbers">{{ DB::table('brands')->count() }}</span>
            <span class="count-name">@lang('admin.routes.Brands')</span>
        </div>
    </a>
</div>
<div class="col-md-3">
    <a href="{{ route('Sales') }}">
        <div class="card-counter danger">
            <i class="fas fa-calendar-alt"></i>
            <span class="count-numbers"></span>
            <span class="count-name">@lang('admin.routes.Sales')</span>
        </div>
    </a>
</div>
<!--
<div class="col-md-3">
    <a href="{{ route('Reviews') }}">
        <div class="card-counter primary">
            <i class="fa fa-star" style="color: white !important;"></i>
            <span class="count-numbers">{{ DB::table('product_reviews')->count() }}</span>
            <span class="count-name">@lang('admin.routes.Reviews')</span>
        </div>
    </a>
</div>
-->
@endsection