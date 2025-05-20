let cart = [];
let selectedBranch = null;

// Function to show home
function showHome() {
    document.getElementById('main-content').querySelectorAll('section').forEach(section => section.style.display = 'none');
    document.getElementById('home').style.display = 'block';
    loadFeaturedProducts();
}

// Function to display featured products
function loadFeaturedProducts() {
    const featuredList = document.getElementById('featured-list');
    featuredList.innerHTML = ''; // Clear existing products

    const products = [
        { id: 1, name: 'Red Wine', price: 10, stock: 5, branch: 'Branch 1', status: 'featured' },
        { id: 2, name: 'Whiskey', price: 25, stock: 0, branch: 'Branch 2', status: 'sold_out' },
        { id: 3, name: 'Vodka', price: 15, stock: 3, branch: 'Branch 1', status: '' },
        { id: 4, name: 'Rum', price: 20, stock: 4, branch: 'Branch 3', status: 'coming_soon' },
        { id: 5, name: 'Champagne', price: 30, stock: 2, branch: 'Branch 2', status: 'on_sale' },
    ];

    products.forEach(product => {
        const card = document.createElement('div');
        card.innerHTML = `
            <h3>${product.name}</h3>
            <p>Price: R${product.price}</p>
            ${product.stock === 0 ? '<p class="sold-out">Sold Out</p>' : ''}
            ${product.status === 'coming_soon' ? '<p class="coming-soon">Coming Soon</p>' : ''}
            ${product.stock > 0 && product.status !== 'coming_soon' ? `
            <input type="number" min="1" max="${product.stock}" value="1" id="quantity-${product.id}">
            <button onclick="addToCart(${product.id}, ${product.price}, ${product.stock})">
                Add to Cart
            </button>` : ''}
        `;
        featuredList.appendChild(card);
    });
}

// Function to show all products
function showProducts() {
    document.getElementById('main-content').querySelectorAll('section').forEach(section => section.style.display = 'none');
    document.getElementById('products').style.display = 'block';
    loadProducts();
}

// Function to load products dynamically
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
            <p>Price: R${product.price}</p>
            <input type="number" min="1" max="${product.stock}" value="1" id="quantity-${product.id}">
            <button onclick="addToCart(${product.id}, ${product.price}, ${product.stock})">
                ${product.stock > 0 ? 'Add to Cart' : 'Sold Out'}
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

// Function to open branch selection popup
function openPopup() {
    document.getElementById('branch-selection-popup').style.display = 'block';
}

// Close the branch selection popup
function closePopup() {
    document.getElementById('branch-selection-popup').style.display = 'none';
}

// Function to handle adding items to cart
function addToCart(id, price, stock) {
    if (!selectedBranch) {
        openPopup();
        return;
    }

    const quantityInput = document.getElementById(`quantity-${id}`);
    const quantity = parseInt(quantityInput.value);

    // Out of stock handling
    if (quantity > stock) {
        alert(`Select ${stock} or less as there is only ${stock} in stock.`);
        quantityInput.value = stock;
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

// Function to show the cart
function showCart() {
    document.getElementById('main-content').querySelectorAll('section').forEach(section => section.style.display = 'none');
    document.getElementById('cart').style.display = 'block';
    displayCartItems();
}

// Function to display cart items
function displayCartItems() {
    const cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = ''; // Clear existing cart

    if (cart.length === 0) {
        cartItems.innerHTML = '<p>Your cart is empty.</p>';
        return;
    }

    cart.forEach(item => {
        cartItems.innerHTML += `<div>${item.quantity} x R${item.price} <button onclick="removeFromCart(${item.id})">Remove</button></div>`;
    });
}

// Function to remove items from cart
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    displayCartItems();
}

// Function to handle checkout
function checkout() {
    alert('Proceeding to checkout...');
}

// Function to show the about page
function showAbout() {
    document.getElementById('main-content').querySelectorAll('section').forEach(section => section.style.display = 'none');
    document.getElementById('about').style.display = 'block';
}

// Function to show the contact page
function showContact() {
    document.getElementById('main-content').querySelectorAll('section').forEach(section => section.style.display = 'none');
    document.getElementById('contact').style.display = 'block';
}

const menuToggle = document.getElementById('mobile-menu');
const nav = document.querySelector('.nav');

menuToggle.addEventListener('click', () => {
    nav.classList.toggle('active');
});

function showSection(section) {
    const sections = document.querySelectorAll('.section');
    sections.forEach((sec) => {
        sec.style.display = 'none';
    });

    const activeSection = document.getElementById(section);
    if (activeSection) {
        activeSection.style.display = 'block';
    }
}
