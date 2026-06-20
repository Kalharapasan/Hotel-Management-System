// Hotel Management System - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if needed
    initializeTooltips();
    
    // Add event listeners
    setupFormValidation();
    setupDatePickers();
});

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('hover', function() {
            const tooltip = this.getAttribute('data-tooltip');
            console.log(tooltip);
        });
    });
}

/**
 * Setup form validation
 */
function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Validate form
 */
function validateForm(form) {
    const email = form.querySelector('input[type="email"]');
    if (email) {
        if (!isValidEmail(email.value)) {
            alert('Please enter a valid email');
            return false;
        }
    }

    const password = form.querySelector('input[type="password"]');
    if (password) {
        if (password.value.length < 6) {
            alert('Password must be at least 6 characters');
            return false;
        }
    }

    return true;
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Setup date pickers
 */
function setupDatePickers() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.name === 'check_out') {
                const checkInInput = document.querySelector('input[name="check_in"]');
                if (checkInInput && checkInInput.value >= this.value) {
                    alert('Check-out date must be after check-in date');
                    this.value = '';
                }
            }
        });
    });
}

/**
 * Toggle element visibility
 */
function toggleElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = element.style.display === 'none' ? 'block' : 'none';
    }
}

/**
 * Show loading indicator
 */
function showLoading() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'block';
    }
}

/**
 * Hide loading indicator
 */
function hideLoading() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'none';
    }
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return '$' + amount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Calculate total price
 */
function calculateTotal(pricePerNight, nights) {
    return pricePerNight * nights;
}

/**
 * Confirm action
 */
function confirmAction(message) {
    return confirm(message);
}

/**
 * Alert user
 */
function alertUser(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = message;
    document.body.insertBefore(alertDiv, document.body.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

/**
 * Export functions to global scope
 */
window.Hotel = {
    toggleElement,
    showLoading,
    hideLoading,
    formatCurrency,
    calculateTotal,
    confirmAction,
    alertUser,
    validateForm,
    isValidEmail
};
