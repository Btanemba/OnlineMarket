<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class OrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        CRUD::setModel(Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings('order', 'orders');
    }

    protected function setupListOperation()
    {
        // Custom styles button
        CRUD::button('custom_styles')->stack('line')->view('crud::buttons.custom_styles');

        // Order Number Column
        CRUD::column([
            'name' => 'order_number',
            'label' => 'Order #',
            'type' => 'text',
            'wrapper' => [
                'element' => 'strong',
                'style' => 'color: #667eea; font-size: 1.1em;'
            ]
        ]);

        // Customer Column (closure combining name and email)
        CRUD::column([
            'name' => 'customer',
            'label' => 'Customer',
            'type' => 'closure',
            'function' => function($entry) {
                return '<div>
                    <strong style="color: #2c3e50;">' . $entry->first_name . ' ' . $entry->last_name . '</strong><br>
                    <small style="color: #7f8c8d;"><i class="la la-envelope"></i> ' . $entry->email . '</small><br>
                    <small style="color: #7f8c8d;"><i class="la la-phone"></i> ' . $entry->phone . '</small>
                </div>';
            },
            'escaped' => false,
        ]);

        // Total Column
        CRUD::column([
            'name' => 'total',
            'label' => 'Total',
            'type' => 'closure',
            'function' => function($entry) {
                return '<strong style="color: #27ae60; font-size: 1.1em;">$' . number_format($entry->total, 2) . '</strong>';
            },
            'escaped' => false,
        ]);

        // Status Column with badge
        CRUD::column([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'closure',
            'function' => function($entry) {
                $colors = [
                    'pending' => '#f39c12',
                    'processing' => '#3498db',
                    'shipped' => '#9b59b6',
                    'delivered' => '#27ae60',
                    'cancelled' => '#e74c3c',
                ];
                $color = $colors[$entry->status] ?? '#95a5a6';
                return '<span class="badge" style="background: ' . $color . '; color: white; padding: 6px 12px; border-radius: 6px;">' 
                    . ucfirst($entry->status) . '</span>';
            },
            'escaped' => false,
        ]);

        // Payment Status Column
        CRUD::column([
            'name' => 'payment_status',
            'label' => 'Payment',
            'type' => 'closure',
            'function' => function($entry) {
                $colors = [
                    'pending' => '#f39c12',
                    'paid' => '#27ae60',
                    'failed' => '#e74c3c',
                ];
                $color = $colors[$entry->payment_status] ?? '#95a5a6';
                return '<span class="badge" style="background: ' . $color . '; color: white; padding: 6px 12px; border-radius: 6px;">' 
                    . ucfirst($entry->payment_status) . '</span>';
            },
            'escaped' => false,
        ]);

        // Payment Method
        CRUD::column([
            'name' => 'payment_method',
            'label' => 'Method',
            'type' => 'text',
            'wrapper' => [
                'element' => 'span',
                'style' => 'text-transform: uppercase; font-weight: 600;'
            ]
        ]);

        // Items Count
        CRUD::column([
            'name' => 'items_count',
            'label' => 'Items',
            'type' => 'closure',
            'function' => function($entry) {
                $count = $entry->orderItems->count();
                return '<span class="badge bg-info" style="font-size: 0.9em;">' . $count . ' items</span>';
            },
            'escaped' => false,
        ]);

        // Created At
        CRUD::column([
            'name' => 'created_at',
            'label' => 'Date',
            'type' => 'datetime',
            'format' => 'MMM D, YYYY',
        ]);
    }

    protected function setupShowOperation()
    {
        CRUD::column('order_number')->label('Order Number');
        
        // Customer Information Section
        CRUD::column([
            'name' => 'customer_info',
            'label' => 'Customer Information',
            'type' => 'custom_html',
            'value' => function($entry) {
                return view('vendor.backpack.crud.columns.order_customer_info', ['entry' => $entry])->render();
            }
        ]);

        // Order Items Section
        CRUD::column([
            'name' => 'order_items',
            'label' => 'Order Items',
            'type' => 'custom_html',
            'value' => function($entry) {
                return view('vendor.backpack.crud.columns.order_items', ['entry' => $entry])->render();
            }
        ]);

        CRUD::column('status')->type('text');
        CRUD::column('payment_status')->type('text');
        CRUD::column('payment_method')->type('text');
        CRUD::column('notes')->type('textarea');
        CRUD::column('created_at')->type('datetime');
        CRUD::column('updated_at')->type('datetime');
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(OrderRequest::class);

        $entry = $this->crud->getCurrentEntry();

        // Read-only fields (customer info)
        CRUD::field([
            'name' => 'order_info',
            'type' => 'custom_html',
            'value' => '<div class="alert alert-info">
                <i class="la la-info-circle"></i> 
                <strong>Order #' . $entry->order_number . '</strong> 
                - Customer information and order items cannot be modified.
            </div>'
        ]);

        // Status (editable)
        CRUD::field([
            'name' => 'status',
            'label' => 'Order Status',
            'type' => 'select_from_array',
            'options' => [
                'pending' => 'Pending',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ],
            'allows_null' => false,
            'hint' => 'Update the order status to track fulfillment progress.',
        ]);

        // Payment Status (editable)
        CRUD::field([
            'name' => 'payment_status',
            'label' => 'Payment Status',
            'type' => 'select_from_array',
            'options' => [
                'pending' => 'Pending',
                'paid' => 'Paid',
                'failed' => 'Failed',
            ],
            'allows_null' => false,
            'hint' => 'Mark as paid when payment is confirmed.',
        ]);

        // Admin Notes (editable)
        CRUD::field([
            'name' => 'notes',
            'label' => 'Order Notes',
            'type' => 'textarea',
            'hint' => 'Add internal notes about this order (visible to staff only).',
        ]);

        // Display order summary (read-only)
        CRUD::field([
            'name' => 'order_summary',
            'type' => 'custom_html',
            'value' => view('vendor.backpack.crud.fields.order_summary', ['entry' => $entry])->render()
        ]);
    }

    protected function setupDeleteOperation()
    {
        // Prevent deletion of non-cancelled orders
        CRUD::addClause('where', 'status', 'cancelled');
    }
}
