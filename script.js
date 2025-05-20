let cart = [];
let selectedBranch = null;

function showProducts() {
    document.getElementById('main-content').querySelectorAll('section').forEach(section => section.style.display = 'none');
    document.getElementById('products').style.display = 'block';
    loadProducts();
}

function loadProducts() {
    const productList = document.getElementById('product-list');
    productList.innerHTML = ''; // Clear existing products

    const products = [
        { id: 1, name: 'Red Wine', price: 10, stock: 5, branch: 'Branch 1' },
        { id: 2, name: 'Whiskey', price: 25, stock: 0, branch: 'Branch 2' },
        { id: 3, name: 'Vodka', price: 15, stock: 3, branch: 'Branch 1' },
        { id: 4, name: 'Rum', price: 20, stock: 4, branch: 'Branch 3' },
        { id: 5, name: 'Champagne', price: 30, stock: 2, branch: 'Branch 2' },
        // Other products...
    ];

    const filteredProducts = products.filter(product => product.branch === selectedBranch || !selectedBranch);

    filteredProducts.forEach(product => {
        const card = document.createElement('div');
        card.innerHTML = `
            <h3>${product.name}</h3>
            <p>Price: $${product.price}</p>
            <input type="number" min="1" max="${product.stock}" value="1" id="quantity-${product.id}">
            <button onclick="addToCart(${product.id}, ${product.price}, ${product.stock})">
                Add to Cart
            </button>
        `;
        productList.appendChild(card);
    });
}

function setBranch() {
    selectedBranch = document.getElementById('branch-selector').value;
    loadProducts();
    closePopup();
}

function openPopup() {
    document.getElementById('branch-selection-popup').style.display = 'block';
}

function closePopup() {
    document.getElementById('branch-selection-popup').style.display = 'none';
}

function addToCart(id, price, stock) {
    if (!selectedBranch) {
        openPopup();
        return;
    }

    const quantityInput = document.getElementById(`quantity-${id}`);
    const quantity = parseInt(quantityInput.value);

    // Handle out of stock
    if (quantity > stock) {
        alert('This product is sold out. Please choose a lower quantity.');
        return;
    }

    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({ id, price, quantity });
    }
    alert('Item added to cart!');
}

function showCart() {
    document.getElementById('main-content').querySelectorAll('section').forEach(section => section.style.display = 'none');
    document.getElementById('cart').style.display = 'block';
    displayCartItems();
}

function displayCartItems() {
    const cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = ''; // Clear existing cart

    if (cart.length === 0) {
        cartItems.innerHTML = '<p>Your cart is empty.</p>';
        return;
    }

    cart.forEach(item => {
        cartItems.innerHTML += `<div>${item.quantity} x $${item.price} <button onclick="removeFromCart(${item.id})">Remove</button></div>`;
    });
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    displayCartItems();
}

function checkout() {
    // Implement checkout logic (e.g., form submission)
    alert('Proceeding to checkout...');
}
