<div class="card">
    <div class="card-body">
        <h6 style="font-weight: 600; color: #667eea; margin-bottom: 15px;">
            <i class="la la-shopping-bag"></i> Order Items
        </h6>
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
                    @foreach($entry->orderItems as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->product)
                                    <br><small class="text-muted">SKU: #{{ $item->product_id }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">${{ number_format($item->price, 2) }}</td>
                            <td class="text-end"><strong>${{ number_format($item->subtotal, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot style="border-top: 2px solid #dee2e6;">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end">${{ number_format($entry->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                        <td class="text-end">${{ number_format($entry->shipping, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                        <td class="text-end">${{ number_format($entry->tax, 2) }}</td>
                    </tr>
                    <tr style="font-size: 1.1rem; background-color: #f8f9fa;">
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong style="color: #667eea;">${{ number_format($entry->total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
