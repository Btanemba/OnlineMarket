@extends('frontend.layout')

@section('title', $product->name . ' - Online Market')

@section('content')
<div class="container my-5">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="la la-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="la la-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('category', $product->category->id) }}">{{ $product->category->name }}</a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            @if($product->images && is_array($product->images) && count($product->images) > 0)
                <!-- Main Image -->
                <div class="card mb-3">
                    @php
                        $firstImage = $product->images[0];
                        $mainImageSrc = filter_var($firstImage, FILTER_VALIDATE_URL) 
                            ? $firstImage 
                            : asset('storage/' . $firstImage);
                    @endphp
                    <img id="mainProductImage" src="{{ $mainImageSrc }}" alt="{{ $product->name }}" 
                         class="card-img-top" style="height: 400px; object-fit: cover;">
                </div>
                
                <!-- Thumbnail Gallery -->
                @if(count($product->images) > 1)
                    <div class="row">
                        @foreach($product->images as $index => $image)
                            @php
                                $thumbSrc = filter_var($image, FILTER_VALIDATE_URL) 
                                    ? $image 
                                    : asset('storage/' . $image);
                            @endphp
                            <div class="col-3 mb-2">
                                <img src="{{ $thumbSrc }}" alt="{{ $product->name }}" 
                                     class="img-thumbnail product-thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                     onclick="changeMainImage('{{ $thumbSrc }}', this)"
                                     style="cursor: pointer; height: 80px; object-fit: cover; width: 100%;">
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="card">
                    <img src="https://via.placeholder.com/600x400?text={{ urlencode($product->name) }}" 
                         alt="{{ $product->name }}" class="card-img-top">
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="card p-4">
                <!-- Category Badge -->
                @if($product->category)
                    <div class="mb-3">
                        <a href="{{ route('category', $product->category->id) }}" class="category-badge">
                            <i class="la la-tag"></i> {{ $product->category->name }}
                        </a>
                    </div>
                @endif

                <!-- Product Name -->
                <h1 class="mb-3" style="font-weight: 700; font-size: 2rem;">{{ $product->name }}</h1>

                <!-- Price -->
                <h2 class="mb-4" style="color: #667eea; font-weight: 700;">
                    ${{ number_format($product->price, 2) }}
                </h2>

                <!-- Stock Status -->
                <div class="mb-4">
                    @if($product->stock > 10)
                        <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); font-size: 1rem; padding: 8px 16px;">
                            <i class="la la-check-circle"></i> In Stock ({{ $product->stock }} available)
                        </span>
                    @elseif($product->stock > 0)
                        <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); font-size: 1rem; padding: 8px 16px;">
                            <i class="la la-exclamation-circle"></i> Low Stock (Only {{ $product->stock }} left!)
                        </span>
                    @else
                        <span class="badge bg-secondary" style="font-size: 1rem; padding: 8px 16px;">
                            <i class="la la-times-circle"></i> Out of Stock
                        </span>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <h5 style="font-weight: 600; margin-bottom: 15px;">Description</h5>
                    <p class="text-muted" style="line-height: 1.8; font-size: 1rem;">
                        {{ $product->description }}
                    </p>
                </div>

                <!-- Divider -->
                <hr class="my-4">

                <!-- Product Info -->
                <div class="row mb-4">
                    <div class="col-6">
                        <p class="text-muted mb-1"><small>SKU</small></p>
                        <p style="font-weight: 600;">#{{ $product->id }}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1"><small>Available Stock</small></p>
                        <p style="font-weight: 600;">{{ $product->stock }} units</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100" style="padding: 15px;">
                                <i class="la la-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="la la-times-circle"></i> Out of Stock
                        </button>
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="la la-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="text-center mb-4" style="font-weight: 700;">
            <i class="la la-sparkles"></i> You May Also Like
        </h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card card">
                        <div style="position: relative;">
                            @if($relatedProduct->images && is_array($relatedProduct->images) && count($relatedProduct->images) > 0)
                                @php
                                    $firstImage = $relatedProduct->images[0];
                                    $imageSrc = filter_var($firstImage, FILTER_VALIDATE_URL) 
                                        ? $firstImage 
                                        : asset('storage/' . $firstImage);
                                @endphp
                                <img src="{{ $imageSrc }}" alt="{{ $relatedProduct->name }}">
                            @else
                                <img src="https://via.placeholder.com/400x250?text={{ urlencode($relatedProduct->name) }}" 
                                     alt="{{ $relatedProduct->name }}">
                            @endif
                            
                            @if($relatedProduct->stock > 10)
                                <span class="stock-badge in-stock">In Stock</span>
                            @elseif($relatedProduct->stock > 0)
                                <span class="stock-badge low-stock">Low Stock</span>
                            @else
                                <span class="stock-badge out-of-stock">Out of Stock</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="product-title">{{ Str::limit($relatedProduct->name, 40) }}</h5>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="product-price">${{ number_format($relatedProduct->price, 2) }}</span>
                                <a href="{{ route('product', $relatedProduct->id) }}" class="btn btn-primary btn-sm">
                                    View <i class="la la-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function changeMainImage(src, element) {
        // Change main image
        document.getElementById('mainProductImage').src = src;
        
        // Update active state
        document.querySelectorAll('.product-thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        element.classList.add('active');
    }
</script>

<style>
    .product-thumbnail {
        border: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .product-thumbnail:hover,
    .product-thumbnail.active {
        border-color: #667eea;
        transform: scale(1.05);
    }
</style>
@endpush
@endsection
