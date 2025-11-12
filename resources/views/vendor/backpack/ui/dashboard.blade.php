@extends(backpack_view('blank'))

@php
    $productCount = \App\Models\Product::count();
    $categoryCount = \App\Models\Category::count();
    $lowStockCount = \App\Models\Product::where('stock', '<', 10)->count();
    $totalValue = \App\Models\Product::sum(\DB::raw('price * stock'));
    
    // Order statistics
    $totalOrders = \App\Models\Order::count();
    $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
    $todayOrders = \App\Models\Order::whereDate('created_at', today())->count();
    $totalRevenue = \App\Models\Order::where('payment_status', 'paid')->sum('total');
@endphp

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4" style="color: #2c3e50; font-weight: 600;">
                <i class="la la-chart-line"></i> Dashboard Overview
            </h2>
        </div>
    </div>
    
    <div class="row">
        {{-- Total Products Card --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Products</p>
                            <h2 class="mb-0" style="font-weight: 700; font-size: 2.5rem;">{{ $productCount }}</h2>
                        </div>
                        <div>
                            <i class="la la-box" style="font-size: 3.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <a href="{{ backpack_url('product') }}" class="btn btn-light btn-sm mt-3" style="border-radius: 6px;">
                        View All <i class="la la-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Total Categories Card --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Categories</p>
                            <h2 class="mb-0" style="font-weight: 700; font-size: 2.5rem;">{{ $categoryCount }}</h2>
                        </div>
                        <div>
                            <i class="la la-tags" style="font-size: 3.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <a href="{{ backpack_url('category') }}" class="btn btn-light btn-sm mt-3" style="border-radius: 6px;">
                        View All <i class="la la-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Low Stock Alert Card --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Low Stock</p>
                            <h2 class="mb-0" style="font-weight: 700; font-size: 2.5rem;">{{ $lowStockCount }}</h2>
                        </div>
                        <div>
                            <i class="la la-exclamation-triangle" style="font-size: 3.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <p class="mb-0 mt-3" style="font-size: 0.85rem; opacity: 0.9;">
                        Products with stock &lt; 10
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Inventory Value Card --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Inventory Value</p>
                            <h2 class="mb-0" style="font-weight: 700; font-size: 2rem;">${{ number_format($totalValue, 2) }}</h2>
                        </div>
                        <div>
                            <i class="la la-dollar-sign" style="font-size: 3.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <p class="mb-0 mt-3" style="font-size: 0.85rem; opacity: 0.9;">
                        Total stock value
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Statistics Row --}}
    <div class="row mt-2">
        {{-- Total Orders Card --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Orders</p>
                            <h2 class="mb-0" style="font-weight: 700; font-size: 2.5rem;">{{ $totalOrders }}</h2>
                        </div>
                        <div>
                            <i class="la la-shopping-cart" style="font-size: 3.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <a href="{{ backpack_url('order') }}" class="btn btn-light btn-sm mt-3" style="border-radius: 6px;">
                        View All <i class="la la-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Pending Orders Card --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Pending Orders</p>
                            <h2 class="mb-0" style="font-weight: 700; font-size: 2.5rem;">{{ $pendingOrders }}</h2>
                        </div>
                        <div>
                            <i class="la la-clock" style="font-size: 3.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <p class="mb-0 mt-3" style="font-size: 0.85rem; opacity: 0.9;">
                        Requires attention
                    </p>
                </div>
            </div>
        </div>

        {{-- Today's Orders Card --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Today's Orders</p>
                            <h2 class="mb-0" style="font-weight: 700; font-size: 2.5rem;">{{ $todayOrders }}</h2>
                        </div>
                        <div>
                            <i class="la la-calendar-check" style="font-size: 3.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <p class="mb-0 mt-3" style="font-size: 0.85rem; opacity: 0.9;">
                        Orders placed today
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Revenue Card --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1" style="opacity: 0.9; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Revenue</p>
                            <h2 class="mb-0" style="font-weight: 700; font-size: 2rem;">${{ number_format($totalRevenue, 2) }}</h2>
                        </div>
                        <div>
                            <i class="la la-chart-line" style="font-size: 3.5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                    <p class="mb-0 mt-3" style="font-size: 0.85rem; opacity: 0.9;">
                        Paid orders only
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity Section --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white" style="border-radius: 12px 12px 0 0; border-bottom: 2px solid #f8f9fa;">
                    <h5 class="mb-0" style="color: #2c3e50; font-weight: 600;">
                        <i class="la la-clock"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ backpack_url('product/create') }}" class="btn btn-lg btn-outline-primary w-100" style="border-radius: 8px; border-width: 2px;">
                                <i class="la la-plus-circle"></i> Add New Product
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ backpack_url('category/create') }}" class="btn btn-lg btn-outline-success w-100" style="border-radius: 8px; border-width: 2px;">
                                <i class="la la-plus-circle"></i> Add New Category
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ backpack_url('order') }}" class="btn btn-lg btn-outline-warning w-100" style="border-radius: 8px; border-width: 2px;">
                                <i class="la la-shopping-cart"></i> View Orders
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ backpack_url('product') }}" class="btn btn-lg btn-outline-info w-100" style="border-radius: 8px; border-width: 2px;">
                                <i class="la la-list"></i> View All Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

