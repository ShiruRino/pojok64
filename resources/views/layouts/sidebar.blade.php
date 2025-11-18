<div class="sidebar-brand d-flex flex-column align-items-center">
    <a href="{{ route('dashboard') }}">Resto</a>
</div>

<nav class="nav flex-column">
    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
    <form action="{{ route('logout') }}" method="POST" class="mt-3" onsubmit="return confirm('Are you sure you want to logout?')">
        @csrf
        <button class="btn btn-danger w-100" type="submit">Logout</button>
    </form>
</nav>
