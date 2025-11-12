<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Inject the custom styles (reused from categories)
        CRUD::addButtonFromView('top', 'custom_styles', 'custom_styles', 'beginning');

        // Product column with thumbnail + name + short description
        CRUD::column('name')
            ->type('closure')
            ->label('Product')
            ->function(function($entry) {
                $desc = isset($entry->description) ? \Illuminate\Support\Str::limit($entry->description, 60) : '';
                $thumb = '';
                if ($entry->images && is_array($entry->images) && count($entry->images)) {
                    $first = $entry->images[0];
                    $src = filter_var($first, FILTER_VALIDATE_URL) ? $first : asset('storage/' . ltrim($first, '/'));
                    $thumb = '<div style="width:56px;height:56px;overflow:hidden;border-radius:8px;margin-right:12px;flex:0 0 56px"><img src="' . $src . '" style="width:56px;height:56px;object-fit:cover;display:block" /></div>';
                } else {
                    $thumb = '<div style="width:56px;height:56px;border-radius:8px;margin-right:12px;background:#f1f3f5;flex:0 0 56px"></div>';
                }
                return '<div style="display:flex;align-items:center">' . $thumb . '<div><strong style="display:block;color:#2c3e50">' . e($entry->name) . '</strong><small style="color:#6c757d">' . e($desc) . '</small></div></div>';
            })
            ->escaped(false);

        // Category
        CRUD::column('category_id')
            ->type('select')
            ->label('Category')
            ->entity('category')
            ->attribute('name')
            ->model('App\\Models\\Category');

        // Price (formatted)
        CRUD::column('price')
            ->type('closure')
            ->label('Price')
            ->function(function($entry) {
                return '$' . number_format($entry->price, 2);
            })
            ->escaped(false);

        // Stock
        CRUD::column('stock')
            ->label('Stock');

        // Created by
        CRUD::column('created_by')
            ->type('select')
            ->label('Created By')
            ->entity('creator')
            ->attribute('name')
            ->model('App\\Models\\User')
            ->wrapper(['class' => 'text-muted']);

        // Created at small column
        CRUD::column('created_at')
            ->type('datetime')
            ->label('Created');

        CRUD::setResponsiveTable(true);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);
        
        // Add modern styling
        CRUD::field('form_styles')
            ->type('custom_html')
            ->value('
                <style>
                    .card {
                        border: none;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
                        border-radius: 12px;
                    }
                    
                    .card-header {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        border-radius: 12px 12px 0 0 !important;
                        padding: 1.5rem;
                    }
                    
                    .card-body {
                        padding: 2rem;
                        background: #f8f9fa;
                    }
                    
                    .form-group label {
                        font-weight: 600;
                        color: #495057;
                        font-size: 0.9rem;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        margin-bottom: 0.5rem;
                    }
                    
                    .form-control, .form-control-lg, select.form-control {
                        border-radius: 8px;
                        border: 2px solid #e9ecef;
                        padding: 0.75rem 1rem;
                        transition: all 0.3s ease;
                        background: white;
                    }
                    
                    .form-control:focus, .form-control-lg:focus, select.form-control:focus {
                        border-color: #667eea;
                        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
                    }
                    
                    .form-control:disabled, select.form-control:disabled {
                        background: #e9ecef;
                        color: #6c757d;
                    }
                    
                    .section-header {
                        background: white;
                        padding: 1rem 1.5rem;
                        border-radius: 8px;
                        margin: 1.5rem 0 1rem 0;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                    }
                    
                    .section-header h5 {
                        margin: 0;
                        color: #667eea;
                        font-weight: 600;
                        font-size: 1.1rem;
                    }
                    
                    .section-header i {
                        margin-right: 0.5rem;
                        font-size: 1.2rem;
                    }
                    
                    .btn-success {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        border: none;
                        border-radius: 8px;
                        padding: 0.75rem 2rem;
                        font-weight: 600;
                        transition: all 0.3s ease;
                    }
                    
                    .btn-success:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
                    }
                    
                    textarea.form-control {
                        min-height: 100px;
                    }
                    
                    input[type="number"].form-control {
                        font-weight: 600;
                    }
                </style>
            ');
        
        // Product Information Section
        CRUD::field('product_info')
            ->type('custom_html')
            ->value('<div class="section-header"><h5><i class="la la-box"></i> Product Information</h5></div>');
        
        CRUD::field('name')
            ->label('Product Name')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->attributes([
                'placeholder' => 'e.g., Wireless Bluetooth Headphones, Organic Cotton T-Shirt',
                'class' => 'form-control form-control-lg'
            ])
            ->hint('Enter a clear, descriptive product name');
        
        CRUD::field('category_id')
            ->type('select')
            ->label('Category')
            ->entity('category')
            ->attribute('name')
            ->model('App\Models\Category')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->hint('Select the category this product belongs to');
            
        CRUD::field('description')
            ->type('textarea')
            ->label('Product Description')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->attributes([
                'placeholder' => 'Describe the product features, materials, uses, and benefits...',
                'rows' => 5
            ])
            ->hint('Provide detailed information about the product');
        
        // Pricing & Inventory Section
        CRUD::field('pricing_info')
            ->type('custom_html')
            ->value('<div class="section-header"><h5><i class="la la-dollar-sign"></i> Pricing & Inventory</h5></div>');
        
        CRUD::field('price')
            ->type('number')
            ->label('Price')
            ->attributes(['step' => '0.01', 'placeholder' => '0.00'])
            ->wrapper(['class' => 'form-group col-md-6'])
            ->hint('Enter the product price in USD');
            
        CRUD::field('stock')
            ->type('number')
            ->label('Stock Quantity')
            ->attributes(['placeholder' => '0'])
            ->wrapper(['class' => 'form-group col-md-6'])
            ->hint('Available quantity in inventory');
        
        // Images Section
        CRUD::field('images_section')
            ->type('custom_html')
            ->value('<div class="section-header"><h5><i class="la la-images"></i> Product Images</h5></div>');
        
        CRUD::field('images')
            ->type('upload_multiple_images')
            ->label('Product Images')
            ->upload(true)
            ->disk('public')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->hint('Upload multiple images. First image will be the main display image.');
        
        // Audit Information Section
        CRUD::field('audit_info')
            ->type('custom_html')
            ->value('<div class="section-header"><h5><i class="la la-clock"></i> Audit Information</h5></div>');
            
        CRUD::field('created_by')
            ->type('select')
            ->label('Created By')
            ->entity('creator')
            ->attribute('name')
            ->model('App\Models\User')
            ->wrapper(['class' => 'form-group col-md-3'])
            ->attributes(['disabled' => 'disabled']);

         CRUD::field('created_at')
            ->type('text')
            ->label('Created At')
            ->wrapper(['class' => 'form-group col-md-3'])
            ->attributes(['disabled' => 'disabled']);
            
        CRUD::field('updated_by')
            ->type('select')
            ->label('Updated By')
            ->entity('updater')
            ->attribute('name')
            ->model('App\Models\User')
            ->wrapper(['class' => 'form-group col-md-3'])
            ->attributes(['disabled' => 'disabled']);
            
        CRUD::field('updated_at')
            ->type('text')
            ->label('Updated At')
            ->wrapper(['class' => 'form-group col-md-3'])
            ->attributes(['disabled' => 'disabled']);
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

   
}
