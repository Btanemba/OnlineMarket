@php
    // Custom styles button - injects CSS for modern list view
@endphp

<style>
    /* Modern Category List Styling */
    .card {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: none;
        border-radius: 12px;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.5rem;
    }
    
    .card-header h1, .card-header h2 {
        color: white !important;
        font-weight: 600;
        margin: 0;
    }
    
    table.table {
        border-spacing: 0 10px;
        border-collapse: separate;
    }
    
    table.table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border: none;
        padding: 15px 20px;
    }
    
    table.table tbody tr {
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    
    table.table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    table.table tbody td {
        border: none;
        padding: 18px 20px;
        vertical-align: middle;
    }
    
    table.table tbody tr td:first-child {
        border-radius: 8px 0 0 8px;
    }
    
    table.table tbody tr td:last-child {
        border-radius: 0 8px 8px 0;
    }
    
    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .badge {
        border-radius: 6px;
        font-weight: 500;
        padding: 6px 12px;
    }
    
    .text-primary {
        color: #667eea !important;
    }
    
    /* Add button styling */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5568d3 0%, #653a8b 100%);
    }
</style>
