let cart = [];
let total = 0;

// Toggle mobile menu visibility
const menuToggle = document.getElementById('mobile-menu');
const nav = document.querySelector('.nav');

menuToggle.addEventListener('click', () => {
    nav.classList.toggle('active');
});

// Show and hide sections
function showSection(section) {
    const sections = document.querySelectorAll('.section');
    sections.forEach((sec) => {
        sec.style.display = 'none'; // Hide all sections
    });

    const activeSection = document.getElementById(section);
    if (activeSection) {
        activeSection.style.display = 'block'; // Show the selected section
        window.scrollTo(0, activeSection.offsetTop); // Smooth scroll to the section
    }
}

// Add item to the cart
function addToCart(productName, price) {
    const existingItem = cart.find(item => item.name === productName); // Check if product is already in cart
    
    if (existingItem) {
        existingItem.quantity++; // Increment quantity if it exists
    } else {
        cart.push({ name: productName, price: price, quantity: 1 }); // Add new item
    }

    updateCart(); // Update cart display
}

// Update the cart display and total
function updateCart() {
    const cartList = document.getElementById('cart-list');
    const totalElement = document.getElementById('cart-total');

    cartList.innerHTML = ''; // Clear current cart list
    total = 0;

    cart.forEach(item => {
        total += item.price * item.quantity; // Calculate total
        const listItem = document.createElement('div'); // Create a new list item
        listItem.textContent = `${item.name} - R${item.price.toFixed(2)} x ${item.quantity}`; // Format the display
        cartList.appendChild(listItem); // Add list item to the cart list
    });

    totalElement.textContent = `Total: R${total.toFixed(2)}`; // Display total
}

// Proceed to the checkout section
function proceedToCheckout() {
    if (cart.length === 0) {
        alert('Your cart is empty! Please add items to your cart before proceeding to checkout.'); // Alert if cart is empty
        return;
    }
    showSection('checkout'); // Show checkout section
}

// Validate customer details and proceed to location selection
function fillDetails() {
    const name = document.getElementById('customer-name').value;
    const email = document.getElementById('customer-email').value;

    if (name && email) {
        showSection('select-location'); // Go to location selection
    } else {
        alert('Please fill in all fields.'); // Alert for missing fields
    }
}

// Select pickup location and display checkout summary
function selectLocation(location) {
    const checkoutSummary = document.getElementById('checkout-summary');
    const paymentButton = document.getElementById('payment-options');

    checkoutSummary.innerHTML = `You have selected to pick up your order at: ${location}<br>Total to pay: R${total.toFixed(2)}`;
    paymentButton.style.display = 'block'; // Show payment options
}

// Handle payment option selection
function paymentOption(option) {
    if (option === 'paynow') {
        alert('Proceeding to payment...');
        // Here, include logic for processing payments, e.g., redirecting to payment gateway
    } else {
        alert('You have chosen to pay later.');
        // Implement 'pay later' logic here
    }
}
