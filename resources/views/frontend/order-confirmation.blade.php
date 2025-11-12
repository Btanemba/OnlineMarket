@extends('frontend.layout')

@section('title', 'Order Confirmation - Online Market')

@section('content')
<div class="container my-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="la la-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Success Message -->
    <div class="text-center mb-5">
        <div class="mb-4">
            <i class="la la-check-circle" style="font-size: 5rem; color: #28a745;"></i>
        </div>
        <h1 style="font-weight: 700; color: #28a745;">Order Placed Successfully!</h1>
        <p class="text-muted" style="font-size: 1.1rem;">
            Thank you for your order. We'll send you a confirmation email shortly.
        </p>
    </div>

    <!-- Order Details -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="font-weight: 600;">
                            <i class="la la-file-invoice"></i> Order Details
                        </h5>
                        <span class="badge bg-light text-dark" style="font-size: 0.9rem;">
                            {{ $order->order_number }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Customer Information -->
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

                    <!-- Order Status -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <p class="text-muted mb-1"><small>Order Status</small></p>
                            <span class="badge bg-{{ $order->status_color }}" style="font-size: 0.9rem; padding: 6px 12px;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1"><small>Payment Method</small></p>
                            <p style="font-weight: 600;">{{ strtoupper($order->payment_method) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1"><small>Payment Status</small></p>
                            <span class="badge bg-{{ $order->payment_status_color }}" style="font-size: 0.9rem; padding: 6px 12px;">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <!-- Order Items -->
                    <h6 style="font-weight: 600; color: #667eea;" class="mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-end" style="font-weight: 600;">
                                            ${{ number_format($item->subtotal, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="border-top: 2px solid #dee2e6;">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                    <td class="text-end text-success">${{ number_format($order->shipping, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                    <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr style="font-size: 1.1rem;">
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end" style="font-weight: 700; color: #667eea;">
                                        ${{ number_format($order->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->notes)
                        <hr>
                        <h6 style="font-weight: 600; color: #667eea;">Order Notes</h6>
                        <p class="text-muted">{{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-2">
                    <i class="la la-home"></i> Back to Home
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">
                    <i class="la la-print"></i> Print Order
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .navbar, footer, .btn, .alert {
            display: none !important;
        }
    }
</style>
@endsection
