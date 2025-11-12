@extends('frontend.layout')

@section('title', 'Home - Online Market')

@section('content')
<!-- Hero Section -->
<div class="hero">
    <div class="container text-center">
        <h1><i class="la la-shopping-cart"></i> Welcome to OnlineMarket</h1>
        <p>Discover amazing products at unbeatable prices</p>
        <a href="#products" class="btn btn-light btn-lg mt-3">
            Shop Now <i class="la la-arrow-down"></i>
        </a>
    </div>
</div>

<!-- Categories Section -->
<div class="container mb-5" id="categories">
    <h2 class="text-center mb-4" style="font-weight: 700;">Browse by Category</h2>
    <div class="text-center">
        <a href="{{ route('home') }}" class="category-badge {{ !request('category') ? 'active' : '' }}">
            <i class="la la-th-large"></i> All Products
        </a>
        @foreach($categories as $category)
            <a href="{{ route('category', $category->id) }}" class="category-badge">
                <i class="la la-tag"></i> {{ $category->name }}
                <span class="badge bg-light text-dark ms-1">{{ $category->products_count }}</span>
            </a>
        @endforeach
    </div>
</div>

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<div class="container mb-5">
    <h2 class="text-center mb-4" style="font-weight: 700;">
        <i class="la la-star"></i> Featured Products
    </h2>
    <div class="row">
        @foreach($featuredProducts as $product)
            <div class="col-lg-4 col-md-6 mb-4">
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
                        <span class="product-category">
                            <i class="la la-tag"></i> {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                        <h5 class="product-title">{{ Str::limit($product->name, 50) }}</h5>
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="product-price">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('product', $product->id) }}" class="btn btn-primary btn-sm">
                                View Details <i class="la la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- All Products Section -->
<div class="container mb-5" id="products">
    <h2 class="text-center mb-4" style="font-weight: 700;">All Products</h2>
    
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
                            <span class="product-category">
                                <i class="la la-tag"></i> {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                            <h5 class="product-title">{{ Str::limit($product->name, 40) }}</h5>
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
            <h4 class="text-muted mt-3">No products found</h4>
            <p class="text-muted">Check back later for new products!</p>
        </div>
    @endif
</div>
@endsection
