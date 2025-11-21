<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}" href="{{ route('user.home') }}">
        <i class="bi bi-house-door-fill me-1"></i>Home
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('user.menu') ? 'active' : '' }}" href="{{ route('user.menu') }}">
        <i class="bi bi-book-fill me-1"></i>Menu
    </a>
</li>

<script>
    (function() {
        function addDynamicNavItems() {
            const navbar = document.querySelector('.navbar-nav');
            if (!navbar) {
                console.error('Navbar not found');
                return;
            }

            const code = localStorage.getItem('guest_order_code');
            const submitted = localStorage.getItem('submitted');

            console.log('Code:', code, 'Submitted:', submitted); // Debug log

            // Remove any existing dynamic nav items to prevent duplicates
            const existingDynamic = navbar.querySelectorAll('.dynamic-nav-item');
            existingDynamic.forEach(item => item.remove());

            if (!code && !submitted) {
                // Show Order button
                const item = document.createElement('li');
                item.classList.add('nav-item', 'dynamic-nav-item');

                const url = `{{ route('user.order.create') }}`;
                const isActive = window.location.href.includes(url) ? 'active' : '';

                item.innerHTML = `
                    <a class="nav-link ${isActive}" href="${url}">
                        <i class="bi bi-cart-plus-fill me-1"></i>Order
                    </a>
                `;
                navbar.appendChild(item);
                console.log('Order button added'); // Debug log
            }

            if (code && submitted) {
                // Show Your Orders button
                const item = document.createElement('li');
                item.classList.add('nav-item', 'dynamic-nav-item');

                const url = `{{ route('user.order.index', ':code') }}`.replace(':code', code);
                const isActive = window.location.href.includes('order') ? 'active' : '';

                item.innerHTML = `
                    <a class="nav-link ${isActive}" href="${url}">
                        <i class="bi bi-bag-check-fill me-1"></i>Your Orders
                    </a>
                `;
                navbar.appendChild(item);
                console.log('Your Orders button added'); // Debug log
            }
        }

        // Try immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', addDynamicNavItems);
        } else {
            addDynamicNavItems();
        }

        // Also try after a small delay as fallback
        setTimeout(addDynamicNavItems, 100);
    })();
</script>
