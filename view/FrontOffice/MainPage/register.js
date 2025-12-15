const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirmPassword");
const birthdayInput = document.getElementById("birthday");
const ageFeedback = document.getElementById("ageFeedback");

const nameFeedback = document.getElementById("nameFeedback");
const emailFeedback = document.getElementById("emailFeedback");
const passwordFeedback = document.getElementById("passwordFeedback");
const confirmFeedback = document.getElementById("confirmFeedback");

const form = document.getElementById("registerForm");

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validatePassword(password) {
    const regex = /^(?=.*[0-9]).{6,}$/;
    return regex.test(password);
}

function calculateAge(date) {
    const birthday = new Date(date);
    const today = new Date();
    let age = today.getFullYear() - birthday.getFullYear();
    const m = today.getMonth() - birthday.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
        age--;
    }
    return age;
}

// Live validations
nameInput.addEventListener("input", () => {
    if (nameInput.value.trim().length >= 3) {
        nameFeedback.textContent = "Looks great ✓";
        nameFeedback.className = "feedback valid";
    } else {
        nameFeedback.textContent = "Name must be at least 3 characters";
        nameFeedback.className = "feedback invalid";
    }
});

emailInput.addEventListener("input", () => {
    if (validateEmail(emailInput.value.trim())) {
        emailFeedback.textContent = "Valid email ✓";
        emailFeedback.className = "feedback valid";
    } else {
        emailFeedback.textContent = "Invalid email format";
        emailFeedback.className = "feedback invalid";
    }
});

passwordInput.addEventListener("input", () => {
    if (validatePassword(passwordInput.value.trim())) {
        passwordFeedback.textContent = "Strong password ✓";
        passwordFeedback.className = "feedback valid";
    } else {
        passwordFeedback.textContent = "Min 6 chars & include a number";
        passwordFeedback.className = "feedback invalid";
    }
});

confirmInput.addEventListener("input", () => {
    if (confirmInput.value === passwordInput.value && confirmInput.value !== "") {
        confirmFeedback.textContent = "Passwords match ✓";
        confirmFeedback.className = "feedback valid";
    } else {
        confirmFeedback.textContent = "Passwords do not match";
        confirmFeedback.className = "feedback invalid";
    }
});

birthdayInput.addEventListener("change", () => {
    const age = calculateAge(birthdayInput.value);
    if (age > 0) {
        ageFeedback.textContent = `You are ${age} years old ✓`;
        ageFeedback.className = "feedback valid";
    } else {
        ageFeedback.textContent = "Please select a valid date";
        ageFeedback.className = "feedback invalid";
    }
});

// register.js - CORRIGEZ cette partie :
form.addEventListener("submit", async (e) => {
    e.preventDefault();
    
    // Frontend validation
    const validFields =
        nameFeedback.classList.contains("valid") &&
        emailFeedback.classList.contains("valid") &&
        passwordFeedback.classList.contains("valid") &&
        confirmFeedback.classList.contains("valid") &&
        birthdayInput.value !== "" &&
        roleSelect.value !== "";
  
    if (!validFields) {
        alert("❌ Please fix the errors before submitting.");
        return;
    }
  
    // CORRECTION : Utilisez FormData au lieu de URLSearchParams
    const formData = new FormData();
    formData.append('name', nameInput.value.trim());
    formData.append('email', emailInput.value.trim());
    formData.append('password', passwordInput.value);
  
    try {
        const response = await fetch('register.php', {
            method: 'POST',
            body: formData // Pas besoin de Content-Type avec FormData
        });
  
        const result = await response.text();
        
        if (result.startsWith('SUCCESS')) {
            alert("✅ Registration successful!");
            window.location.href = "login.html";
        } else {
            alert("❌ " + result.replace('ERROR: ', ''));
        }
    } catch (error) {
        alert("❌ Network error: " + error);
    }
  });

