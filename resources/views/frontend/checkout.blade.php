@extends('frontend.layout')

@section('title', 'Checkout - Online Market')

@section('content')
<div class="container my-5">
    <h1 class="mb-4" style="font-weight: 700;">
        <i class="la la-credit-card"></i> Checkout
    </h1>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="la la-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Customer Information Form -->
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="la la-user"></i> Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm" action="{{ route('checkout.place-order') }}" method="POST">
                        @csrf
                        
                        <!-- Contact Information -->
                        <h6 class="mb-3" style="font-weight: 600; color: #667eea;">Contact Information</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>

                        <!-- Shipping Address -->
                        <h6 class="mb-3" style="font-weight: 600; color: #667eea;">Shipping Address</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Street Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="address" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">State/Province <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="state" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ZIP/Postal Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="zip" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="country" required>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <h6 class="mb-3" style="font-weight: 600; color: #667eea;">Additional Notes</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3" 
                                      placeholder="Special instructions for delivery..."></textarea>
                        </div>

                        <!-- Payment Method -->
                        <h6 class="mb-3" style="font-weight: 600; color: #667eea;">Payment Method</h6>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        <i class="la la-money-bill-wave"></i> Cash on Delivery
                                        <small class="text-muted d-block">Pay when you receive your order</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="card" value="card">
                                    <label class="form-check-label" for="card">
                                        <i class="la la-credit-card"></i> Credit/Debit Card
                                        <small class="text-muted d-block">Coming soon</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" style="color: #667eea;">Terms and Conditions</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="la la-check-circle"></i> Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-5">
            <div class="card" style="position: sticky; top: 20px;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="la la-shopping-bag"></i> Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Cart Items -->
                    <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                        @foreach($cart as $item)
                            <div class="d-flex mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                @if($item['image'])
                                    @php
                                        $imageSrc = filter_var($item['image'], FILTER_VALIDATE_URL) 
                                            ? $item['image'] 
                                            : asset('storage/' . $item['image']);
                                    @endphp
                                    <img src="{{ $imageSrc }}" alt="{{ $item['name'] }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <img src="https://via.placeholder.com/60?text=No+Image" 
                                         alt="{{ $item['name'] }}" style="width: 60px; height: 60px; border-radius: 8px;">
                                @endif
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                        {{ Str::limit($item['name'], 30) }}
                                    </h6>
                                    <small class="text-muted">
                                        {{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}
                                    </small>
                                    <div class="text-end">
                                        <strong style="color: #667eea;">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <!-- Pricing Details -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span style="font-weight: 600;">${{ number_format($total, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="text-success" style="font-weight: 600;">FREE</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span style="font-weight: 600;">$0.00</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <h5 style="font-weight: 700;">Total:</h5>
                        <h5 style="font-weight: 700; color: #667eea;">${{ number_format($total, 2) }}</h5>
                    </div>

                    <!-- Promo Code -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Promo code">
                            <button class="btn btn-outline-secondary" type="button">Apply</button>
                        </div>
                    </div>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="la la-arrow-left"></i> Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
