# Lumina 🌟
> Bridging people in need with local associations and volunteers in Tunisia.

![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.2.12-purple)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![Architecture](https://img.shields.io/badge/Architecture-MVC-green)

---

## 📋 Overview
Lumina connects individuals facing social difficulties (unemployment, poverty, etc.) with nearby associations and volunteers. Users submit cases, and the platform maps their location to visualize available "help points" in their region.

> *"Acting as a bridge between those who need help and those who can offer it."*

---

## ✨ Key Features
- **For Seekers:** Easy case submission, real-time location mapping, anonymous options.
- **For Volunteers/Associations:** Geographic matching, case management dashboard, volunteer coordination.
- **Core:** Interactive maps, smart proximity matching, AI-powered chatbot, multi-module architecture.

---

## 👥 Team & Modules
| Team Member | Module | Responsibilities |
| :--- | :--- | :--- |
| **Oumaima** | User Module | Auth, profiles, registration, login, user dashboard |
| **Mariem** | Events Module | Event creation, calendar, volunteer coordination |
| **Menna** | Social Cases | Submission forms, case management, priority tracking |
| **Nawress** | Jobs Department | Job listings, employment matching, applications |
| **Ilyess** | Complaints & AI Chatbot | Feedback, complaints, reviews, AI-powered assistance |

---

## 📸 Screenshots
<img width="1850" height="905" alt="Screenshot 1" src="https://github.com/user-attachments/assets/644d7c61-8631-47c4-ba99-8f127fb5d72a" />
<img width="1721" height="910" alt="Screenshot 2" src="https://github.com/user-attachments/assets/fb7c10d1-7501-4f5e-9c26-80921d20e415" />
<img width="1818" height="901" alt="Screenshot 3" src="https://github.com/user-attachments/assets/7c35b1cf-3768-4fdd-a017-d2c51d609bc0" />
<img width="1820" height="904" alt="Screenshot 4" src="https://github.com/user-attachments/assets/72c9e28c-c4d2-402b-9f3f-08abdc017cc3" />

---

## 🛠️ Tech Stack
- **Backend:** PHP 8.2.12 (PDO, Custom MVC)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (ES6+), Leaflet.js / Google Maps API
- **Environment:** XAMPP, Apache

---

## 📁 Structure (Brief)
app/ (Controllers, Models, Views)
public/ (CSS, JS, images, index.php)
config/ (Database & env configs)
database/ (Schema & seed data)
helpers/ (Auth, Map, Validation)

---

## 🚀 Installation
1. **Clone:** `git clone https://github.com/yourusername/lumina.git`
2. **Move** files to `C:\xampp\htdocs\ProjetMVC\` and start Apache/MySQL.
3. **Database:** Create `lumina_db` and import `database/schema.sql`.
4. **Config:** Copy `config/env.example.php` to `config/env.php` and set your DB credentials.
5. **Launch:** Visit `http://localhost/ProjetMVC/`.

---

## 📡 API Documentation (v1)
| Module | Method | Endpoint | Description |
| :--- | :--- | :--- | :--- |
| User | POST | `/api/users/register` | Register account |
| User | POST | `/api/users/login` | Authenticate |
| User | GET | `/api/users/profile` | Get profile |
| Cases | POST | `/api/cases` | Submit request |
| Cases | GET | `/api/cases` | List cases |
| Cases | PUT | `/api/cases/{id}/status` | Update status |
| Map | GET | `/api/map/help-points` | Nearby coordinates |
| Chatbot | POST | `/api/chatbot/message` | AI assistant |

---

## 🎨 Design System
css
/* Base Backgrounds */
--background-dark: #0f0f1a;
--background-card: #1a1a2e;

/* Brand */
--primary-purple: #8b6cf7;
--primary-gradient: linear-gradient(135deg, #8b6cf7 0%, #6a4fbb 100%);

/* Accents */
--accent-blue: #4fc3f7;
--accent-green: #4caf50;
--accent-orange: #ff9800;
--accent-red: #f44336;

/* Text */
--text-primary: #ffffff;
--text-secondary: #b0b0c0;
--text-muted: #8888a0;
🔧 Dev Guidelines
Security: Use PDO prepared statements & htmlspecialchars().

Architecture: Strict MVC separation (Model, View, Controller).

Git Flow:

bash
git checkout -b feature/module-name
git commit -m "Add: description"
git push origin feature/module-name
🗺️ Roadmap
Phase 1 (MVP): Auth, case submission, interactive map.

Phase 2: Push notifications, React Native mobile app, Arabic/French localization.

Phase 3: Government API integration, predictive analytics, volunteer dispatch.

📄 License
MIT License. See LICENSE for details.

"Lighting the way to help, one connection at a time" ✨
Built with ❤️ for Tunisia
