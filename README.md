Lumina 🌟
A Tunisian Social Platform Connecting People in Need with Local Associations and Volunteers

https://img.shields.io/badge/License-MIT-blue.svg
https://img.shields.io/badge/PHP-8.2.12-purple
https://img.shields.io/badge/MySQL-Database-orange
https://img.shields.io/badge/Architecture-MVC-green

📋 Table of Contents
Overview

Key Features

Team & Modules

Screenshots

Tech Stack

Project Structure

Installation

API Documentation

Design System

Development Guidelines

Future Roadmap

Contributing

License

🌟 Overview
Lumina is a social service platform in Tunisia designed to bridge the gap between individuals facing social difficulties and nearby associations or volunteers who can assist them within their region.

When someone encounters a social problem — such as unemployment, poverty, or precarious living situations — they can complete an online form detailing their situation. The platform maps their location, displaying visual help points ("light points") representing nearby resources and support networks.

🎯 Mission Statement
"Acting as a bridge between those who need help and those who can offer it."

✨ Key Features
🆘 For Those Seeking Help

Intuitive Case Submission Form: Easy-to-complete forms for various social issues.

Real-Time Location Mapping: Visual representation of nearby available aid.

Anonymous Options: Privacy protections for sensitive situations.

Multiple Assistance Categories: Employment, social cases, events, and urgent aid.

🤝 For Associations & Volunteers

Geographic Matching System: Connect directly with individuals in your immediate area.

Case Management Dashboard: Track, update, and manage assistance requests.

Volunteer Coordination: Organize and delegate aid efforts efficiently.

Impact Analytics: Measure community contribution and reach.

🗺️ Core Platform Features

Interactive Map Interface: Geographic representation of needs and local help points.

Smart Matching System: Proximity-based connection algorithm.

Multi-Module Architecture: Dedicated modules handling distinct social workflows.

AI-Powered Chatbot: 24/7 automated guidance and user support.

Feedback & Reviews: Continuous service quality tracking and complaint management.

👥 Team & Modules
Development Team & Responsibilities
Team Member	Module	Responsibilities
Oumaima	User Module	User authentication, profiles, registration, login system, user management dashboard
Mariem	Events Module	Event creation, calendar, volunteer coordination, event management
Menna	Social Cases Department	Case submission forms, case management, priority assignment, case tracking
Nawress	Jobs Department	Job listings, employment matching, employer profiles, job application system
Ilyess	Complaints & Reviews + AI Chatbot	Feedback system, complaint tracking, review management, AI-powered assistance chatbot
Module Integration Points
Shared Authentication: All platform modules utilize Oumaima's core authentication architecture.

Unified Location Services: Geographic mapping utilities shared across all submission and response features.

Central Notification System: Unified cross-module communication and status alerts.

Consistent Design Language: Standardized styling, component structure, and dark-theme guidelines.

📸 Screenshots
<img width="1850" height="905" alt="Screenshot 1" src="https://github.com/user-attachments/assets/644d7c61-8631-47c4-ba99-8f127fb5d72a" /> <img width="1721" height="910" alt="Screenshot 2" src="https://github.com/user-attachments/assets/fb7c10d1-7501-4f5e-9c26-80921d20e415" /> <img width="1818" height="901" alt="Screenshot 3" src="https://github.com/user-attachments/assets/7c35b1cf-3768-4fdd-a017-d2c51d609bc0" /> <img width="1820" height="904" alt="Screenshot 4" src="https://github.com/user-attachments/assets/72c9e28c-c4d2-402b-9f3f-08abdc017cc3" />
🛠️ Tech Stack
Backend: PHP 8.2.12 (PDO, Custom MVC Framework)

Database: MySQL (Normalized schema, Geospatial query support)

Frontend: HTML5, CSS3 (Custom properties/variables), JavaScript (ES6+), Leaflet.js / Google Maps API

Development Environment: XAMPP, Apache

📁 Project Structure
text
ProjetMVC/
├── app/
│   ├── controllers/
│   │   ├── UserController.php
│   │   ├── SocialCaseController.php
│   │   ├── JobController.php
│   │   ├── EventController.php
│   │   └── ComplaintController.php
│   ├── models/
│   │   ├── User.php
│   │   ├── SocialCase.php
│   │   ├── Job.php
│   │   ├── Event.php
│   │   └── Complaint.php
│   └── views/
│       ├── user/
│       ├── social-case/
│       ├── job/
│       ├── event/
│       └── complaint/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── main.css
│   │   │   ├── dark-theme.css
│   │   │   └── animations.css
│   │   ├── js/
│   │   │   ├── main.js
│   │   │   ├── map.js
│   │   │   └── form-validation.js
│   │   └── images/
│   └── index.php
├── config/
│   ├── database.php
│   └── env.php
├── database/
│   ├── schema.sql
│   └── seed-data.sql
├── helpers/
│   ├── AuthHelper.php
│   ├── MapHelper.php
│   └── ValidationHelper.php
├── vendor/
├── .htaccess
└── README.md
🚀 Installation
Prerequisites
XAMPP (PHP 8.2.12+ and MySQL)

Composer

Git

Step-by-Step Setup
Clone the Repository

bash
git clone https://github.com/yourusername/lumina.git
cd lumina
Configure Server Environment

Move the project files into C:\xampp\htdocs\ProjetMVC\

Start Apache and MySQL via the XAMPP Control Panel.

Database Initialization

sql
CREATE DATABASE lumina_db;
-- Import database/schema.sql into lumina_db using phpMyAdmin or MySQL CLI
Environment Configuration

bash
cp config/env.example.php config/env.php
Edit config/env.php with your database credentials:

php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'lumina_db');
define('DB_USER', 'root');
define('DB_PASS', '');

define('APP_URL', 'http://localhost/ProjetMVC/');
define('APP_ENV', 'development');

define('MAP_API_KEY', 'your_api_key_here');
define('ENCRYPTION_KEY', 'your_secure_key_here');
Launch Application

Navigate to http://localhost/ProjetMVC/ in your browser.

📡 API Documentation
Base URL: /api/v1/

Module	Method	Endpoint	Description
User	POST	/api/users/register	Register new account
User	POST	/api/users/login	Authenticate user
User	GET	/api/users/profile	Retrieve user profile
Social Cases	POST	/api/cases	Submit new assistance request
Social Cases	GET	/api/cases	List cases (with filter criteria)
Social Cases	PUT	/api/cases/{id}/status	Update resolution status
Location Services	GET	/api/map/help-points	Fetch nearby assistance coordinates
AI Chatbot	POST	/api/chatbot/message	Dispatch message to AI assistant
🎨 Design System
Color Palette
css
/* Base Backgrounds */
--background-dark: #0f0f1a;
--background-card: #1a1a2e;

/* Brand Gradients & Primaries */
--primary-purple: #8b6cf7;
--primary-gradient: linear-gradient(135deg, #8b6cf7 0%, #6a4fbb 100%);

/* Accent Palette */
--accent-blue: #4fc3f7;
--accent-green: #4caf50;
--accent-orange: #ff9800;
--accent-red: #f44336;

/* Typography Colors */
--text-primary: #ffffff;
--text-secondary: #b0b0c0;
--text-muted: #8888a0;
🔧 Development Guidelines
Security Standards: Always use PDO prepared statements to prevent SQL injections. Sanitize output using htmlspecialchars() to protect against XSS attacks.

Architecture Rules: Maintain strict MVC isolation — model logic, controller processing, and view rendering should remain completely separate.

Git Workflow:

bash
git checkout -b feature/module-name
git commit -m "Add: Concise description of feature"
git push origin feature/module-name
🗺️ Future Roadmap
Phase 1 (MVP): Auth architecture, case submission lifecycle, interactive map display, and core module integration.

Phase 2: Real-time push notifications, mobile application (React Native), multi-language localization (Arabic/French).

Phase 3: Government social service API integrations, predictive need analytics, automated volunteer dispatch schedules.

🤝 Contributing
Contributions are welcome! Please follow these steps:

Fork the repository.

Create a feature branch (git checkout -b feature/AmazingFeature).

Commit your changes (git commit -m 'Add AmazingFeature').

Push to the branch (git push origin feature/AmazingFeature).

Open a Pull Request.

📄 License
This project is licensed under the MIT License — see the LICENSE file for details.

"Lighting the way to help, one connection at a time" ✨
Built with ❤️ for Tunisia
