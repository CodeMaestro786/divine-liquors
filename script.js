let cart = [];
let total = 0;

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
        window.scrollTo(0, activeSection.offsetTop); // Smooth scroll to the section
    }
}

function addToCart(productName, price) {
    const existingItem = cart.find(item => item.name === productName);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({ name: productName, price: price, quantity: 1 });
    }

    updateCart();
}

function updateCart() {
    const cartList = document.getElementById('cart-list');
    const totalElement = document.getElementById('cart-total');

    cartList.innerHTML = '';
    total = 0;

    cart.forEach(item => {
        total += item.price * item.quantity;
        const listItem = document.createElement('div');
        listItem.textContent = `${item.name} - R${item.price.toFixed(2)} x ${item.quantity}`;
        cartList.appendChild(listItem);
    });

    totalElement.textContent = `Total: R${total.toFixed(2)}`;
}

function proceedToCheckout() {
    showSection('checkout');
}

function fillDetails() {
    const name = document.getElementById('customer-name').value;
    const email = document.getElementById('customer-email').value;

    if (name && email) {
        showSection('select-location');
    } else {
        alert('Please fill in all fields.');
    }
}

function selectLocation(location) {
    const checkoutSummary = document.getElementById('checkout-summary');
    const paymentButton = document.getElementById('payment-options');

    checkoutSummary.innerHTML = `You have selected to pick up your order at: ${location}<br>Total to pay: R${total.toFixed(2)}`;
    paymentButton.style.display = 'block';
}

// Payment option handling
function paymentOption(option) {
    if (option === 'paynow') {
        alert('Proceeding to payment...');
        // Redirect or show payment options here
    } else {
        alert('You have chosen to pay later.');
        // Implement pay later logic here
    }
}
