@extends('frontend.layout')

@section('title', $category->name . ' - Online Market')

@section('content')
<!-- Category Header -->
<div class="hero" style="padding: 60px 0;">
    <div class="container text-center">
        <h1><i class="la la-tag"></i> {{ $category->name }}</h1>
        @if($category->description)
            <p>{{ $category->description }}</p>
        @endif
        <a href="{{ route('home') }}" class="btn btn-light mt-3">
            <i class="la la-arrow-left"></i> Back to All Products
        </a>
    </div>
</div>

<!-- Products in Category -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-weight: 700; margin: 0;">
            Products in {{ $category->name }}
        </h2>
        <span class="badge bg-primary" style="font-size: 1rem; padding: 8px 16px;">
            {{ $products->total() }} {{ Str::plural('Product', $products->total()) }}
        </span>
    </div>
    
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card card">
                        <div style="position: relative;">
                            @if($product->images && is_array($product->images) && count($product->images) > 0)
                                @php
                                    $firstImage = $product->images[0];
                                    $imageSrc = filter_var($firstImage, FILTER_VALIDATE_URL) 
                                        ? $firstImage 
                                        : asset('storage/' . $firstImage);
                                @endphp
                                <img src="{{ $imageSrc }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/400x250?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                            @endif
                            
                            @if($product->stock > 10)
                                <span class="stock-badge in-stock">In Stock</span>
                            @elseif($product->stock > 0)
                                <span class="stock-badge low-stock">Low Stock</span>
                            @else
                                <span class="stock-badge out-of-stock">Out of Stock</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="product-title">{{ Str::limit($product->name, 40) }}</h5>
                            <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                {{ Str::limit($product->description, 60) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('product', $product->id) }}" class="btn btn-primary btn-sm">
                                    View <i class="la la-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="la la-shopping-bag" style="font-size: 5rem; color: #e9ecef;"></i>
            <h4 class="text-muted mt-3">No products in this category yet</h4>
            <p class="text-muted">Check other categories or come back later!</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                <i class="la la-arrow-left"></i> Browse All Products
            </a>
        </div>
    @endif
</div>

<!-- Other Categories -->
@if($otherCategories->count() > 0)
<div class="container mb-5">
    <h3 class="text-center mb-4" style="font-weight: 700;">Browse Other Categories</h3>
    <div class="text-center">
        @foreach($otherCategories as $otherCategory)
            <a href="{{ route('category', $otherCategory->id) }}" class="category-badge">
                <i class="la la-tag"></i> {{ $otherCategory->name }}
                <span class="badge bg-light text-dark ms-1">{{ $otherCategory->products_count }}</span>
            </a>
        @endforeach
    </div>
</div>
@endif
@endsection
