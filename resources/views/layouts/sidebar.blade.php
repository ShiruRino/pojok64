<div class="sidebar-brand d-flex flex-column align-items-center">
    <a href="{{ route('dashboard') }}">BFC</a>
</div>

<nav class="nav flex-column">
    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
    @if(Auth::user()->role == 'admin')
    <a href="{{ route('products.index') }}" class="nav-link">Products</a>
    @endif
    @if (Auth::user()->role == 'cashier')
    <a href="{{ route('orders.index') }}" class="nav-link">Orders</a>
    <a href="{{ route('transactions.index') }}" class="nav-link">Transactions</a>
    @endif
    <form action="{{ route('logout') }}" method="POST" class="mt-3" onsubmit="return confirm('Are you sure you want to logout?')">
        @csrf
        <button class="btn btn-danger w-100" type="submit">Logout</button>
    </form>
</nav>
