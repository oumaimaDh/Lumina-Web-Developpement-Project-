// scriptjob.js - Validation UX et interactions COMPLÈTE
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing Jobs UX');
    initJobsUX();
});

function initJobsUX() {
    console.log('Initializing Jobs UX...');
    
    // Vérifier si on est sur la page du formulaire
    const offerForm = document.getElementById('new-offer-form');
    if (offerForm) {
        console.log('Offer form found, setting up validation...');
        setupFormValidation();
        setupSkillsManagement();
        setupFormSubmission();
    } else {
        console.log('No offer form found on this page');
    }
    
    // Setup navigation
    setupJobsNavigation();
    
    console.log('Jobs UX initialized successfully');
}

function setupFormValidation() {
    console.log('Setting up form validation...');
    
    // Validation du titre
    const jobTitleInput = document.getElementById('job-title');
    if (jobTitleInput) {
        jobTitleInput.addEventListener('input', function() {
            validateField(this, this.value.trim().length >= 3, 'Title must be at least 3 characters');
        });
        console.log('Title validation setup');
    }
    
    // Validation de la localisation
    const jobLocationInput = document.getElementById('job-location');
    if (jobLocationInput) {
        jobLocationInput.addEventListener('input', function() {
            validateField(this, this.value.trim().length >= 2, 'Location is required');
        });
        console.log('Location validation setup');
    }
    
    // Validation des salaires
    const salaryMinInput = document.getElementById('salary-min');
    const salaryMaxInput = document.getElementById('salary-max');
    if (salaryMinInput && salaryMaxInput) {
        salaryMinInput.addEventListener('input', validateSalaries);
        salaryMaxInput.addEventListener('input', validateSalaries);
        console.log('Salary validation setup');
    }
    
    // Validation de la date
    const expirationInput = document.getElementById('expiration-date');
    if (expirationInput) {
        // Set min date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        expirationInput.min = tomorrow.toISOString().split('T')[0];
        
        expirationInput.addEventListener('change', function() {
            const selected = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            validateField(this, selected > today, 'Expiration date must be in the future');
        });
        console.log('Date validation setup');
    }
    
    // Compteur de caractères pour la description
    const jobDescriptionInput = document.getElementById('job-description');
    if (jobDescriptionInput) {
        jobDescriptionInput.addEventListener('input', function() {
            const counter = document.getElementById('char-counter');
            if (counter) {
                counter.textContent = this.value.length;
            }
            validateField(this, this.value.trim().length >= 50, 'Description must be at least 50 characters');
        });
        
        // Initialiser le compteur
        const counter = document.getElementById('char-counter');
        if (counter) {
            counter.textContent = jobDescriptionInput.value.length;
        }
        console.log('Description validation setup');
    }
}

function setupSkillsManagement() {
    console.log('Setting up skills management...');
    
    const addSkillButton = document.getElementById('add-skill');
    const skillInput = document.getElementById('skill-input');
    
    if (addSkillButton && skillInput) {
        addSkillButton.addEventListener('click', addSkill);
        skillInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkill();
            }
        });
        console.log('Skills management setup complete');
    } else {
        console.log('Skills elements not found');
    }
}

function setupFormSubmission() {
    console.log('Setting up form submission...');
    
    const form = document.getElementById('new-offer-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission intercepted');
            e.preventDefault();
            
            if (validateForm()) {
                console.log('Form is valid, submitting...');
                
                // Show loading state
                const submitBtn = document.getElementById('submit-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '⏳ Publishing...';
                submitBtn.disabled = true;
                
                // Soumettre le formulaire
                console.log('Submitting form now...');
                this.submit();
            } else {
                console.log('Form validation failed');
                showFormError('Please correct the errors in the form before submitting.');
            }
        });
        console.log('Form submission handler attached');
    }
}

// FONCTIONS DE VALIDATION
function validateField(field, isValid, errorMessage) {
    let validationDiv = field.parentElement.querySelector('.validation-message');
    
    if (!validationDiv) {
        validationDiv = document.createElement('div');
        validationDiv.className = 'validation-message';
        field.parentElement.appendChild(validationDiv);
    }
    
    if (!isValid && field.value.trim() !== '') {
        field.style.borderColor = '#EF4444';
        validationDiv.innerHTML = `<span class="error">❌ ${errorMessage}</span>`;
        return false;
    } else if (isValid) {
        field.style.borderColor = '#10B981';
        validationDiv.innerHTML = `<span class="success">✅ Valid</span>`;
        return true;
    } else {
        field.style.borderColor = '';
        validationDiv.innerHTML = '';
        return false;
    }
}

function validateSalaries() {
    const min = parseInt(document.getElementById('salary-min').value) || 0;
    const max = parseInt(document.getElementById('salary-max').value) || 0;
    let validationDiv = document.getElementById('salary-validation');
    
    if (!validationDiv) {
        validationDiv = document.createElement('div');
        validationDiv.id = 'salary-validation';
        validationDiv.className = 'validation-message';
        document.querySelector('.form-row').appendChild(validationDiv);
    }
    
    const minInput = document.getElementById('salary-min');
    const maxInput = document.getElementById('salary-max');
    
    if (min >= max && max > 0) {
        minInput.style.borderColor = '#EF4444';
        maxInput.style.borderColor = '#EF4444';
        validationDiv.innerHTML = `<span class="error">❌ Minimum salary must be less than maximum</span>`;
        return false;
    } else if (min > 0 && max > 0) {
        minInput.style.borderColor = '#10B981';
        maxInput.style.borderColor = '#10B981';
        validationDiv.innerHTML = `<span class="success">✅ Valid salary range</span>`;
        return true;
    } else {
        minInput.style.borderColor = '';
        maxInput.style.borderColor = '';
        validationDiv.innerHTML = `<span class="error">⚠️ Please enter both salary values</span>`;
        return false;
    }
}

function validateSkills() {
    const skillsTags = document.querySelectorAll('.skill-tag');
    let validationDiv = document.getElementById('skills-validation');
    
    if (!validationDiv) {
        const container = document.getElementById('skills-tags').closest('.form-group');
        validationDiv = document.createElement('div');
        validationDiv.id = 'skills-validation';
        validationDiv.className = 'validation-message';
        container.appendChild(validationDiv);
    }
    
    if (skillsTags.length < 2) {
        validationDiv.innerHTML = `<span class="error">❌ Add at least 2 skills</span>`;
        return false;
    } else {
        validationDiv.innerHTML = `<span class="success">✅ ${skillsTags.length} skill(s) added</span>`;
        return true;
    }
}

function validateForm() {
    console.log('Validating entire form...');
    let isValid = true;
    
    // Validate title
    const title = document.getElementById('job-title');
    if (title) {
        isValid = validateField(title, title.value.trim().length >= 3, 'Title must be at least 3 characters') && isValid;
    }
    
    // Validate location
    const location = document.getElementById('job-location');
    if (location) {
        isValid = validateField(location, location.value.trim().length >= 2, 'Location is required') && isValid;
    }
    
    // Validate salaries
    isValid = validateSalaries() && isValid;
    
    // Validate date
    const date = document.getElementById('expiration-date');
    if (date && date.value) {
        const selected = new Date(date.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        isValid = validateField(date, selected > today, 'Expiration date must be in the future') && isValid;
    } else {
        isValid = false;
        if (date) {
            date.style.borderColor = '#EF4444';
            let validationDiv = date.parentElement.querySelector('.validation-message');
            if (validationDiv) {
                validationDiv.innerHTML = `<span class="error">❌ Expiration date is required</span>`;
            }
        }
    }
    
    // Validate description
    const description = document.getElementById('job-description');
    if (description) {
        isValid = validateField(description, description.value.trim().length >= 50, 'Description must be at least 50 characters') && isValid;
    }
    
    // Validate skills
    isValid = validateSkills() && isValid;
    
    console.log('Form validation result:', isValid);
    return isValid;
}

// GESTION DES SKILLS
function addSkill() {
    console.log('Adding skill...');
    const input = document.getElementById('skill-input');
    const skill = input.value.trim();
    
    if (skill && skill.length > 0) {
        // Créer un tag de compétence
        const skillsContainer = document.getElementById('skills-tags');
        const skillTag = document.createElement('div');
        skillTag.className = 'skill-tag';
        skillTag.innerHTML = `
            ${skill}
            <span class="remove" onclick="removeSkill(this)">&times;</span>
        `;
        skillsContainer.appendChild(skillTag);
        
        // Mettre à jour le champ hidden pour le formulaire
        updateSkillsInput();
        
        // Valider les skills
        validateSkills();
        
        // Vider le champ
        input.value = '';
        
        console.log('Skill added:', skill);
    }
}

function removeSkill(element) {
    console.log('Removing skill...');
    element.parentElement.remove();
    updateSkillsInput();
    validateSkills();
}

function updateSkillsInput() {
    const skillsTags = document.querySelectorAll('.skill-tag');
    const skills = Array.from(skillsTags).map(tag => 
        tag.textContent.replace('×', '').trim()
    );
    
    console.log('Current skills:', skills);
    
    // Créer ou mettre à jour le champ hidden
    let skillsInput = document.getElementById('skills_input');
    if (!skillsInput) {
        skillsInput = document.createElement('input');
        skillsInput.type = 'hidden';
        skillsInput.name = 'skills_input';
        skillsInput.id = 'skills_input';
        document.getElementById('new-offer-form').appendChild(skillsInput);
    }
    
    skillsInput.value = skills.join(',');
}

function showFormError(message) {
    console.log('Showing form error:', message);
    
    let errorDiv = document.getElementById('form-global-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'form-global-error';
        errorDiv.style.cssText = `
            background: #FEE2E2;
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
        `;
        const form = document.getElementById('new-offer-form');
        form.insertBefore(errorDiv, form.firstChild);
    }
    
    errorDiv.textContent = message;
    
    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// NAVIGATION
function setupJobsNavigation() {
    console.log('Setting up jobs navigation...');
    
    // Boutons de navigation principaux
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('onclick');
            if (href) {
                const url = href.match(/window\.location\.href='([^']+)'/);
                if (url && url[1]) {
                    window.location.href = url[1];
                }
            }
        });
    });
    
    // Boutons de retour
    const backButtons = document.querySelectorAll('.btn-back');
    backButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('onclick');
            if (href) {
                const url = href.match(/window\.location\.href='([^']+)'/);
                if (url && url[1]) {
                    window.location.href = url[1];
                }
            }
        });
    });
}

console.log('scriptjob.js loaded successfully');
