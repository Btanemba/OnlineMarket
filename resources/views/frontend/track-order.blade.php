@extends('frontend.layout')

@section('title', 'Track Your Order - Online Market')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 style="font-weight: 700; color: #2c3e50;">
                    <i class="la la-search"></i> Track Your Order
                </h1>
                <p class="text-muted" style="font-size: 1.1rem;">
                    Enter your order number and email to check your order status
                </p>
            </div>

            <!-- Search Form -->
            <div class="card shadow-sm mb-4" style="border-radius: 12px; border: none;">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0" style="font-weight: 600;">
                        <i class="la la-file-invoice"></i> Order Lookup
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="la la-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('track.order.search') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600;">
                                <i class="la la-hashtag"></i> Order Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('order_number') is-invalid @enderror" 
                                   name="order_number" 
                                   value="{{ old('order_number') }}"
                                   placeholder="e.g., ORD-20251112-ABC123" 
                                   required
                                   style="border-radius: 8px;">
                            @error('order_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="la la-info-circle"></i> You can find this in your order confirmation email
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600;">
                                <i class="la la-envelope"></i> Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="your.email@example.com" 
                                   required
                                   style="border-radius: 8px;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="la la-info-circle"></i> Enter the email used during checkout
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100" style="border-radius: 8px;">
                            <i class="la la-search"></i> Track Order
                        </button>
                    </form>
                </div>
            </div>

            @if(isset($order))
                <!-- Order Status Result -->
                <div class="card shadow-sm" style="border-radius: 12px; border: none;">
                    <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border-radius: 12px 12px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0" style="font-weight: 600;">
                                <i class="la la-check-circle"></i> Order Found
                            </h5>
                            <span class="badge bg-light text-dark" style="font-size: 1rem;">
                                {{ $order->order_number }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Order Status Timeline -->
                        <div class="mb-4">
                            <h6 style="font-weight: 600; color: #667eea; margin-bottom: 20px;">
                                <i class="la la-shipping-fast"></i> Order Status
                            </h6>
                            
                            <div class="position-relative" style="padding-left: 40px;">
                                <!-- Timeline Line -->
                                <div style="position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background: #e9ecef;"></div>
                                
                                <!-- Pending -->
                                <div class="mb-4 position-relative">
                                    <div style="position: absolute; left: -25px; width: 30px; height: 30px; border-radius: 50%; 
                                                background: {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? '#28a745' : '#e9ecef' }}; 
                                                display: flex; align-items: center; justify-content: center;">
                                        <i class="la la-check" style="color: white; font-size: 14px;"></i>
                                    </div>
                                    <strong style="color: {{ $order->status == 'pending' ? '#667eea' : '#2c3e50' }};">Order Placed</strong>
                                    <br><small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                                </div>

                                <!-- Processing -->
                                <div class="mb-4 position-relative">
                                    <div style="position: absolute; left: -25px; width: 30px; height: 30px; border-radius: 50%; 
                                                background: {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? '#28a745' : '#e9ecef' }}; 
                                                display: flex; align-items: center; justify-content: center;">
                                        <i class="la la-{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'check' : 'clock' }}" 
                                           style="color: white; font-size: 14px;"></i>
                                    </div>
                                    <strong style="color: {{ $order->status == 'processing' ? '#667eea' : '#2c3e50' }};">Processing</strong>
                                    <br><small class="text-muted">{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'Order is being prepared' : 'Awaiting processing' }}</small>
                                </div>

                                <!-- Shipped -->
                                <div class="mb-4 position-relative">
                                    <div style="position: absolute; left: -25px; width: 30px; height: 30px; border-radius: 50%; 
                                                background: {{ in_array($order->status, ['shipped', 'delivered']) ? '#28a745' : '#e9ecef' }}; 
                                                display: flex; align-items: center; justify-content: center;">
                                        <i class="la la-{{ in_array($order->status, ['shipped', 'delivered']) ? 'check' : 'clock' }}" 
                                           style="color: white; font-size: 14px;"></i>
                                    </div>
                                    <strong style="color: {{ $order->status == 'shipped' ? '#667eea' : '#2c3e50' }};">Shipped</strong>
                                    <br><small class="text-muted">{{ in_array($order->status, ['shipped', 'delivered']) ? 'Order is on the way' : 'Not shipped yet' }}</small>
                                </div>

                                <!-- Delivered -->
                                <div class="position-relative">
                                    <div style="position: absolute; left: -25px; width: 30px; height: 30px; border-radius: 50%; 
                                                background: {{ $order->status == 'delivered' ? '#28a745' : '#e9ecef' }}; 
                                                display: flex; align-items: center; justify-content: center;">
                                        <i class="la la-{{ $order->status == 'delivered' ? 'check' : 'clock' }}" 
                                           style="color: white; font-size: 14px;"></i>
                                    </div>
                                    <strong style="color: {{ $order->status == 'delivered' ? '#667eea' : '#2c3e50' }};">Delivered</strong>
                                    <br><small class="text-muted">{{ $order->status == 'delivered' ? 'Order has been delivered' : 'Not delivered yet' }}</small>
                                </div>
                            </div>

                            @if($order->status == 'cancelled')
                                <div class="alert alert-danger mt-3">
                                    <i class="la la-times-circle"></i> <strong>Order Cancelled</strong>
                                    <br>This order has been cancelled.
                                </div>
                            @endif
                        </div>

                        <hr>

                        <!-- Order Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 style="font-weight: 600; color: #667eea;">Customer Information</h6>
                                <p class="mb-1"><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 style="font-weight: 600; color: #667eea;">Shipping Address</h6>
                                <p class="mb-1">{{ $order->address }}</p>
                                <p class="mb-1">{{ $order->city }}, {{ $order->state }} {{ $order->zip }}</p>
                                <p class="mb-1">{{ $order->country }}</p>
                            </div>
                        </div>

                        <hr>

                        <!-- Order Items -->
                        <h6 style="font-weight: 600; color: #667eea; margin-bottom: 15px;">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td><strong>{{ $item->product_name }}</strong></td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                            <td class="text-end"><strong>${{ number_format($item->subtotal, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="border-top: 2px solid #dee2e6;">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong style="color: #667eea; font-size: 1.2rem;">${{ number_format($order->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <hr>

                        <!-- Payment Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>Payment Status:</strong> 
                                    <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'warning') }}" 
                                          style="font-size: 0.9rem;">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 text-center">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                                <i class="la la-arrow-left"></i> Continue Shopping
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-secondary">
                                <i class="la la-print"></i> Print Details
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Help Section -->
            <div class="text-center mt-5">
                <p class="text-muted">
                    <i class="la la-question-circle"></i> Need help? Contact us at 
                    <a href="mailto:support@onlinemarket.com" style="color: #667eea;">support@onlinemarket.com</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .navbar, footer, .btn, form, .text-center:last-child {
            display: none !important;
        }
    }
</style>
@endsection
