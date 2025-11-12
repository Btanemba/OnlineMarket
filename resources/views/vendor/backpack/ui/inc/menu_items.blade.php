{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Categories" icon="la la-tags" :link="backpack_url('category')" />
<x-backpack::menu-item title="Products" icon="la la-box" :link="backpack_url('product')" />
<x-backpack::menu-item title="Orders" icon="la la-shopping-cart" :link="backpack_url('order')" />

{{-- Logout --}}
<li class="nav-item logout-link">
    <a class="nav-link" href="{{ backpack_url('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="la la-sign-out nav-icon"></i> 
        <span>Logout</span>
    </a>
</li>

<form id="logout-form" action="{{ backpack_url('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
