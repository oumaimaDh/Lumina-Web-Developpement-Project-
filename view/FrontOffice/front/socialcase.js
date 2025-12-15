document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit to ensure all elements are loaded
    setTimeout(function() {
        const form = document.getElementById('contact-form');
        if (!form) {
            console.warn('Form not found, skipping validation');
            return; // Exit if form doesn't exist (not on socialcase page)
        }
        
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        const emailInput = document.getElementById('email');
        const catgInput = document.getElementById('catg');
        const descInput = document.getElementById('desc');
        const locInput = document.getElementById('loc');

        // Check if all required inputs exist (association dropdown removed)
        if (!nameInput || !phoneInput || !emailInput || !catgInput || !descInput || !locInput) {
            console.warn('Some form elements are missing. Form validation may not work correctly.');
            return; // Exit if any required input is missing
        }

        form.addEventListener('submit', function(event) {
        let isValid = true;

        // Name validation
        if (nameInput.value.trim() === '') {
            alert('Please enter your name.');
            event.preventDefault();
            return;
        }

        // Phone validation
        if (phoneInput.value.trim() === '') {
            alert('Please enter your phone number.');
            event.preventDefault();
            return; 
        }

        // Email validation
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(emailInput.value.trim())) {
            alert('Please enter a valid email address.');
            event.preventDefault();
            return;
        }

        // Category validation
        if (catgInput.value === '' || catgInput.value === 'categorie') {
            alert('Please choose a problem category.');
            event.preventDefault();
            return;
        }

        // Association validation
        const assocInput = document.getElementById('assoc');
        if (assocInput && (assocInput.value === '' || assocInput.disabled)) {
            alert('Please choose an association for this category.');
            event.preventDefault();
            return;
        }

        // Description validation
        if (descInput.value.trim() === '') {
            alert('Please enter a description.');
            event.preventDefault();
            return;
        }

        // Location validation - must be selected from map
        if (locInput.value.trim() === '' || /^\s*\d+\.?\d*\s*[,|]\s*\d+\.?\d*\s*$/.test(locInput.value.trim())) {
            alert('Veuillez sélectionner un emplacement sur la carte avant de soumettre. / Please select a location on the map before submitting.');
            event.preventDefault();
            return;
        }

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
        });
    }, 100); // Small delay to ensure DOM is fully ready
});