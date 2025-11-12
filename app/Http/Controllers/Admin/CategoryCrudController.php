<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Add custom CSS for modern styling
        CRUD::addButtonFromView('top', 'custom_styles', 'custom_styles', 'beginning');
        
        CRUD::column('name')
            ->type('text')
            ->label('Category Name')
            ->wrapper([
                'class' => 'font-weight-bold text-primary',
                'style' => 'font-size: 1.1em;'
            ]);
            
        CRUD::column('description')
            ->type('text')
            ->label('Description')
            ->limit(150)
            ->wrapper([
                'class' => 'text-muted',
                'style' => 'font-style: italic;'
            ]);
            
        CRUD::column('created_by')
            ->type('select')
            ->label('Created By')
            ->entity('creator')
            ->attribute('name')
            ->model('App\Models\User')
            ->wrapper([
                'class' => 'badge badge-soft-info p-2'
            ]);
            
                    
        // Better visual settings
        CRUD::setResponsiveTable(true);
        CRUD::setActionsColumnPriority(10000);
        
        // Customize buttons
        CRUD::button('show')->stack('line')->remove();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CategoryRequest::class);
        
        // Add custom styling
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
                    
                    .form-control, .form-control-lg {
                        border-radius: 8px;
                        border: 2px solid #e9ecef;
                        padding: 0.75rem 1rem;
                        transition: all 0.3s ease;
                        background: white;
                    }
                    
                    .form-control:focus, .form-control-lg:focus {
                        border-color: #667eea;
                        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
                    }
                    
                    .form-control:disabled {
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
                    
                    .section-header hr {
                        display: none;
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
                        min-height: 120px;
                    }
                </style>
            ');
        
        // Category Information Section
        CRUD::field('category_info')
            ->type('custom_html')
            ->value('<div class="section-header"><h5><i class="la la-tag"></i> Category Information</h5></div>');
        
        CRUD::field('name')
            ->label('Category Name')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->attributes([
                'placeholder' => 'e.g., Electronics, Fashion, Home & Garden',
                'class' => 'form-control form-control-lg'
            ])
            ->hint('Enter a unique and descriptive name for this category');
            
        CRUD::field('description')
            ->type('textarea')
            ->label('Description')
            ->wrapper(['class' => 'form-group col-md-12'])
            ->attributes([
                'placeholder' => 'Provide a detailed description of this category...',
                'rows' => 4
            ])
            ->hint('Optional: Add details about what products belong in this category');
        
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
