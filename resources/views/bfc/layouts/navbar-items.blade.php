<nav class="nav-item">
<a href="{{ route('user.home') }}">Home</a>
</nav>
<nav class="nav-item">
<a href="{{ route('user.menu') }}">Menu</a>
</nav>
{{-- <nav class="nav-item">
<a href="{{ route('user.order.create') }}">Order</a>
</nav> --}}

<script>
    const navbar = document.querySelector('.navbar-nav');
    const code = localStorage.getItem('guest_order_code');
    const submitted = localStorage.getItem('submitted');
    if(!code && !submitted){
        const item = document.createElement('nav');
        item.classList.add('nav-item');

        const url = `{{ route('user.order.create') }}`;

        item.innerHTML = `<a href="${url}">Order</a>`;
        navbar.appendChild(item);
    }
    if (code && submitted) {
        const item = document.createElement('nav');
        item.classList.add('nav-item');

        const url = `{{ route('user.order.index', ':code') }}`.replace(':code', code);

        item.innerHTML = `<a href="${url}">Your Orders</a>`;
        navbar.appendChild(item);
    }
</script>

