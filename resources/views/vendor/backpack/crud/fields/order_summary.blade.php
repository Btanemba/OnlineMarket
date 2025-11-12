<div class="card mt-3">
    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <h6 class="mb-0" style="font-weight: 600;">
            <i class="la la-file-invoice"></i> Order Summary (Read-Only)
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2"><strong>Customer:</strong> {{ $entry->first_name }} {{ $entry->last_name }}</p>
                <p class="mb-2"><strong>Email:</strong> {{ $entry->email }}</p>
                <p class="mb-2"><strong>Phone:</strong> {{ $entry->phone }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Payment Method:</strong> {{ strtoupper($entry->payment_method) }}</p>
                <p class="mb-2"><strong>Order Date:</strong> {{ $entry->created_at->format('M d, Y H:i') }}</p>
                <p class="mb-2"><strong>Total Amount:</strong> <span style="color: #667eea; font-weight: 700; font-size: 1.2em;">${{ number_format($entry->total, 2) }}</span></p>
            </div>
        </div>
        
        <hr>
        
        <h6 style="font-weight: 600; margin-bottom: 10px;">Order Items</h6>
        <ul class="list-unstyled">
            @foreach($entry->orderItems as $item)
                <li class="mb-1">
                    <i class="la la-check-circle text-success"></i> 
                    <strong>{{ $item->product_name }}</strong> 
                    - Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }} 
                    = <strong>${{ number_format($item->subtotal, 2) }}</strong>
                </li>
            @endforeach
        </ul>
    </div>
</div>
