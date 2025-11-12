<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 style="font-weight: 600; color: #667eea; margin-bottom: 15px;">
                    <i class="la la-user"></i> Customer Information
                </h6>
                <p class="mb-2"><strong>Name:</strong> {{ $entry->first_name }} {{ $entry->last_name }}</p>
                <p class="mb-2"><strong>Email:</strong> <a href="mailto:{{ $entry->email }}">{{ $entry->email }}</a></p>
                <p class="mb-2"><strong>Phone:</strong> <a href="tel:{{ $entry->phone }}">{{ $entry->phone }}</a></p>
            </div>
            <div class="col-md-6">
                <h6 style="font-weight: 600; color: #667eea; margin-bottom: 15px;">
                    <i class="la la-map-marker"></i> Shipping Address
                </h6>
                <p class="mb-1">{{ $entry->address }}</p>
                <p class="mb-1">{{ $entry->city }}, {{ $entry->state }} {{ $entry->zip }}</p>
                <p class="mb-1">{{ $entry->country }}</p>
            </div>
        </div>
    </div>
</div>
