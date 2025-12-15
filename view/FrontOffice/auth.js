// auth.js → Version corrigée
class AuthManager {
    constructor() {
        this.currentUser = null;
        this.init();
    }

    init() {
        this.loadUser();
        window.addEventListener('storage', () => this.refresh());
        window.addEventListener('authUpdated', () => this.refresh());
        document.addEventListener('DOMContentLoaded', () => setTimeout(() => this.refresh(), 100));
        
        let attempts = 0;
        const interval = setInterval(() => {
            this.refresh();
            attempts++;
            if (attempts > 10) clearInterval(interval);
        }, 500);
    }

    loadUser() {
        const data = localStorage.getItem('currentUser');
        if (data) {
            try {
                this.currentUser = JSON.parse(data);
            } catch (e) {
                localStorage.removeItem('currentUser');
                this.currentUser = null;
            }
        }
    }

    refresh() {
        this.loadUser();
        this.updateNavbar();
    }

    updateNavbar() {
        const link = document.querySelector('#authLinks > a');
        const menu = document.getElementById('authSubMenu');

        if (!link || !menu) return;

        if (this.currentUser) {
            link.innerHTML = `<i class="fas fa-user"></i> ${this.currentUser.username || 'User'}`;
            menu.innerHTML = `
                <li><a href="profile.html"><i class="fas fa-user-circle"></i> Profile</a></li>
                <li>
                    <a href="javascript:void(0)" id="logoutLink" style="cursor:pointer;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            `;
        
            // Properly bind logout without href="#" side effects
            setTimeout(() => {
                const logoutBtn = document.getElementById('logoutLink');
                if (logoutBtn) {
                    logoutBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        confirmLogout();
                    });
                }
            }, 10);
        }
        
        
        else {
            link.innerHTML = 'Account';
            menu.innerHTML = `
                <li><a href="login.html"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.html"><i class="fas fa-user-plus"></i> Register</a></li>
            `;
        }
    }

    logout() {
        this.currentUser = null;
        localStorage.removeItem('currentUser');
        sessionStorage.clear(); // optional, but nice

        this.refresh(); // instantly updates navbar

        // Small delay so the "Goodbye" animation can finish
        setTimeout(() => {
            window.location.href = 'newindex.html'; // or 'login.html' if you prefer
        }, 400);
    }

    setUser(userData) {
        this.currentUser = userData;
        localStorage.setItem('currentUser', JSON.stringify(userData));
        
        // Also set a cookie for PHP to read
        document.cookie = `php_user_data=${JSON.stringify(userData)}; path=/; max-age=${86400 * 7}`;
        
        this.refresh();
        window.dispatchEvent(new Event('authUpdated'));
    }



    getUser() {
        return this.currentUser;
    }
}

// Add this function to auth.js, after the AuthManager class but before window.authManager = new AuthManager();

class ProtectedNavigation {
    constructor() {
        this.authManager = window.authManager || new AuthManager();
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.bindProtectedLinks());
        } else {
            this.bindProtectedLinks();
        }
    }

    bindProtectedLinks() {
        // Find all protected links
        const protectedLinks = document.querySelectorAll('a.protected-link[data-protected="true"]');
        
        protectedLinks.forEach(link => {
            // Remove existing click listeners to avoid duplicates
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
            
            // Add click handler
            newLink.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleProtectedClick(e, newLink);
            });
        });
    }

    handleProtectedClick(e, linkElement) {
        const isLoggedIn = this.authManager.getUser() !== null;
        const targetHref = linkElement.getAttribute('href');
        
        if (isLoggedIn) {
            // User is logged in, allow navigation
            window.location.href = targetHref;
        } else {
            // User is not logged in, show login prompt
            this.showLoginPrompt(linkElement.textContent, targetHref);
        }
    }

    showLoginPrompt(pageName, targetUrl) {
        // Create overlay similar to logout confirmation
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.background = 'rgba(0,0,0,0.7)';
        overlay.style.backdropFilter = 'blur(8px)';
        overlay.style.zIndex = '10000';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.opacity = '0';
        overlay.style.transition = 'opacity 0.4s ease';

        // Modal for login prompt
        const modal = document.createElement('div');
        modal.style.background = 'white';
        modal.style.padding = '2.5rem';
        modal.style.borderRadius = '20px';
        modal.style.boxShadow = '0 20px 60px rgba(0,0,0,0.3)';
        modal.style.textAlign = 'center';
        modal.style.maxWidth = '450px';
        modal.style.width = '90%';
        modal.style.transform = 'scale(0.8)';
        modal.style.transition = 'transform 0.4s ease';

        modal.innerHTML = `
            <i class="fas fa-lock" style="font-size: 3.5rem; color: #5f4b8b; margin-bottom: 1rem;"></i>
            <h3 style="margin: 0 0 1rem 0; color: #2d2a44;">Login Required</h3>
            <p style="color: #6c6c6c; margin-bottom: 1.5rem; line-height: 1.6;">
                To access <strong>${pageName}</strong>, please log in first.<br>
                Don't have an account? Register to join our community!
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <button id="cancelLoginPrompt" style="
                    padding: 0.8rem 1.8rem;
                    background: #f1f1f1;
                    border: none;
                    border-radius: 12px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s;
                ">Maybe Later</button>
                <a href="login.html" id="goToLogin" style="
                    padding: 0.8rem 1.8rem;
                    background: linear-gradient(135deg, #5f4b8b, #8b6cf7);
                    color: white;
                    border: none;
                    border-radius: 12px;
                    font-weight: 600;
                    cursor: pointer;
                    text-decoration: none;
                    display: inline-block;
                    box-shadow: 0 8px 25px rgba(95,75,139,0.4);
                    transition: all 0.3s;
                ">Login / Register</a>
            </div>
            ${targetUrl ? `
            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #eee;">
                <p style="font-size: 0.85rem; color: #888; margin: 0.5rem 0;">
                    Want to save this page for later?
                </p>
                <button id="savePageForLater" style="
                    background: none;
                    border: 2px dashed #ccc;
                    color: #666;
                    padding: 0.5rem 1rem;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 0.9rem;
                    transition: all 0.3s;
                    margin-top: 0.5rem;
                ">Save Page</button>
            </div>
            ` : ''}
        `;

        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        // Animation entrance
        setTimeout(() => {
            overlay.style.opacity = '1';
            modal.style.transform = 'scale(1)';
        }, 10);

        // Close button
        modal.querySelector('#cancelLoginPrompt').onclick = () => {
            overlay.style.opacity = '0';
            modal.style.transform = 'scale(0.8)';
            setTimeout(() => document.body.removeChild(overlay), 400);
        };

        // Save page for later functionality
        const saveButton = modal.querySelector('#savePageForLater');
        if (saveButton) {
            saveButton.onclick = () => {
                // Save the intended URL to localStorage
                const savedPages = JSON.parse(localStorage.getItem('savedPages') || '[]');
                if (!savedPages.includes(targetUrl)) {
                    savedPages.push({
                        url: targetUrl,
                        name: pageName,
                        timestamp: new Date().toISOString()
                    });
                    localStorage.setItem('savedPages', JSON.stringify(savedPages));
                    
                    // Update button text
                    saveButton.textContent = '✓ Saved!';
                    saveButton.style.border = '2px solid #00b894';
                    saveButton.style.color = '#00b894';
                    saveButton.disabled = true;
                    
                    setTimeout(() => {
                        overlay.style.opacity = '0';
                        modal.style.transform = 'scale(0.8)';
                        setTimeout(() => document.body.removeChild(overlay), 400);
                    }, 1500);
                }
            };
        }

        // Close on overlay click
        overlay.onclick = (e) => {
            if (e.target === overlay) {
                modal.querySelector('#cancelLoginPrompt').click();
            }
        };
    }
}

// Also add CSS for hover effects
const protectedLinkStyle = document.createElement('style');
protectedLinkStyle.textContent = `
    a.protected-link {
        position: relative;
    }
    
    a.protected-link:after {
        content: '🔒';
        font-size: 0.7em;
        margin-left: 5px;
        opacity: 0.6;
    }
    
    a.protected-link:hover:after {
        opacity: 1;
    }
    
    /* When user is logged in, show unlocked icon */
    .user-logged-in a.protected-link:after {
        content: '🔓';
    }
`;
document.head.appendChild(protectedLinkStyle);

// تأكيد الخروج + رسالة وداعية أنيقة
function confirmLogout() {
    // إنشاء overlay خلفية شفافة
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.background = 'rgba(0,0,0,0.7)';
    overlay.style.backdropFilter = 'blur(8px)';
    overlay.style.zIndex = '9999';
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';
    overlay.style.opacity = '0';
    overlay.style.transition = 'opacity 0.4s ease';

    // صندوق التأكيد
    const modal = document.createElement('div');
    modal.style.background = 'white';
    modal.style.padding = '2.5rem';
    modal.style.borderRadius = '20px';
    modal.style.boxShadow = '0 20px 60px rgba(0,0,0,0.3)';
    modal.style.textAlign = 'center';
    modal.style.maxWidth = '400px';
    modal.style.width = '90%';
    modal.style.transform = 'scale(0.8)';
    modal.style.transition = 'transform 0.4s ease';

    modal.innerHTML = `
        <i class="fas fa-heart-crack" style="font-size: 3.5rem; color: #e17055; margin-bottom: 1rem;"></i>
        <h3 style="margin: 0 0 1rem 0; color: #2d2a44;">Are you sure you want to leave?</h3>
        <p style="color: #6c6c6c; margin-bottom: 1.5rem;">We'll miss you until next time ❤️</p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button id="cancelLogout" style="
                padding: 0.8rem 1.8rem;
                background: #f1f1f1;
                border: none;
                border-radius: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s;
            ">Stay</button>
            <button id="confirmLogout" style="
                padding: 0.8rem 1.8rem;
                background: linear-gradient(135deg, #5f4b8b, #8b6cf7);
                color: white;
                border: none;
                border-radius: 12px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 8px 25px rgba(95,75,139,0.4);
                transition: all 0.3s;
            ">Yes, Logout</button>
        </div>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    // Animation دخول
    setTimeout(() => {
        overlay.style.opacity = '1';
        modal.style.transform = 'scale(1)';
    }, 10);

    // إلغاء الخروج
    modal.querySelector('#cancelLogout').onclick = () => {
        overlay.style.opacity = '0';
        modal.style.transform = 'scale(0.8)';
        setTimeout(() => document.body.removeChild(overlay), 400);
    };

    // تأكيد الخروج
    modal.querySelector('#confirmLogout').onclick = () => {
        overlay.style.opacity = '0';
        modal.innerHTML = `
            <i class="fas fa-heart" style="font-size: 3.5rem; color: #00b894; margin-bottom: 1rem; animation: pulse 1s infinite;"></i>
            <h3 style="color: #2d2a44;">Goodbye!</h3>
            <p style="color: #6c6c6c;">See you soon 👋</p>
        `;
        setTimeout(() => {
            document.body.removeChild(overlay);
            authManager.logout(); // ا
        }, 1500);
    };

   
    overlay.onclick = (e) => {
        if (e.target === overlay) {
            modal.querySelector('#cancelLogout').click();
        }
    };
}

const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(style);

window.authManager = new AuthManager();
window.logout = () => window.authManager.logout();

// Initialize Protected Navigation
document.addEventListener('DOMContentLoaded', () => {
    window.protectedNav = new ProtectedNavigation();
    
    // Update body class based on auth status
    const updateBodyClass = () => {
        if (window.authManager.getUser()) {
            document.body.classList.add('user-logged-in');
        } else {
            document.body.classList.remove('user-logged-in');
        }
    };
    
    // Initial update
    updateBodyClass();
    
    // Listen for auth changes
    window.addEventListener('authUpdated', updateBodyClass);
});