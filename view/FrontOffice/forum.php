<?php
  require_once __DIR__ . '/../../config/Database.php';

  $pdo = (new Database())->connect();

  // Get only the questions
  $sql = "SELECT id_question, title, content, date_question, category, likes
          FROM questions 
          ORDER BY date_question DESC";
  $stmt = $pdo->query($sql);
  $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum | Lumina</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Light Theme - Snowy Twilight Palette */
        :root {
            --bg: #D3D3DF;
            --text: #575D7F;
            --card-bg: #ffffff;
            --border: #E3CED1;
            --primary: #7B8AB6;
            --secondary: #A09EBB;
            --accent: #575D7F;
            --gradient: linear-gradient(135deg, #7B8AB6, #A09EBB);
            --shadow: rgba(0, 0, 0, 0.05);
            --page-bg: #d5d5e1;
            --muted: #999;
            
            /* Reference Design Colors */
            --ref-navy: #3D4E6C;
            --ref-purple: #6B7AA1;
            --ref-light-bg: #F5F6FA;
            --ref-lavender-bg: #E8EAF6;
            --ref-button: #6B7AA1;
            
            /* Extended Colors */
            --light-violet: #E8EAF6;
            --shadow-color: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        [data-theme="dark"] {
            --bg: #1a1a2e;
            --text: #e6e6ff;
            --card-bg: #16213e;
            --border: #2d4263;
            --primary: #7B8AB6;
            --secondary: #8a8bb8;
            --accent: #a09ebb;
            --gradient: linear-gradient(135deg, #2d4263, #7B8AB6);
            --shadow: rgba(0, 0, 0, 0.3);
            --page-bg: #0f3460;
            --muted: #8a8bb8;
            
            --ref-navy: #7B8AB6;
            --ref-purple: #8a8bb8;
            --ref-light-bg: #16213e;
            --ref-lavender-bg: #2d4263;
            --ref-button: #7B8AB6;
            
            --light-violet: #2d4263;
            --shadow-color: 0 4px 20px rgba(0, 0, 0, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            transition: background-color 0.3s, color 0.3s;
            min-height: 100vh;
            padding-top: 100px;
        }

        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            height: auto;
            background-color: var(--card-bg);
            box-shadow: 0 2px 15px var(--shadow);
            box-sizing: border-box;
        }

        .logo img {
            height: 50px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            padding: 5px 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a.active {
            color: var(--primary);
        }

        .nav-links a.active::after {
            width: 100%;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--card-bg);
            box-shadow: var(--shadow-color);
            border-radius: 10px;
            padding: 12px 0;
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s;
            z-index: 100;
            border: 1px solid var(--border);
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu a {
            padding: 12px 25px;
            display: block;
            white-space: nowrap;
            border-bottom: none;
        }

        .dropdown-menu a:hover {
            background: var(--light-violet);
        }

        .dropdown-menu a::after {
            display: none;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .theme-toggle {
            background: var(--light-violet);
            border: none;
            font-size: 1.3rem;
            color: var(--primary);
            cursor: pointer;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .theme-toggle:hover {
            transform: rotate(30deg);
            background: var(--primary);
            color: white;
        }

        /* Forum Container */
        .forum-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Forum Form Section */
        .contact-us {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: var(--shadow-color);
            border: 1px solid var(--border);
        }

        .section-heading h2 {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .section-heading h2 em {
            color: var(--accent);
            font-style: normal;
        }

        #contact-form input,
        #contact-form select,
        #contact-form textarea {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 2px solid var(--border);
            border-radius: 10px;
            background: var(--card-bg);
            color: var(--text);
            font-size: 1rem;
            transition: all 0.3s;
        }

        #contact-form input:focus,
        #contact-form select:focus,
        #contact-form textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 138, 182, 0.1);
        }

        .orange-button {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .orange-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 138, 182, 0.3);
        }

        /* Forum Cards */
        .forum-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-color);
            transition: all 0.3s;
            border: 1px solid var(--border);
        }

        .forum-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: var(--primary);
        }

        .forum-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .forum-content {
            font-size: 1rem;
            color: var(--text);
            margin-bottom: 20px;
            line-height: 1.7;
        }

        .forum-footer {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        /* Forum Buttons */
        button.main-button {
            padding: 12px 30px;
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            text-transform: capitalize;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            margin: 10px 10px 10px 0;
        }

        button.main-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 138, 182, 0.3);
        }

        .like-button {
            padding: 10px 20px;
            background: #ff3f6e;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            margin: 10px 10px 10px 0;
            transition: all 0.3s;
        }

        .like-button:hover {
            background: #ff648b;
            transform: translateY(-2px);
        }

        .reply-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            margin: 10px 10px 10px 0;
        }

        .reply-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .edit-button {
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            margin: 10px 10px 10px 0;
        }

        .edit-button:hover {
            background-color: #0b7dda;
            transform: translateY(-2px);
        }

        .delete-btn {
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            margin: 10px 10px 10px 0;
        }

        .delete-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* Reply Form */
        .reply-form {
            background: var(--light-violet);
            border: 2px solid var(--border);
            border-radius: 15px;
            padding: 20px;
            margin-top: 15px;
            box-shadow: var(--shadow-color);
        }

        .reply-form textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border);
            border-radius: 10px;
            background: var(--card-bg);
            color: var(--text);
            min-height: 100px;
            margin-bottom: 10px;
        }

        .reply-form button {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
        }

        /* Edit Form */
        .edit-form {
            background: var(--light-violet);
            border: 2px solid var(--border);
            border-radius: 15px;
            padding: 20px;
            margin-top: 15px;
        }

        .edit-form input,
        .edit-form select,
        .edit-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid var(--border);
            border-radius: 10px;
            background: var(--card-bg);
            color: var(--text);
        }

        .edit-form button {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
        }

        /* Responses Container */
        .responses-container {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        /* Footer */
        .footer {
            background: var(--card-bg);
            padding: 50px 0 30px;
            margin-top: 80px;
            border-top: 1px solid var(--border);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-logo {
            height: 40px;
            margin-bottom: 20px;
        }

        .footer-column h4 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links a {
            color: var(--text);
            text-decoration: none;
            transition: all 0.3s;
            padding: 5px 0;
        }

        .footer-links a:hover {
            color: var(--primary);
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: var(--light-violet);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-link:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            color: var(--text);
            opacity: 0.7;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            
            .nav-links {
                display: none;
            }
            
            .forum-container {
                padding: 20px 15px;
            }
            
            .contact-us {
                padding: 20px;
            }
        }
    </style>
</head>

<body data-theme="light">
<?php if (isset($_GET['submitted']) && $_GET['submitted'] === 'true'): ?>
<div id="success-message" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #4CAF50, #45a049);
    color: white;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    z-index: 9999;
    animation: slideIn 0.5s ease;
    max-width: 400px;
">
    <div style="display: flex; align-items: center; gap: 15px;">
        <div style="font-size: 32px;">✅</div>
        <div>
            <strong style="font-size: 18px; display: block; margin-bottom: 5px;">Success!</strong>
            <p style="margin: 0; font-size: 14px;">Your question has been posted. Our AI bot is preparing a response...</p>
        </div>
        <button onclick="closeSuccessMessage()" style="
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: auto;
        ">×</button>
    </div>
</div>

<style>
@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

@keyframes highlight {
    0%, 100% { 
        transform: scale(1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    50% { 
        transform: scale(1.02);
        box-shadow: 0 8px 24px rgba(76, 175, 80, 0.3);
        border: 2px solid #4CAF50;
    }
}
</style>

<script>
function closeSuccessMessage() {
    const msg = document.getElementById('success-message');
    msg.style.animation = 'slideOut 0.5s ease';
    setTimeout(() => msg.remove(), 500);
    
    // Remove the query parameter from URL
    const url = new URL(window.location);
    url.searchParams.delete('submitted');
    window.history.replaceState({}, '', url);
}

// Auto-close after 5 seconds
setTimeout(() => {
    const msg = document.getElementById('success-message');
    if (msg) closeSuccessMessage();
}, 5000);

// Scroll to the newly posted question
window.addEventListener('load', function() {
    const cards = document.querySelectorAll('.forum-card');
    if (cards.length > 0) {
        cards[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        cards[0].style.animation = 'highlight 2s ease';
    }
});
</script>
<?php endif; ?>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <img src="assets/images/logo.png" alt="Lumina Logo">
        </div>
        <ul class="nav-links">
            <li><a href="newindex.html"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="front/socialcase.php"><i class="fas fa-hands-helping"></i> Get Help</a></li>
            <li class="dropdown">
                <a href="front/jobdepartment.php" class="dropdown-toggle"><i class="fas fa-briefcase"></i> Jobs<i class="fas fa-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="jobdepartment.php#employment-service"><i class="fas fa-search"></i> Job Search</a></li>
                    <li><a href="jobdepartment.php#associations"><i class="fas fa-users"></i> Associations</a></li>
                    <li><a href="jobdepartment.php#application-tracking"><i class="fas fa-tasks"></i> Application Tracking</a></li>
                </ul>
            </li>
            <li><a href="event.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
            <li><a href="forum.php" class="active"><i class="fas fa-comments"></i> Forum</a></li>
            <li><a href="contact.html"><i class="fas fa-envelope"></i> Contact</a></li>
        </ul>
        <div class="nav-actions">
            <button class="theme-toggle" id="theme-toggle">
                <i class="fas fa-sun"></i>
            </button>
        </div>
    </nav>
    
    <!-- Forum Content -->
    <div class="forum-container">
        <div class="contact-us section" id="contact">
            <form id="contact-form" action="../controller/SubmitQuestion.php" method="post">
                <div class="section-heading">
                    <h2><em>Forum</em> Form</h2>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <input type="text" name="title" id="nn" placeholder="Title..." required>
                    <select name="category" id="category" required>
                        <option value="" selected>Select Category</option>
                        <option value="General">General</option>
                        <option value="Programming">Programming</option>
                        <option value="Experience Sharing">Experience Sharing</option>
                        <option value="Help Request">Help Request</option>
                    </select>
                </div>
                <textarea name="content" id="message" placeholder="Content..." required style="min-height: 120px;"></textarea>
                <button type="submit" id="form-submit" class="orange-button">Submit Question</button>
            </form>
        </div>
        <div class="forum-questions">
            <?php foreach($questions as $q): ?>
                <div class="forum-card">
                    <h3 class="forum-title" id="title-<?= $q['id_question'] ?>"><?= htmlspecialchars($q['title']); ?></h3>
                    <p class="forum-content" id="content-<?= $q['id_question'] ?>"><?= nl2br(htmlspecialchars($q['content'])); ?></p>
                    <div class="forum-footer">
                        Category: <?= htmlspecialchars($q['category']); ?> |
                        Posted on: <?= $q['date_question']; ?>
                    </div>
                    
                    <!-- Like Button -->
                    <button class="like-button" onclick="likeQuestion(<?= $q['id_question'] ?>, this)">
                        ❤️ Like (<span class="like-count"><?= $q['likes'] ?></span>)
                    </button>
                    
                    <!-- Show Responses Button -->
                    <button class="main-button" onclick="loadResponses(<?= $q['id_question'] ?>, this)">
                        Show Responses
                    </button>
                    
                    <!-- Edit Button -->
                    <button class="edit-button" onclick="toggleEditForm(<?= $q['id_question'] ?>)">
                        ✏️ Modifier
                    </button>
                    
                    <!-- Delete Button -->
                    <button class="delete-btn" onclick="deleteQuestion(<?= $q['id_question'] ?>)">
                        🗑️ Delete Question
                    </button>
                    
                    <!-- THIS IS THE RESPONSES CONTAINER - IT MUST BE INSIDE THE FORUM-CARD -->
                    <div class="responses-container"></div>
                    
                    <!-- Reply Button -->
                    <button type="button" class="reply-button" onclick="toggleReplyForm(this)">
                        Reply
                    </button>
                    
                    <!-- Reply Form -->
                    <div class="reply-form" style="display:none;">
                        <textarea class="reply-text" placeholder="Write a response..."></textarea>
                        <button type="button" onclick="sendResponse(<?= $q['id_question'] ?>, this)">
                            Submit Reply
                        </button>
                    </div>
                </div>
                
                <!-- Edit Form -->
                <div class="edit-form" id="edit-form-<?= $q['id_question'] ?>" style="display:none; margin-top:15px;">
                    <form onsubmit="submitEditQuestion(event, <?= $q['id_question'] ?>)">
                        <input type="hidden" name="id_question" value="<?= $q['id_question'] ?>">
                        <input type="text" name="title" value="<?= htmlspecialchars($q['title']) ?>" required placeholder="Title">
                        <select name="category" required>
                            <option value="General" <?= $q['category']=="General"?"selected":"" ?>>General</option>
                            <option value="Programming" <?= $q['category']=="Programming"?"selected":"" ?>>Programming</option>
                            <option value="Experience Sharing" <?= $q['category']=="Experience Sharing"?"selected":"" ?>>Experience Sharing</option>
                            <option value="Help Request" <?= $q['category']=="Help Request"?"selected":"" ?>>Help Request</option>
                        </select>
                        <textarea name="content" required placeholder="Content"><?= htmlspecialchars($q['content']) ?></textarea>
                        <button type="submit">✅ Enregistrer</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <img src="assets/images/logo.png" alt="Lumina Logo" class="footer-logo">
                <p style="color: var(--text); opacity: 0.8; margin-bottom: 20px;">Supporting communities through technology and care.</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <h4>Quick Links</h4>
                <div class="footer-links">
                    <a href="newindex.html">Home</a>
                    <a href="front/socialcase.php">Get Help</a>
                    <a href="front/jobdepartment.php">Jobs</a>
                    <a href="event.php">Events</a>
                    <a href="forum.php">Forum</a>
                </div>
            </div>
            <div class="footer-column">
                <h4>Support</h4>
                <div class="footer-links">
                    <a href="contact.html">Contact Us</a>
                    <a href="about.html">About Us</a>
                    <a href="faqs.html">FAQs</a>
                </div>
            </div>
            <div class="footer-column">
                <h4>Contact Info</h4>
                <div class="footer-links">
                    <a href="tel:+21654271233"><i class="fas fa-phone"></i> +(216) 54 271 233</a>
                    <a href="mailto:lumina@socialservice.com"><i class="fas fa-envelope"></i> lumina@socialservice.com</a>
                    <a href="#"><i class="fas fa-map-marker-alt"></i> 123 Al Ghazela, Ariana Soghra, Tunis</a>
                </div>
            </div>
        </div>
        <div class="copyright">
            © 2025 Lumina Community Support. All rights reserved. | Designed with <i class="fas fa-heart" style="color: var(--primary);"></i> for our community
        </div>
    </footer>

    <script src="../assets/js/script.js"></script>
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;
        const themeIcon = themeToggle.querySelector('i');
        
        const savedTheme = localStorage.getItem('theme') || 
                          (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        
        body.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
        
        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'fas fa-moon';
            } else {
                themeIcon.className = 'fas fa-sun';
            }
        }
    </script>
    <!-- LumiBot Chatbot -->
<div id="lumibot">
    <button id="lumibot-toggle">💬 LumiBot</button>

    <div id="lumibot-window">
        <div id="lumibot-header">
            <strong>LumiBot</strong>
            <span id="lumibot-close">×</span>
        </div>

        <div id="lumibot-messages">
            <div class="bot">
                Hi 👋 I’m <strong>LumiBot</strong>.<br>
                I can help you use the forum. Try asking:
                <ul>
                    <li>How do I post a question?</li>
                    <li>What are the categories?</li>
                    <li>How do likes work?</li>
                </ul>
            </div>
        </div>

        <div id="lumibot-input">
            <input type="text" id="lumibot-text" placeholder="Ask LumiBot..." />
            <button id="lumibot-send">Send</button>
        </div>
    </div>
</div>
<style>
#lumibot {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

#lumibot-toggle {
    background: var(--gradient);
    color: white;
    border: none;
    padding: 14px 18px;
    border-radius: 30px;
    cursor: pointer;
    font-weight: 600;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

#lumibot-window {
    width: 320px;
    height: 420px;
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: var(--shadow-color);
    display: none;
    flex-direction: column;
    overflow: hidden;
    margin-bottom: 10px;
}

#lumibot-header {
    background: var(--gradient);
    color: white;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

#lumibot-close {
    cursor: pointer;
    font-size: 20px;
}

#lumibot-messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    font-size: 14px;
}

#lumibot-messages .bot,
#lumibot-messages .user {
    margin-bottom: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    max-width: 90%;
}

#lumibot-messages .bot {
    background: var(--light-violet);
}

#lumibot-messages .user {
    background: var(--primary);
    color: white;
    margin-left: auto;
}

#lumibot-input {
    display: flex;
    border-top: 1px solid var(--border);
}

#lumibot-input input {
    flex: 1;
    border: none;
    padding: 10px;
    outline: none;
    background: var(--card-bg);
    color: var(--text);
}

#lumibot-input button {
    border: none;
    padding: 10px 15px;
    background: var(--primary);
    color: white;
    cursor: pointer;
}
</style>
<script>
(function () {
    const toggleBtn = document.getElementById('lumibot-toggle');
    const windowEl = document.getElementById('lumibot-window');
    const closeBtn = document.getElementById('lumibot-close');
    const sendBtn = document.getElementById('lumibot-send');
    const input = document.getElementById('lumibot-text');
    const messages = document.getElementById('lumibot-messages');

    toggleBtn.onclick = () => windowEl.style.display = 'flex';
    closeBtn.onclick = () => windowEl.style.display = 'none';

    sendBtn.onclick = sendMessage;
    input.addEventListener('keypress', e => {
        if (e.key === 'Enter') sendMessage();
    });

    function sendMessage() {
        const text = input.value.trim();
        if (!text) return;

        addMessage(text, 'user');
        input.value = '';

        setTimeout(() => {
            addMessage(getBotReply(text), 'bot');
        }, 400);
    }

    function addMessage(text, type) {
        const div = document.createElement('div');
        div.className = type;
        div.innerHTML = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function getBotReply(message) {
        const msg = message.toLowerCase();

        if (msg.includes('post') || msg.includes('question')) {
            return "To post a question, fill in the <strong>Forum Form</strong> at the top and click <em>Submit Question</em>.";
        }

        if (msg.includes('category')) {
            return "Available categories are:<br>• General<br>• Programming<br>• Experience Sharing<br>• Help Request";
        }

        if (msg.includes('like')) {
            return "Click the ❤️ <strong>Like</strong> button on a question to support it.";
        }

        if (msg.includes('reply') || msg.includes('answer')) {
            return "Click <strong>Reply</strong> under a question, write your response, and submit it.";
        }

        if (msg.includes('edit') || msg.includes('delete')) {
            return "You can edit ✏️ or delete 🗑️ your question using the buttons below it.";
        }

        return "I’m here to help with forum usage 🙂 Try asking about posting, replies, categories, or likes.";
    }
})();
</script>

</body>
</html>
