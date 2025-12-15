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
        this.refresh();
        window.dispatchEvent(new Event('authUpdated'));
        
        // ✅ SUPPRIMEZ COMPLÈTEMENT LA REDIRECTION AUTOMATIQUE
        // La redirection est gérée dans login.js uniquement
    }



    getUser() {
        return this.currentUser;
    }
}

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