class PasswordReset {
    constructor() {
        this.currentStep = 1;
        this.userEmail = '';
        this.verificationCode = '';
        this.timerInterval = null;
        this.timeLeft = 180;
        
        this.initializeEventListeners();
        this.setupCodeInputs();
    }

    initializeEventListeners() {
        document.getElementById('emailForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleEmailSubmit();
        });

        document.getElementById('codeForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleCodeVerification();
        });

        document.getElementById('passwordForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handlePasswordReset();
        });

        document.getElementById('resendCode').addEventListener('click', (e) => {
            e.preventDefault();
            this.resendVerificationCode();
        });

        document.getElementById('newPassword').addEventListener('input', (e) => {
            this.checkPasswordStrength(e.target.value);
        });

        document.getElementById('confirmPassword').addEventListener('input', (e) => {
            this.checkPasswordMatch();
        });
    }

    setupCodeInputs() {
        const codeInputs = document.querySelectorAll('.code-input');
        
        codeInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                // Only allow numbers
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 1) {
                    value = value.charAt(0); // Take only first character
                }
                e.target.value = value;
                
                // Auto-focus next input
                if (value && index < codeInputs.length - 1) {
                    codeInputs[index + 1].focus();
                }
                
                this.checkCodeCompletion();
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    codeInputs[index - 1].focus();
                }
                
                // Allow only numbers
                if (!/^\d$|Backspace|Delete|ArrowLeft|ArrowRight|Tab/.test(e.key)) {
                    e.preventDefault();
                }
            });
            
            // Paste handling
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').replace(/\D/g, '');
                if (pasteData.length === 6) {
                    const digits = pasteData.split('');
                    codeInputs.forEach((input, idx) => {
                        if (idx < 6) input.value = digits[idx] || '';
                    });
                    this.checkCodeCompletion();
                }
            });
        });
    }

    checkCodeCompletion() {
        const codeInputs = document.querySelectorAll('.code-input');
        const code = Array.from(codeInputs).map(input => input.value).join('');
        const verifyBtn = document.getElementById('verifyCodeBtn');
        
        verifyBtn.disabled = code.length !== 6;
        this.verificationCode = code;
    }

    async handleEmailSubmit() {
        const email = document.getElementById('resetEmail').value.trim();
        const feedback = document.getElementById('emailFeedback');
        
        if (!this.validateEmail(email)) {
            this.showFeedback(feedback, "Please enter a valid email address", false);
            return;
        }
        
        try {
            this.setButtonLoading('emailForm', true);
            
            const formData = new FormData();
            formData.append('email', email);
            formData.append('action', 'send_code');
            
            const response = await fetch('forgot-password.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.text();
            
            if (result === 'SUCCESS') {
                this.userEmail = email;
                this.moveToStep(2);
                this.startTimer();
                this.showFeedback(feedback, "Verification code sent!", true);
            } else {
                this.showFeedback(feedback, result.replace('ERROR: ', ''), false);
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showFeedback(feedback, "Network error. Please try again.", false);
        } finally {
            this.setButtonLoading('emailForm', false);
        }
    }

    async handleCodeVerification() {
        const feedback = document.getElementById('codeFeedback');
        
        try {
            this.setButtonLoading('codeForm', true);
            
            const formData = new FormData();
            formData.append('email', this.userEmail);
            formData.append('code', this.verificationCode);
            formData.append('action', 'verify_code');
            
            const response = await fetch('forgot-password.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.text();
            
            if (result === 'SUCCESS') {
                this.moveToStep(3);
                this.stopTimer();
            } else {
                this.showFeedback(feedback, result.replace('ERROR: ', ''), false);
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showFeedback(feedback, "Network error. Please try again.", false);
        } finally {
            this.setButtonLoading('codeForm', false);
        }
    }

    async handlePasswordReset() {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const feedback = document.getElementById('confirmFeedback');
        
        if (newPassword !== confirmPassword) {
            this.showFeedback(feedback, "Passwords do not match", false);
            return;
        }
        
        if (!this.validatePassword(newPassword)) {
            this.showFeedback(feedback, "Password must be 6+ characters and include a number", false);
            return;
        }
        
        try {
            this.setButtonLoading('passwordForm', true);
            
            const formData = new FormData();
            formData.append('email', this.userEmail);
            formData.append('new_password', newPassword);
            formData.append('action', 'reset_password');
            
            const response = await fetch('forgot-password.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.text();
            
            if (result === 'SUCCESS') {
                alert('✅ Password reset successfully! You can now login with your new password.');
                window.location.href = 'login.html';
            } else {
                this.showFeedback(feedback, result.replace('ERROR: ', ''), false);
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showFeedback(feedback, "Network error. Please try again.", false);
        } finally {
            this.setButtonLoading('passwordForm', false);
        }
    }

    async resendVerificationCode() {
        const resendLink = document.getElementById('resendLink');
        const feedback = document.getElementById('codeFeedback');
        
        if (resendLink.classList.contains('disabled')) return;
        
        try {
            resendLink.classList.add('disabled');
            
            const formData = new FormData();
            formData.append('email', this.userEmail);
            formData.append('action', 'send_code');
            
            const response = await fetch('forgot-password.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.text();
            
            if (result === 'SUCCESS') {
                this.showFeedback(feedback, "✅ New verification code sent!", true);
                this.resetTimer();
                this.startTimer();
                
                // Clear previous code inputs
                document.querySelectorAll('.code-input').forEach(input => {
                    input.value = '';
                });
                document.getElementById('verifyCodeBtn').disabled = true;
                
            } else {
                this.showFeedback(feedback, result.replace('ERROR: ', ''), false);
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showFeedback(feedback, "Network error. Please try again.", false);
        } finally {
            setTimeout(() => {
                resendLink.classList.remove('disabled');
            }, 30000);
        }
    }

    // Helper methods
    setButtonLoading(formId, isLoading) {
        const button = document.querySelector(`#${formId} .btn`);
        if (isLoading) {
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            button.disabled = true;
        } else {
            if (formId === 'emailForm') {
                button.innerHTML = '<i class="fas fa-paper-plane"></i> Send Verification Code';
            } else if (formId === 'codeForm') {
                button.innerHTML = '<i class="fas fa-check"></i> Verify Code';
            } else {
                button.innerHTML = '<i class="fas fa-lock"></i> Reset Password';
            }
            button.disabled = false;
        }
    }

    showFeedback(element, message, isSuccess) {
        element.textContent = message;
        element.className = isSuccess ? "feedback valid" : "feedback invalid";
    }

    // ... rest of the methods remain the same (moveToStep, startTimer, stopTimer, etc.)
    moveToStep(step) {
        document.querySelectorAll('.reset-step').forEach(stepEl => {
            stepEl.classList.remove('active');
        });
        
        document.querySelectorAll('.step').forEach(stepEl => {
            stepEl.classList.remove('active', 'completed');
        });
        
        document.getElementById(`step${step}-content`).classList.add('active');
        
        for (let i = 1; i <= step; i++) {
            const stepEl = document.getElementById(`step${i}`);
            if (i === step) {
                stepEl.classList.add('active');
            } else {
                stepEl.classList.add('completed');
            }
        }
        
        this.currentStep = step;
        
        if (step === 2) {
            document.getElementById('userEmail').textContent = this.userEmail;
        }
    }

    startTimer() {
        this.stopTimer();
        this.updateTimerDisplay();
        
        this.timerInterval = setInterval(() => {
            this.timeLeft--;
            this.updateTimerDisplay();
            
            if (this.timeLeft <= 0) {
                this.stopTimer();
                this.handleTimerExpired();
            }
        }, 1000);
    }

    stopTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
            this.timerInterval = null;
        }
    }

    resetTimer() {
        this.timeLeft = 180;
        this.updateTimerDisplay();
    }

    updateTimerDisplay() {
        const timerElement = document.getElementById('timer');
        const minutes = Math.floor(this.timeLeft / 60);
        const seconds = this.timeLeft % 60;
        
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (this.timeLeft <= 30) {
            timerElement.classList.add('expiring');
        } else {
            timerElement.classList.remove('expiring');
        }
    }

    handleTimerExpired() {
        const feedback = document.getElementById('codeFeedback');
        feedback.textContent = "Verification code has expired. Please request a new one.";
        feedback.className = "feedback invalid";
        document.getElementById('verifyCodeBtn').disabled = true;
    }

    validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    validatePassword(password) {
        const regex = /^(?=.*[0-9]).{6,}$/;
        return regex.test(password);
    }

    checkPasswordStrength(password) {
        const strengthBar = document.getElementById('passwordStrength');
        const feedback = document.getElementById('passwordFeedback');
        
        if (password.length === 0) {
            strengthBar.className = 'password-strength';
            feedback.textContent = '';
            return;
        }
        
        let strength = 0;
        
        if (password.length >= 6) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[^A-Za-z0-9]/)) strength++;
        
        if (strength < 2) {
            strengthBar.className = 'password-strength weak';
            feedback.textContent = 'Weak password';
            feedback.className = 'feedback invalid';
        } else if (strength < 4) {
            strengthBar.className = 'password-strength medium';
            feedback.textContent = 'Medium strength password';
            feedback.className = 'feedback';
        } else {
            strengthBar.className = 'password-strength strong';
            feedback.textContent = 'Strong password ✓';
            feedback.className = 'feedback valid';
        }
    }

    checkPasswordMatch() {
        const password = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const feedback = document.getElementById('confirmFeedback');
        
        if (confirmPassword.length === 0) {
            feedback.textContent = '';
            return;
        }
        
        if (password === confirmPassword) {
            feedback.textContent = 'Passwords match ✓';
            feedback.className = 'feedback valid';
        } else {
            feedback.textContent = 'Passwords do not match';
            feedback.className = 'feedback invalid';
        }
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    new PasswordReset();
});