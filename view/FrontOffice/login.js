const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const emailFeedback = document.getElementById("emailFeedback");
const passwordFeedback = document.getElementById("passwordFeedback");
const form = document.getElementById("loginForm");

function validateEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

function validatePassword(password) {
  const regex = /^(?=.*[0-9]).{6,}$/;
  return regex.test(password);
}

emailInput.addEventListener("input", () => {
  const email = emailInput.value.trim();
  if (email === "") {
    emailFeedback.textContent = "";
    emailFeedback.className = "feedback";
  } else if (validateEmail(email)) {
    emailFeedback.textContent = "Valid email ✓";
    emailFeedback.className = "feedback valid";
  } else {
    emailFeedback.textContent = "Invalid email format";
    emailFeedback.className = "feedback invalid";
  }
});

passwordInput.addEventListener("input", () => {
  const password = passwordInput.value.trim();
  if (password === "") {
    passwordFeedback.textContent = "";
    passwordFeedback.className = "feedback";
  } else if (validatePassword(password)) {
    passwordFeedback.textContent = "Strong password ✓";
    passwordFeedback.className = "feedback valid";
  } else {
    passwordFeedback.textContent = "Password must be 6+ chars and include a number";
    passwordFeedback.className = "feedback invalid";
  }
});

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const email = emailInput.value.trim();
  const password = passwordInput.value.trim();
  const remember = document.getElementById('remember').checked ? 'on' : 'off';

  if (!validateEmail(email) || !password) {
    alert("❌ Please enter valid email and password.");
    return;
  }

  try {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    formData.append('remember', remember);  // Send remember flag

    const response = await fetch('login.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.text();
    console.log('Login result:', result);

    if (result.includes('SUCCESS')) {
      let userData = null;
      const parts = result.split(':');
      
      if (parts.length >= 3) {
          try {
              userData = JSON.parse(parts.slice(2).join(':'));
          } catch (e) {
              console.error('JSON parse error:', e);
          }
      }
  
      if (userData) {
          storeUserData(userData);
          
          if (result.includes('SUCCESS:ADMIN')) {
              alert("✅ Welcome Admin!");
              setTimeout(() => window.location.href = '../BackOffice/index.html', 500);
          } else {
              alert("✅ Login successful!");
              setTimeout(() => window.location.href = 'newindex.html', 500);
          }

          if (document.getElementById('remember').checked) {
            localStorage.setItem('rememberedEmail', email);
            localStorage.setItem('rememberedName', userData.username || email.split('@')[0]);
        } else {
            localStorage.removeItem('rememberedEmail');
            localStorage.removeItem('rememberedName');
        }
      }
  } else {
      alert('❌ ' + result.replace('ERROR: ', ''));
  } 
  } catch (error) {
    console.error('Error:', error);
    alert('❌ Network error. Please try again.');
  }
});

// Function to store user data in localStorage
function storeUserData(userData) {
  // Store in localStorage
  localStorage.setItem('currentUser', JSON.stringify(userData));
  
  // Trigger update for authManager (even if it loads later)
  window.dispatchEvent(new Event('authUpdated'));
}
// Function to check admin credentials (optional - can be removed)
function checkAdminCredentials(email, password) {
  const adminCredentials = [
    { email: 'admin@lumina.com', password: 'admin123' },
    { email: 'administrator@lumina.com', password: 'admin123' }
  ];

  return adminCredentials.some(admin => 
    admin.email === email && admin.password === password
  );
}

class HelpingCaptcha {
  constructor() {
      this.questions = [
          {
              question: "Which action would help someone in need right now?",
              correct: "Share a warm meal",
              options: ["Share a warm meal", "Ignore their request", "Take a selfie", "Watch TV"]
          },
          {
              question: "What's the most helpful thing for an elderly neighbor?",
              correct: "Help with groceries",
              options: ["Help with groceries", "Play loud music", "Avoid eye contact", "Complain about noise"]
          },
          {
              question: "How can you support a struggling student?",
              correct: "Offer to tutor them",
              options: ["Offer to tutor them", "Make fun of them", "Ignore their questions", "Take their notes"]
          },
          {
              question: "What helps a homeless person survive winter?",
              correct: "Donate warm clothes",
              options: ["Donate warm clothes", "Walk faster past them", "Take photos", "Complain about the cold"]
          },
          {
              question: "How to support mental health awareness?",
              correct: "Listen without judgment",
              options: ["Listen without judgment", "Tell them to cheer up", "Avoid the topic", "Share memes"]
          }
      ];

      this.currentQuestion = null;
      this.selectedOption = null;
      this.isVerified = false;

      this.initialize();
  }

  initialize() {
      this.loadQuestion();
      this.setupEventListeners();
  }

  loadQuestion() {
      const randomIndex = Math.floor(Math.random() * this.questions.length);
      this.currentQuestion = this.questions[randomIndex];
      
      const questionElement = document.getElementById('captchaQuestion');
      const optionsContainer = document.getElementById('helpOptions');
      const verifyBtn = document.getElementById('verifyBtn');
      const feedback = document.getElementById('captchaFeedback');

      questionElement.textContent = this.currentQuestion.question;
      optionsContainer.innerHTML = '';
      verifyBtn.disabled = true;
      feedback.innerHTML = '';
      this.selectedOption = null;
      this.isVerified = false;

      // Shuffle options
      const shuffledOptions = [...this.currentQuestion.options].sort(() => Math.random() - 0.5);

      shuffledOptions.forEach(option => {
          const optionElement = document.createElement('div');
          optionElement.className = 'help-option';
          optionElement.textContent = option;
          optionElement.addEventListener('click', () => this.selectOption(optionElement, option));
          optionsContainer.appendChild(optionElement);
      });
  }

  selectOption(element, option) {
      if (this.isVerified) return;

      // Remove selected class from all options
      document.querySelectorAll('.help-option').forEach(opt => {
          opt.classList.remove('selected');
      });

      // Add selected class to clicked option
      element.classList.add('selected');
      this.selectedOption = option;

      // Enable verify button
      document.getElementById('verifyBtn').disabled = false;
  }

  verify() {
      if (!this.selectedOption) return;

      const isCorrect = this.selectedOption === this.currentQuestion.correct;
      const feedback = document.getElementById('captchaFeedback');
      const verifyBtn = document.getElementById('verifyBtn');

      // Mark options as correct/incorrect
      document.querySelectorAll('.help-option').forEach(option => {
          option.classList.remove('correct', 'incorrect');
          if (option.textContent === this.currentQuestion.correct) {
              option.classList.add('correct');
          } else if (option.textContent === this.selectedOption && !isCorrect) {
              option.classList.add('incorrect');
          }
      });

      if (isCorrect) {
          feedback.innerHTML = '<span class="success-message heart-animation"><i class="fas fa-heart"></i> Thank you for caring! Verification passed.</span>';
          this.isVerified = true;
          verifyBtn.disabled = true;
          
          // Trigger success event
          this.onSuccess();
      } else {
          feedback.innerHTML = '<span class="error-message"><i class="fas fa-heart-broken"></i> That wouldn\'t be very helpful. Try again!</span>';
          verifyBtn.disabled = true;
          this.selectedOption = null;
      }
  }

  setupEventListeners() {
      document.getElementById('verifyBtn').addEventListener('click', () => this.verify());
      document.getElementById('refreshBtn').addEventListener('click', () => this.loadQuestion());
  }

  onSuccess() {
      // This can be customized to trigger form submission or other actions
      console.log('CAPTCHA verified successfully!');
      
      // Dispatch custom event for integration
      const event = new CustomEvent('captchaSuccess', { 
          detail: { verified: true, timestamp: new Date() }
      });
      document.dispatchEvent(event);
  }

  // Public method to check verification status
  isVerified() {
      return this.isVerified;
  }
}

// === SMART AUTO-LOGIN: Only auto-login on index.html, NOT on login.html ===
document.addEventListener('DOMContentLoaded', async () => {
  new HelpingCaptcha();

  const urlParams = new URLSearchParams(window.location.search);
  const isLoginPage = window.location.pathname.includes('login.html');

  // If user explicitly wants to switch account → do NOTHING
  if (urlParams.has('switch')) {
    localStorage.removeItem('rememberedEmail');
    localStorage.removeItem('rememberedName');
    return;
  }

  // Only auto-login if we're NOT on the login page
  if (!isLoginPage) {
    try {
      const response = await fetch('auto_login.php');
      const result = await response.text();

      if (result.includes('SUCCESS')) {
        let userData = null;
        const parts = result.split(':');
        if (parts.length >= 3) {
          try {
            userData = JSON.parse(parts.slice(2).join(':'));
          } catch (e) {
            console.error('JSON parse error:', e);
          }
        }
        
        if (userData) {
          storeUserData(userData);
          // We're already on index.html or dashboard → just update navbar
          window.dispatchEvent(new Event('authUpdated'));
        }
      }
    } catch (error) {
      console.error('Auto-login error:', error);
    }
    return; // Stop here if not on login page
  }

  // === ONLY ON LOGIN PAGE: Show remembered email + option to switch ===
  const rememberedEmail = localStorage.getItem('rememberedEmail');
  const rememberedName = localStorage.getItem('rememberedName');

  if (rememberedEmail && rememberedEmail.trim() !== '') {
    emailInput.value = rememberedEmail;

    // Show validation
    emailFeedback.textContent = "Valid email ✓";
    emailFeedback.className = "feedback valid";

    // Insert beautiful "Welcome back" banner
    const banner = document.createElement('div');
    banner.className = 'remembered-user-banner';
    banner.innerHTML = `
      <div style="margin: 15px 0; padding: 16px; background: linear-gradient(135deg, #e3f2fd, #bbdefb); 
                  border-radius: 12px; border-left: 5px solid #1976d2; font-family: 'Poppins', sans-serif;">
        <p style="margin: 0 0 10px 0; font-size: 1rem; color: #1565c0;">
          <i class="fas fa-user-circle" style="margin-right: 8px;"></i>
          <strong>Welcome back${rememberedName ? ', ' + rememberedName : ''}!</strong>
        </p>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
          <button type="button" id="continueAsUser" class="btn" style="background: #1976d2; padding: 10px 20px; font-size: 0.95rem;">
            Continue as ${rememberedName || rememberedEmail}
          </button>
          <a href="?switch" style="color: #d32f2f; text-decoration: underline; align-self: center; font-size: 0.9rem;">
            Not you? Log in with another account
          </a>
        </div>
      </div>
    `;

    // Insert banner above the form
    document.querySelector('.form-panel').insertBefore(banner, document.querySelector('.form-header').nextSibling);

    // Auto-login when clicking "Continue"
    document.getElementById('continueAsUser')?.addEventListener('click', async () => {
      try {
        const response = await fetch('auto_login.php');
        const result = await response.text();
        if (result.includes('SUCCESS')) {
          const parts = result.split(':');
          const userData = JSON.parse(parts.slice(2).join(':'));
          storeUserData(userData);
          window.location.href = 'newindex.html';
        }
      } catch (err) {
        alert('Auto-login failed. Please log in manually.');
      }
    });

    passwordInput.focus();
  }
});

// === EMAIL SUGGESTION DROPDOWN (Like Gmail) ===
const rememberedEmail = localStorage.getItem('rememberedEmail');
const rememberedName = localStorage.getItem('rememberedName');

if (rememberedEmail && !document.querySelector('.remembered-user-banner')) {
    const emailGroup = emailInput.parentElement;
    emailGroup.style.position = 'relative';

    const dropdown = document.createElement('div');
    dropdown.className = 'email-suggestion-dropdown';
    dropdown.innerHTML = `
        <div style="padding: 12px 16px; background: white; border: 1px solid #ddd; border-top: none; 
                    border-radius: 0 0 12px 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); cursor: pointer;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-user-circle" style="font-size: 2rem; color: #1976d2;"></i>
                <div>
                    <div style="font-weight: 600;">${rememberedName || rememberedEmail}</div>
                    <div style="font-size: 0.9rem; color: #666;">${rememberedEmail}</div>
                </div>
            </div>
        </div>
    `;
    dropdown.style.display = 'none';
    emailGroup.appendChild(dropdown);

    const showDropdown = () => {
        if (emailInput.value === '' || emailInput.value === rememberedEmail) {
            dropdown.style.display = 'block';
        }
    };
    const hideDropdown = () => dropdown.style.display = 'none';

    emailInput.addEventListener('focus', showDropdown);
    emailInput.addEventListener('input', () => {
        if (rememberedEmail.toLowerCase().includes(emailInput.value.toLowerCase())) {
            showDropdown();
        } else {
            hideDropdown();
        }
    });

    dropdown.addEventListener('click', () => {
        emailInput.value = rememberedEmail;
        emailFeedback.textContent = "Valid email";
        emailFeedback.className = "feedback valid";
        hideDropdown();
        passwordInput.focus();
    });

    document.addEventListener('click', (e) => {
        if (!emailGroup.contains(e.target)) hideDropdown();
    });

    // Show on page load
    setTimeout(showDropdown, 300);
}