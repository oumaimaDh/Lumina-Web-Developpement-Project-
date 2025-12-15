Lumina 🌟
A Tunisian Social Platform Connecting People in Need with Local Associations and Volunteers

https://img.shields.io/badge/License-MIT-blue.svg
https://img.shields.io/badge/PHP-8.2.12-purple
https://img.shields.io/badge/MySQL-Database-orange
https://img.shields.io/badge/Architecture-MVC-green

📋 Table of Contents
Overview

Features

Screenshots

Tech Stack

Installation

Project Structure

Team & Modules

API Documentation

Design System

Development

Future Roadmap

Contributing

License

🌟 Overview
Lumina is a groundbreaking social service platform in Tunisia designed to bridge the gap between individuals facing social difficulties and the associations or volunteers who can assist them within their local region.

When someone encounters a social problem — such as unemployment, poverty, or precarious living situations — they can complete an online form describing their situation. Once submitted, the platform displays their location on an interactive map, with glowing "light points" appearing around it to represent nearby associations or individuals available to help.

🎯 Mission Statement
"Acting as a bridge between those who need help and those who can offer it."

✨ Key Features
🆘 For Those Seeking Help
Intuitive Case Submission Form – Easy-to-complete forms for various social issues

Real-time Location Mapping – Visual representation of help availability

Anonymous Options – Privacy protection for sensitive situations

Multiple Assistance Categories – Employment, social cases, events, and more

🤝 For Associations & Volunteers
Geographic Matching System – Connect with people in your immediate area

Case Management Dashboard – Track and manage assistance requests

Volunteer Coordination – Organize help efforts efficiently

Impact Analytics – Measure your community contribution

🗺️ Platform Features
Interactive Map Interface – Visual geographic representation of needs and resources

Smart Matching Algorithm – Proximity-based connection system

Multi-module Architecture – Specialized departments for different assistance types

AI-Powered Chatbot – 24/7 guidance and support

Feedback & Complaint System – Quality assurance and continuous improvement

📸 Screenshots
NOTE: Add screenshots here once your project is developed. Suggested screenshots:

Landing Page – Showcasing the dark theme with purple accents

Case Submission Form – The form interface

Interactive Map – Displaying help requests and available assistance

Dashboard Views – For both users and associations

Mobile Responsive Views – Different device displays

Example format for adding screenshots:

text
![Dashboard View](screenshots/dashboard.png)
*Dashboard showing active cases and nearby help points*
🛠️ Tech Stack
Backend
PHP 8.2.12 – Server-side scripting

XAMPP – Development environment

PDO (PHP Data Objects) – Secure database interactions

MVC Architecture – Clean separation of concerns

Frontend
HTML5 – Semantic markup

CSS3 with Custom Properties – Modern styling

JavaScript (ES6+) – Interactive features

Map Integration – For geographic visualization (consider Leaflet.js or Google Maps API)

Database
MySQL – Relational database management

Normalized Schema – Efficient data organization

Geospatial Queries – Location-based searches

Architecture
MVC Pattern – Model-View-Controller separation

Modular Design – Independent feature modules

RESTful Principles – API design approach

🚀 Installation
Prerequisites
XAMPP (with PHP 8.2.12+ and MySQL)

Composer (for PHP dependencies)

Git

Step-by-Step Setup
Clone the Repository

bash
git clone https://github.com/yourusername/lumina.git
cd lumina
Configure XAMPP

Place the project in C:\xampp\htdocs\ProjetMVC\

Start Apache and MySQL from XAMPP Control Panel

Database Setup

sql
-- Create database
CREATE DATABASE lumina_db;

-- Import the provided SQL file (will be in /database/schema.sql)
-- Or run the initialization script
Configure Environment

bash
# Copy the environment configuration template
cp config/env.example.php config/env.php

# Edit config/env.php with your database credentials
# Set database connection, API keys, and other configurations
Install Dependencies (if any)

bash
# If using Composer packages
composer install
Access the Application

Open browser and navigate to: http://localhost/ProjetMVC/

Default admin credentials: (to be set during installation)

Configuration File Example (config/env.php)
php
<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'lumina_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application Configuration
define('APP_URL', 'http://localhost/ProjetMVC/');
define('APP_ENV', 'development'); // 'production' for live site

// Map API Key (Google Maps or Leaflet)
define('MAP_API_KEY', 'your_api_key_here');

// Security
define('ENCRYPTION_KEY', 'your_secure_key_here');
?>
📁 Project Structure
text
ProjetMVC/
│
├── app/
│   ├── controllers/
│   │   ├── UserController.php
│   │   ├── SocialCaseController.php
│   │   ├── JobController.php
│   │   ├── EventController.php
│   │   └── ComplaintController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── SocialCase.php
│   │   ├── Job.php
│   │   ├── Event.php
│   │   └── Complaint.php
│   │
│   └── views/
│       ├── user/
│       ├── social-case/
│       ├── job/
│       ├── event/
│       └── complaint/
│
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
│   │
│   └── index.php
│
├── config/
│   ├── database.php
│   └── env.php
│
├── database/
│   ├── schema.sql
│   └── seed-data.sql
│
├── helpers/
│   ├── AuthHelper.php
│   ├── MapHelper.php
│   └── ValidationHelper.php
│
├── vendor/          # Composer dependencies
├── .htaccess
├── README.md
└── composer.json
👥 Team & Modules
Development Team & Responsibilities
Team Member	Module	Responsibilities
Oumaima	User Module	User authentication, profiles, registration, login system, user management dashboard
Mariem	Events Module	Event creation, calendar, volunteer coordination, event management
Menna	Social Cases Department	Case submission forms, case management, priority assignment, case tracking
Nawress	Jobs Department	Job listings, employment matching, employer profiles, job application system
Ilyess	Complaints & Reviews + AI Chatbot	Feedback system, complaint tracking, review management, AI-powered assistance chatbot
Module Integration Points
Shared Authentication – All modules use Oumaima's user system

Unified Location Services – Geographic features shared across modules

Central Notification System – Cross-module communication

Consistent Design Language – Following the established design system

🎨 Design System
Color Palette
css
/* Primary Colors */
--background-dark: #0f0f1a;
--background-card: #1a1a2e;
--primary-purple: #8b6cf7;
--primary-gradient: linear-gradient(135deg, #8b6cf7 0%, #6a4fbb 100%);

/* Accent Colors */
--accent-blue: #4fc3f7;
--accent-green: #4caf50;
--accent-orange: #ff9800;
--accent-red: #f44336;

/* Text Colors */
--text-primary: #ffffff;
--text-secondary: #b0b0c0;
--text-muted: #8888a0;
Typography
Primary Font: Roboto (sans-serif)

Headings Font: Poppins (semi-bold)

Base Font Size: 16px

Line Height: 1.6

UI Components
Cards: Rounded corners (8px), subtle shadows

Buttons: Gradient backgrounds, smooth hover animations

Forms: Glass-morphism effects, focused states

Map Interface: Dark theme compatible, glowing points animation

Animation Guidelines
Hover Effects: 0.3s ease transitions

Page Transitions: Fade-in effects

Loading States: Skeleton screens with shimmer

Success/Error States: Subtle animations with color cues

🔧 Development Guidelines
Coding Standards
Follow PSR-12 coding standards for PHP

Use meaningful variable and function names

Comment complex logic sections

Keep functions focused and single-responsibility

Database Design Principles
Use foreign key constraints for data integrity

Implement proper indexing for frequently queried columns

Normalize data where appropriate

Include created_at and updated_at timestamps

Security Measures
SQL Injection Protection: Use PDO prepared statements exclusively

XSS Prevention: Always escape output with htmlspecialchars()

CSRF Protection: Implement tokens for form submissions

Password Security: Use PHP's password_hash() and password_verify()

Input Validation: Validate on both client and server side

Git Workflow
bash
# Feature Branch Workflow
git checkout -b feature/module-name
# Make changes
git add .
git commit -m "Add: Description of changes"
git push origin feature/module-name
# Create Pull Request for review
📡 API Documentation
Base URL: /api/v1/
Endpoints
User Module
http
POST   /api/users/register     # Register new user
POST   /api/users/login        # User authentication
GET    /api/users/profile      # Get user profile
PUT    /api/users/profile      # Update profile
Social Cases Module
http
POST   /api/cases              # Submit new case
GET    /api/cases              # List cases (with filters)
GET    /api/cases/{id}         # Get specific case
PUT    /api/cases/{id}/status  # Update case status
Map & Location Services
http
GET    /api/map/help-points    # Get nearby help points
POST   /api/map/geocode        # Convert address to coordinates
GET    /api/map/heatmap        # Get needs density data
AI Chatbot
http
POST   /api/chatbot/message    # Send message to AI assistant
GET    /api/chatbot/context    # Get conversation context
🗺️ Future Roadmap
Phase 1: MVP (Current)
User authentication system

Basic case submission and viewing

Interactive map with static help points

Module separation and basic integration

Phase 2: Enhanced Features
Real-time notifications

Advanced matching algorithm

Mobile application (React Native)

SMS integration for low-connectivity users

Multi-language support (Arabic/French)

Phase 3: Advanced Integration
Government API integration (social services)

AI-powered need prediction

Volunteer scheduling system

Impact reporting dashboard

Blockchain for transparent aid tracking

Phase 4: Scale & Expansion
Expand to other Maghreb countries

Partnership portal for corporations

Advanced analytics for policymakers

Mobile payment integration for donations

🤝 Contributing
We welcome contributions to make Lumina more effective in connecting people with help!

How to Contribute
Fork the repository

Create a feature branch (git checkout -b feature/AmazingFeature)

Commit your changes (git commit -m 'Add some AmazingFeature')

Push to the branch (git push origin feature/AmazingFeature)

Open a Pull Request

Development Setup for Contributors
bash
# 1. Fork and clone
git clone https://github.com/YOUR_USERNAME/lumina.git

# 2. Set up development environment
# Follow installation steps above

# 3. Create a new branch
git checkout -b feature/your-feature-name

# 4. Make your changes and test thoroughly

# 5. Submit pull request
Coding Standards
Write clear, commented code

Update documentation as needed

Add tests for new features

Ensure mobile responsiveness

Follow the established design system

📄 License
This project is licensed under the MIT License - see the LICENSE file for details.

🙏 Acknowledgments
The Tunisian volunteer community for inspiration

Open-source mapping libraries that make this project possible

All contributors who believe in using technology for social good

The development team for their dedication to creating positive impact

📞 Support & Contact
For support, questions, or partnership inquiries:

Project Lead: [Your Name/Contact]

Technical Issues: Create an issue in the GitHub repository

General Inquiries: [Your Email]

<div align="center">
"Lighting the way to help, one connection at a time" ✨

Built with ❤️ for Tunisia

</div>