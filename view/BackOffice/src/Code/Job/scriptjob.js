// scriptjob.js - Validation UX et interactions COMPLÈTE
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing Jobs UX');
    initJobsUX();
});

function initJobsUX() {
    console.log('Initializing Jobs UX...');
    
    const offerForm = document.getElementById('new-offer-form');
    if (offerForm) {
        console.log('Offer form found, setting up validation...');
        // Initialiser TOUTES les validations
        initializeAllValidations();
        setupSkillsManagement();
        setupFormSubmission();
        // Initialize map for job location
        setupJobLocationMap();
    }
    
    setupJobsNavigation();
    console.log('Jobs UX initialized successfully');
}

// Initialiser TOUTES les validations
function initializeAllValidations() {
    console.log('Initializing all validations...');
    
    // 1. Configurer la validation en temps réel
    setupRealTimeValidation();
    
    // 2. Configurer la date minimum
    setupMinDate();
    
    // 3. Initialiser le compteur de caractères
    setupCharCounter();
}

// Configuration de la validation en temps réel
function setupRealTimeValidation() {
    // Job Title
    const jobTitle = document.getElementById('job-title');
    if (jobTitle) {
        jobTitle.addEventListener('input', validateJobTitle);
        jobTitle.addEventListener('blur', validateJobTitle);
    }
    
    // Job Location
    const jobLocation = document.getElementById('job-location');
    if (jobLocation) {
        jobLocation.addEventListener('input', validateJobLocation);
        jobLocation.addEventListener('blur', validateJobLocation);
    }
    
    // Salaires
    const salaryMin = document.getElementById('salary-min');
    const salaryMax = document.getElementById('salary-max');
    if (salaryMin && salaryMax) {
        salaryMin.addEventListener('input', validateSalaries);
        salaryMin.addEventListener('blur', validateSalaries);
        salaryMax.addEventListener('input', validateSalaries);
        salaryMax.addEventListener('blur', validateSalaries);
    }
    
    // Date d'expiration
    const expDate = document.getElementById('expiration-date');
    if (expDate) {
        expDate.addEventListener('change', validateExpirationDate);
        expDate.addEventListener('blur', validateExpirationDate);
    }
    
    // Description
    const jobDesc = document.getElementById('job-description');
    if (jobDesc) {
        jobDesc.addEventListener('input', validateJobDescription);
        jobDesc.addEventListener('blur', validateJobDescription);
    }
}

function setupMinDate() {
    const dateInput = document.getElementById('expiration-date');
    if (dateInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];
    }
}

function setupCharCounter() {
    const descInput = document.getElementById('job-description');
    const counter = document.getElementById('char-counter');
    
    if (descInput && counter) {
        descInput.addEventListener('input', function() {
            counter.textContent = this.value.length;
        });
        counter.textContent = descInput.value.length;
    }
}

function setupSkillsManagement() {
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
    }
}

function setupFormSubmission() {
    const form = document.getElementById('new-offer-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Valider TOUS les champs avant soumission
            const isValid = validateAllFields();
            
            if (isValid) {
                // Show loading state
                const submitBtn = document.getElementById('submit-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '⏳ Publishing...';
                submitBtn.disabled = true;
                
                // Soumettre le formulaire
                this.submit();
            } else {
                showFormError('Please correct the errors in the form before submitting.');
            }
        });
    }
}

// ===== FONCTIONS DE VALIDATION =====

// Fonction utilitaire pour afficher les messages
function showMessage(elementId, message, isError = true) {
    const msgElement = document.getElementById(elementId);
    if (!msgElement) return;
    
    msgElement.textContent = message;
    msgElement.className = 'field-msg ' + (isError ? 'error' : 'success');
    msgElement.style.display = 'block';
    
    return !isError;
}

// Fonction utilitaire pour styliser les champs
function styleField(fieldId, isValid) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    field.classList.remove('is-valid', 'is-invalid');
    field.classList.add(isValid ? 'is-valid' : 'is-invalid');
}

// 1. Validation du titre
function validateJobTitle() {
    const value = document.getElementById('job-title').value.trim();
    const isValid = value.length >= 3;
    
    styleField('job-title', isValid);
    
    return showMessage(
        'job-title-msg',
        isValid ? '✓ Title valid' : '❌ Title must be at least 3 characters',
        !isValid
    );
}

// 2. Validation de la localisation
function validateJobLocation() {
    const locationInput = document.getElementById('job-location');
    if (!locationInput) return true; // If map is not used, skip validation
    
    const value = locationInput.value.trim();
    // Check if location is coordinates (should not be)
    const isCoordinates = /^\s*\d+\.?\d*\s*[,|]\s*\d+\.?\d*\s*$/.test(value);
    const isValid = value.length >= 2 && !isCoordinates;
    
    // Note: job-location is hidden, so we don't style it
    return showMessage(
        'job-location-msg',
        isValid ? '✓ Location valid' : '❌ Please select a location on the map',
        !isValid
    );
}

// 3. Validation des salaires
function validateSalaries() {
    const minField = document.getElementById('salary-min');
    const maxField = document.getElementById('salary-max');
    const min = minField ? parseInt(minField.value) || 0 : 0;
    const max = maxField ? parseInt(maxField.value) || 0 : 0;
    
    let isValid = true;
    let message = '✓ Valid salary range';
    
    if (min <= 0 && max <= 0) {
        isValid = false;
        message = '❌ Please enter both salary values';
    } else if (min > 0 && max > 0 && min >= max) {
        isValid = false;
        message = '❌ Minimum must be less than maximum';
    } else if (min > 0 && max === 0) {
        isValid = false;
        message = '❌ Please enter maximum salary';
    } else if (min === 0 && max > 0) {
        isValid = false;
        message = '❌ Please enter minimum salary';
    }
    
    // Styliser les champs
    if (minField) {
        minField.classList.remove('is-valid', 'is-invalid');
        minField.classList.add(isValid ? 'is-valid' : 'is-invalid');
    }
    if (maxField) {
        maxField.classList.remove('is-valid', 'is-invalid');
        maxField.classList.add(isValid ? 'is-valid' : 'is-invalid');
    }
    
    // Afficher le message
    const msgElement = document.getElementById('salary-msg');
    if (msgElement) {
        msgElement.textContent = message;
        msgElement.className = 'field-msg ' + (isValid ? 'success' : 'error');
        msgElement.style.display = 'block';
    }
    
    return isValid;
}

// 4. Validation de la date
function validateExpirationDate() {
    const field = document.getElementById('expiration-date');
    const value = field ? field.value : '';
    
    if (!value) {
        styleField('expiration-date', false);
        return showMessage(
            'expiration-date-msg',
            '❌ Expiration date is required',
            true
        );
    }
    
    const selected = new Date(value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const isValid = selected > today;
    styleField('expiration-date', isValid);
    
    return showMessage(
        'expiration-date-msg',
        isValid ? '✓ Valid date' : '❌ Date must be in the future',
        !isValid
    );
}

// 5. Validation de la description
function validateJobDescription() {
    const value = document.getElementById('job-description').value.trim();
    const isValid = value.length >= 50;
    
    styleField('job-description', isValid);
    
    return showMessage(
        'job-description-msg',
        isValid ? '✓ Description valid' : '❌ Description must be at least 50 characters',
        !isValid
    );
}

// 6. Validation des compétences
function validateSkills() {
    const skillsTags = document.querySelectorAll('.skill-tag');
    const isValid = skillsTags.length >= 2;
    
    return showMessage(
        'skills-msg',
        isValid ? `✓ ${skillsTags.length} skill(s) added` : '❌ Add at least 2 skills',
        !isValid
    );
}

// Valider TOUS les champs (utilisé à la soumission)
function validateAllFields() {
    console.log('Validating ALL fields...');
    
    let allValid = true;
    
    allValid = validateJobTitle() && allValid;
    allValid = validateJobLocation() && allValid;
    allValid = validateSalaries() && allValid;
    allValid = validateExpirationDate() && allValid;
    allValid = validateJobDescription() && allValid;
    allValid = validateSkills() && allValid;
    
    console.log('All fields valid:', allValid);
    return allValid;
}

// ===== GESTION DES SKILLS =====

function addSkill() {
    const input = document.getElementById('skill-input');
    const skill = input.value.trim();
    
    if (skill) {
        const skillsContainer = document.getElementById('skills-tags');
        const skillTag = document.createElement('div');
        skillTag.className = 'skill-tag';
        skillTag.innerHTML = `
            ${skill}
            <span class="remove" onclick="removeSkill(this)">&times;</span>
        `;
        skillsContainer.appendChild(skillTag);
        
        updateSkillsInput();
        validateSkills();
        input.value = '';
    }
}

function removeSkill(element) {
    element.parentElement.remove();
    updateSkillsInput();
    validateSkills();
}

function updateSkillsInput() {
    const skillsTags = document.querySelectorAll('.skill-tag');
    const skills = Array.from(skillsTags).map(tag => 
        tag.textContent.replace('×', '').trim()
    );
    
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

// ===== FONCTIONS UTILITAIRES =====

function showFormError(message) {
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
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Navigation
function setupJobsNavigation() {
    document.querySelectorAll('.nav-btn, .btn-back, .cancel-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const onclick = this.getAttribute('onclick');
            if (onclick) {
                e.preventDefault();
                const match = onclick.match(/window\.location\.href\s*=\s*'([^']+)'/);
                if (match && match[1]) {
                    window.location.href = match[1];
                }
            }
        });
    });
}

// ===== MAP INITIALIZATION FOR JOB LOCATION =====

async function setupJobLocationMap() {
    const mapContainer = document.getElementById('job-location-map');
    if (!mapContainer) {
        console.log('Job location map container not found');
        return;
    }
    
    // Check if map-utils.js is loaded
    if (typeof initTunisiaMap === 'undefined') {
        console.error('map-utils.js not loaded. Please include it in the page.');
        return;
    }
    
    let locationMap = null;
    let selectedMarker = null;
    
    try {
        // Initialize map
        locationMap = initTunisiaMap('job-location-map', 33.8869, 10.1775, 7);
        
        // Get existing location value if editing
        const currentLocation = document.getElementById('job-location').value;
        
        // If editing and location exists, show it on map
        if (currentLocation && currentLocation.trim() !== '') {
            let coords = parseCoordinates(currentLocation);
            
            // If not coordinates, try to geocode
            if (!coords) {
                coords = await getLocationCoordinates(currentLocation);
            }
            
            if (coords) {
                selectedMarker = L.marker([coords.lat, coords.lng], {draggable: true}).addTo(locationMap);
                selectedMarker.bindPopup('Job location').openPopup();
                locationMap.setView([coords.lat, coords.lng], 13);
                document.getElementById('selected-job-location').textContent = currentLocation;
                document.getElementById('job_loc_lat').value = coords.lat;
                document.getElementById('job_loc_lng').value = coords.lng;
            }
        }
        
        // Add click event to map
        locationMap.on('click', async function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            // Remove previous marker if exists
            if (selectedMarker) {
                locationMap.removeLayer(selectedMarker);
            }
            
            // Add new marker
            selectedMarker = L.marker([lat, lng], {draggable: true}).addTo(locationMap);
            selectedMarker.bindPopup('Job location').openPopup();
            
            // Get address from coordinates
            try {
                const address = await reverseGeocode(lat, lng);
                
                // Ensure address is text, not coordinates
                let locationText = address;
                if (address && /^\s*\d+\.?\d*\s*[,|]\s*\d+\.?\d*\s*$/.test(address)) {
                    locationText = 'Location sélectionnée, Tunisie';
                }
                
                document.getElementById('job-location').value = locationText;
                document.getElementById('job_loc_lat').value = lat;
                document.getElementById('job_loc_lng').value = lng;
                document.getElementById('selected-job-location').textContent = locationText;
                
                // Validate location
                validateJobLocation();
            } catch (error) {
                console.error('Geocoding error:', error);
                const locationText = lat + ', ' + lng;
                document.getElementById('job-location').value = locationText;
                document.getElementById('job_loc_lat').value = lat;
                document.getElementById('job_loc_lng').value = lng;
                document.getElementById('selected-job-location').textContent = locationText;
            }
            
            // Allow marker dragging
            selectedMarker.on('dragend', async function(e) {
                const newLat = e.target.getLatLng().lat;
                const newLng = e.target.getLatLng().lng;
                
                try {
                    const newAddress = await reverseGeocode(newLat, newLng);
                    
                    let locationText = newAddress;
                    if (newAddress && /^\s*\d+\.?\d*\s*[,|]\s*\d+\.?\d*\s*$/.test(newAddress)) {
                        locationText = 'Location sélectionnée, Tunisie';
                    }
                    
                    document.getElementById('job-location').value = locationText;
                    document.getElementById('job_loc_lat').value = newLat;
                    document.getElementById('job_loc_lng').value = newLng;
                    document.getElementById('selected-job-location').textContent = locationText;
                    
                    // Validate location
                    validateJobLocation();
                } catch (error) {
                    console.error('Geocoding error:', error);
                    const locationText = newLat + ', ' + newLng;
                    document.getElementById('job-location').value = locationText;
                    document.getElementById('job_loc_lat').value = newLat;
                    document.getElementById('job_loc_lng').value = newLng;
                    document.getElementById('selected-job-location').textContent = locationText;
                }
            });
        });
        
    } catch (error) {
        console.error('Error initializing job location map:', error);
        mapContainer.innerHTML = '<p style="padding: 20px; color: red;">Error loading map. Please refresh the page.</p>';
    }
}

// Exposer les fonctions globales
window.removeSkill = removeSkill;
window.addSkill = addSkill;

console.log('✅ scriptjob.js loaded successfully');