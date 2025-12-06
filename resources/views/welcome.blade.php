<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lunar shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f1115;
            --panel: #131720;
            --muted: #9ca3af;
            --alert: #e872e8ff;
            --accent: #7bf1a8;
            --accent-strong: #5ce393;
            --text: #e5e7eb;
            --card: #151922;
            --border: #1f2532;
            --radius: 14px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Space Grotesk', 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(123,241,168,0.08), transparent 30%), radial-gradient(circle at 80% 0%, rgba(94,154,255,0.12), transparent 25%), var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 28px 18px 60px;
        }
        header {
            max-width: 1100px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }
        .brand {
            font-size: 1.2rem;
            letter-spacing: 0.02em;
            font-weight: 600;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .brand-badge {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7bf1a8, #5ac8fa);
            display: grid;
            place-items: center;
            color: #0b1016;
            font-weight: 700;
        }
        .cart-button {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 999px;
            padding: 10px 16px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: border-color 0.2s ease, transform 0.15s ease;
        }
        .cart-button:hover { border-color: var(--accent); transform: translateY(-1px); }
        .cart-count {
            background: var(--accent);
            color: #0c130f;
            min-width: 26px;
            height: 26px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            font-weight: 600;
            font-size: 0.9rem;
        }
        main {
            max-width: 1100px;
            margin: 0 auto;
        }
        .hero {
            display: grid;
            gap: 16px;
            margin-bottom: 28px;
        }
        .hero h1 {
            margin: 0;
            font-size: clamp(26px, 4vw, 36px);
            line-height: 1.1;
        }
        .hero p { margin: 0; color: var(--muted); max-width: 720px; }
        .product-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
        .product-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px;
            display: grid;
            gap: 12px;
            box-shadow: 0 18px 50px rgba(0,0,0,0.15);
        }
        .product-cover {
            aspect-ratio: 4 / 3;
            border-radius: 12px;
            background: radial-gradient(circle at 30% 30%, rgba(123,241,168,0.3), transparent 45%), #0b0f16;
            border: 1px solid var(--border);
            display: grid;
            place-items: center;
            color: var(--muted);
            font-size: 1.4rem;
        }
        .product-title { font-size: 1.05rem; margin: 0; font-weight: 600; }
        .product-desc { margin: 0; color: var(--muted); font-size: 0.95rem; }
        .price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
        .price { font-weight: 600; font-size: 1.1rem; }
        .add-btn {
            background: var(--accent);
            color: #0c130f;
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease;
            box-shadow: 0 12px 30px rgba(123, 241, 168, 0.25);
        }
        .add-btn:hover { transform: translateY(-1px); }
        .add-btn:active { transform: translateY(0); }
        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }
        .cart-overlay.open { opacity: 1; pointer-events: auto; }
        .cart-panel {
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: min(420px, 92vw);
            background: #0c1016;
            border-left: 1px solid var(--border);
            box-shadow: -10px 0 40px rgba(0,0,0,0.35);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: grid;
            grid-template-rows: auto 1fr auto;
            padding: 22px;
            gap: 16px;
            z-index: 20;
        }
        .cart-panel.open { transform: translateX(0); }
        .cart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .close-btn {
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            border-radius: 10px;
            padding: 8px 12px;
            cursor: pointer;
        }
        .cart-items {
            overflow-y: auto;
            padding-right: 6px;
        }
        .cart-item {
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 12px;
            display: grid;
            gap: 8px;
            margin-bottom: 10px;
            background: #0f131c;
        }
        .cart-item:last-child { margin-bottom: 0; }
        .cart-item header { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
        .cart-item h3 { margin: 0; font-size: 1rem; }
        .meta { color: var(--muted); font-size: 0.9rem; }
        .qty-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .qty-controls {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #0a0d13;
            border-radius: 999px;
            padding: 6px 10px;
            border: 1px solid var(--border);
        }
        .qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: var(--card);
            color: var(--text);
            cursor: pointer;
            font-weight: 700;
        }
        .qty-input {
            width: 32px;
            background: transparent;
            border: none;
            color: var(--text);
            text-align: center;
            font-weight: 600;
        }
        .remove-btn {
            border: none;
            background: transparent;
            color: var(--alert);
            cursor: pointer;
            font-size: 0.9rem;
        }
        .cart-summary {
            border-top: 1px solid var(--border);
            padding-top: 12px;
            display: grid;
            gap: 10px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--muted);
            font-size: 0.95rem;
        }
        .summary-row strong { color: var(--text); }
        .checkout-btn {
            background: linear-gradient(135deg, var(--accent), var(--accent-strong));
            color: #0c130f;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.2s ease;
            box-shadow: 0 14px 40px rgba(123,241,168,0.28);
        }
        .checkout-btn:hover { transform: translateY(-1px); }
        @media (max-width: 720px) {
            header { flex-direction: column; align-items: flex-start; }
            .cart-panel { padding: 18px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <div class="brand-badge">L</div>
            Lunar market
        </div>
        <button class="cart-button" id="cart-toggle" aria-label="Cart">
            <span>Cart</span>
            <span class="cart-count" id="cart-count">{{ $cart['quantity'] ?? 0 }}</span>
        </button>
    </header>

    <m  ain>
        <section class="hero">
            <h1>Minimal shop on Laravel + Lunar</h1>
            <p>Add products to the session cart without third-party frontend frameworks. The cart slides out from the right and immediately shows the totals.</p>
        </section>

        <section class="product-grid">
            @foreach ($products as $product)
                @php
                    $variant = $product->variants->first();
                    $price = $variant?->prices->firstWhere('currency_id', optional($currency)->id) ?? $variant?->prices->first();
                @endphp
                <article class="product-card">
                    <div class="product-cover">☆</div>
                    <h2 class="product-title">{{ (string) ($product->attribute_data['name'] ?? 'Product') }}</h2>
                    <p class="product-desc">{{ (string) ($product->attribute_data['description'] ?? '') }}</p>
                    <div class="price-row">
                        <span class="price">{{ $price?->price?->formatted() ?? '—' }}</span>
                        @if ($variant)
                            <button class="add-btn add-to-cart" data-variant="{{ $variant->id }}">Add to cart</button>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>
    </main>

    <div class="cart-overlay" id="cart-overlay"></div>

    <aside class="cart-panel" id="cart-panel" aria-label="Cart Panel">
        <div class="cart-header">
            <div>
                <div style="font-weight:600;">Cart</div>
                <div class="meta" id="cart-status">Add products to proceed to checkout</div>
            </div>
            <button class="close-btn" id="cart-close">Close</button>
        </div>

        <div class="cart-items" id="cart-items"></div>

        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <strong id="subtotal">{{ $cart['totals']['subTotal'] ?? '0.00' }}</strong>
            </div>
            <div class="summary-row">
                <span>Tax</span>
                <strong id="tax">{{ $cart['totals']['tax'] ?? '0.00' }}</strong>
            </div>
            <div class="summary-row">
                <span>Total</span>
                <strong id="total">{{ $cart['totals']['total'] ?? '0.00' }}</strong>
            </div>
            <button class="checkout-btn" type="button">Proceed to checkout</button>
        </div>
    </aside>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let cartState = @json($cart);

        const panel = document.getElementById('cart-panel');
        const overlay = document.getElementById('cart-overlay');
        const toggle = document.getElementById('cart-toggle');
        const closeBtn = document.getElementById('cart-close');
        const itemsEl = document.getElementById('cart-items');
        const countEl = document.getElementById('cart-count');
        const subtotalEl = document.getElementById('subtotal');
        const taxEl = document.getElementById('tax');
        const totalEl = document.getElementById('total');
        const statusEl = document.getElementById('cart-status');

        function openCart() {
            panel.classList.add('open');
            overlay.classList.add('open');
        }

        function closeCart() {
            panel.classList.remove('open');
            overlay.classList.remove('open');
        }

        toggle.addEventListener('click', openCart);
        overlay.addEventListener('click', closeCart);
        closeBtn.addEventListener('click', closeCart);

        function renderCart(cart) {
            cartState = cart;
            const lines = cart.lines || [];
            countEl.textContent = cart.quantity || 0;
            subtotalEl.textContent = cart.totals?.subTotal || '0.00';
            taxEl.textContent = cart.totals?.tax || '0.00';
            totalEl.textContent = cart.totals?.total || '0.00';
            statusEl.textContent = lines.length ? 'Products ready for checkout' : 'Cart is empty. Add products to proceed to checkout';

            itemsEl.innerHTML = '';

            if (!lines.length) {
                itemsEl.innerHTML = '<div class="meta">The cart is currently empty. Add products from the catalog.</div>';
                return;
            }

            lines.forEach((line) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'cart-item';
                wrapper.innerHTML = `
                    <header>
                        <h3>${line.name ?? 'Product'}</h3>
                        <button class="remove-btn" data-line="${line.id}">Remove</button>
                    </header>
                    <div class="meta">${line.sku || ''}</div>
                    <div class="qty-row">
                        <div class="qty-controls" data-line="${line.id}">
                            <button class="qty-btn" data-action="minus">−</button>
                            <input class="qty-input" type="number" min="1" max="99" value="${line.quantity}">
                            <button class="qty-btn" data-action="plus">+</button>
                        </div>
                        <div style="text-align:right;">
                            <div class="meta">Цена</div>
                            <strong>${line.unit_price || ''}</strong>
                        </div>
                        <div style="text-align:right;">
                            <div class="meta">Сумма</div>
                            <strong>${line.total || ''}</strong>
                        </div>
                    </div>
                `;

                wrapper.querySelector('.remove-btn').addEventListener('click', () => removeLine(line.id));

                const controls = wrapper.querySelector('.qty-controls');
                const input = controls.querySelector('.qty-input');

                controls.querySelector('[data-action="minus"]').addEventListener('click', () => {
                    const val = Math.max(1, parseInt(input.value, 10) - 1);
                    input.value = val;
                    updateLine(line.id, val);
                });

                controls.querySelector('[data-action="plus"]').addEventListener('click', () => {
                    const val = Math.min(99, parseInt(input.value, 10) + 1);
                    input.value = val;
                    updateLine(line.id, val);
                });

                input.addEventListener('change', () => {
                    const val = Math.min(99, Math.max(1, parseInt(input.value, 10) || 1));
                    input.value = val;
                    updateLine(line.id, val);
                });

                itemsEl.appendChild(wrapper);
            });
        }

        async function addToCart(variantId) {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ variant_id: variantId, quantity: 1 }),
            });

            if (!response.ok) {
                alert('The product could not be added to the cart');
                return;
            }

            const data = await response.json();
            renderCart(data);
            openCart();
        }

        async function updateLine(lineId, quantity) {
            const response = await fetch(`/cart/lines/${lineId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ quantity }),
            });

            if (!response.ok) {
                alert('Failed to update quantity');
                return;
            }

            renderCart(await response.json());
        }

        async function removeLine(lineId) {
            const response = await fetch(`/cart/lines/${lineId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken },
            });

            if (!response.ok) {
                alert('Failed to remove product from cart');
                return;
            }

            renderCart(await response.json());
        }

        document.querySelectorAll('.add-to-cart').forEach((btn) => {
            btn.addEventListener('click', () => addToCart(btn.dataset.variant));
        });

        renderCart(cartState);
    </script>
</body>
</html>
