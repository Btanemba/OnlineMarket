<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(12);
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::with('category')->latest()->take(6)->get();
        
        return view('frontend.index', compact('products', 'categories', 'featuredProducts'));
    }
    
    public function category($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $id)->with('category')->paginate(12);
        $otherCategories = Category::where('id', '!=', $id)->withCount('products')->get();
        
        return view('frontend.category', compact('products', 'category', 'otherCategories'));
    }
    
    public function product($id)
    {
        $product = Product::with(['category', 'creator'])->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        
        return view('frontend.product', compact('product', 'relatedProducts'));
    }
    
    public function trackOrder()
    {
        return view('frontend.track-order');
    }
    
    public function searchOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email',
        ]);
        
        $order = Order::where('order_number', $request->order_number)
            ->where('email', $request->email)
            ->with('orderItems')
            ->first();
        
        if (!$order) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Order not found. Please check your order number and email address.');
        }
        
        return view('frontend.track-order', compact('order'));
    }
}
