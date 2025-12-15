document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.add-association-form');

    form.addEventListener('submit', function(event) {
        const name = document.getElementById('name').value;
        const phone = document.getElementById('phone').value;
        const location = document.getElementById('location').value;
        const email = document.getElementById('email').value;
        const id_category = document.getElementById('id_category').value;

        if (!name) {
            alert('Name is required.');
            event.preventDefault();
            return;
        }

        if (!phone) {
            alert('Phone is required.');
            event.preventDefault();
            return;
        }

        if (!location) {
            alert('Location is required.');
            event.preventDefault();
            return;
        }

        if (!email) {
            alert('Email is required.');
            event.preventDefault();
            return;
        }

        if (!validateEmail(email)) {
            alert('Please enter a valid email address.');
            event.preventDefault();
            return;
        }

        if (!id_category) {
            alert('Category is required.');
            event.preventDefault();
            return;
        }
    });

    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
});