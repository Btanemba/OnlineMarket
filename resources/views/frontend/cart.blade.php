@extends('frontend.layout')

@section('title', 'Shopping Cart - Online Market')

@section('content')
<div class="container my-5">
    <h1 class="mb-4" style="font-weight: 700;">
        <i class="la la-shopping-cart"></i> Shopping Cart
    </h1>

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

    @if(empty($cart))
        <div class="text-center py-5">
            <i class="la la-shopping-cart" style="font-size: 6rem; color: #e9ecef;"></i>
            <h3 class="mt-4 text-muted">Your cart is empty</h3>
            <p class="text-muted mb-4">Add some products to get started!</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                <i class="la la-arrow-left"></i> Continue Shopping
            </a>
        </div>
    @else
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        @foreach($cart as $id => $item)
                            <div class="row mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <!-- Product Image -->
                                <div class="col-md-2 col-3">
                                    @if($item['image'])
                                        @php
                                            $imageSrc = filter_var($item['image'], FILTER_VALIDATE_URL) 
                                                ? $item['image'] 
                                                : asset('storage/' . $item['image']);
                                        @endphp
                                        <img src="{{ $imageSrc }}" alt="{{ $item['name'] }}" 
                                             class="img-fluid rounded" style="width: 100%; height: 80px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/100?text=No+Image" 
                                             alt="{{ $item['name'] }}" class="img-fluid rounded">
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-4 col-9">
                                    <h5 style="font-weight: 600;">{{ $item['name'] }}</h5>
                                    <p class="text-muted mb-1" style="font-size: 0.9rem;">
                                        <i class="la la-tag"></i> {{ $item['category'] }}
                                    </p>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem;">
                                        Stock: {{ $item['stock'] }} available
                                    </p>
                                </div>

                                <!-- Quantity Control -->
                                <div class="col-md-3 col-6 mt-3 mt-md-0">
                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="decrementQuantity(this)">
                                                <i class="la la-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" class="form-control text-center" 
                                                   value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}"
                                                   style="max-width: 70px;">
                                            <button type="button" class="btn btn-outline-secondary" 
                                                    onclick="incrementQuantity(this, {{ $item['stock'] }})">
                                                <i class="la la-plus"></i>
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-sm ms-2">
                                                <i class="la la-check"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Price & Remove -->
                                <div class="col-md-3 col-6 mt-3 mt-md-0 text-end">
                                    <h5 style="font-weight: 700; color: #667eea;">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </h5>
                                    <small class="text-muted d-block mb-2">
                                        ${{ number_format($item['price'], 2) }} each
                                    </small>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="la la-trash"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Continue Shopping & Clear Cart -->
                <div class="mt-3">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="la la-arrow-left"></i> Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline float-end">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to clear your cart?')">
                            <i class="la la-trash"></i> Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card" style="position: sticky; top: 20px;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h5 class="mb-0" style="font-weight: 600;">
                            <i class="la la-calculator"></i> Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Subtotal -->
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal:</span>
                            <span style="font-weight: 600;">${{ number_format($total, 2) }}</span>
                        </div>

                        <!-- Items Count -->
                        <div class="d-flex justify-content-between mb-3">
                            <span>Items:</span>
                            <span style="font-weight: 600;">{{ count($cart) }}</span>
                        </div>

                        <!-- Shipping -->
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping:</span>
                            <span class="text-success" style="font-weight: 600;">FREE</span>
                        </div>

                        <hr>

                        <!-- Total -->
                        <div class="d-flex justify-content-between mb-4">
                            <h5 style="font-weight: 700;">Total:</h5>
                            <h5 style="font-weight: 700; color: #667eea;">${{ number_format($total, 2) }}</h5>
                        </div>

                        <!-- Checkout Button -->
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="la la-credit-card"></i> Proceed to Checkout
                        </a>

                        <!-- Trust Badges -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="text-center text-muted" style="font-size: 0.85rem;">
                                <i class="la la-shield-alt" style="font-size: 1.2rem; color: #667eea;"></i>
                                <p class="mb-1">Secure Checkout</p>
                                <i class="la la-shipping-fast" style="font-size: 1.2rem; color: #667eea;"></i>
                                <p class="mb-0">Fast Delivery</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function incrementQuantity(button, maxStock) {
        const input = button.parentElement.querySelector('input[name="quantity"]');
        const currentValue = parseInt(input.value);
        if (currentValue < maxStock) {
            input.value = currentValue + 1;
        }
    }

    function decrementQuantity(button) {
        const input = button.parentElement.querySelector('input[name="quantity"]');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }
</script>
@endpush
@endsection
