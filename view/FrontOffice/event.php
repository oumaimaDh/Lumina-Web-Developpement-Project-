<script>
// BLOCK ALL COOKIES from being set
Object.defineProperty(document, 'cookie', {
    get: function() { return ''; },
    set: function() { return false; }
});

// Clear existing cookies
document.cookie.split(";").forEach(function(c) {
    document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
});

// Check if user is logged in via localStorage
const user = localStorage.getItem('currentUser');
if (!user) {
    // Not logged in - redirect to login
    window.location.href = 'auth_guard.html';
    throw new Error('Not authenticated');
}
</script>

<?php


// Include database config
require_once "../../config/db.php";

// Now you're guaranteed to be logged in
$db = new Database();
$pdo = $db->connect();
// Fetch events
$events = $pdo->query("SELECT * FROM events ORDER BY date ASC")->fetchAll();

// Now display your events page content
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Lumina — Events</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
     <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-tale-seo-agency.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet"href="https://unpkg.com/swiper@7/swiper-bundle.min.css"/>
<link rel="stylesheet" href="event.css">    
<link rel="stylesheet" href="styles.css">    
<link rel="icon" type="image/png" href="ss.png">
  <style>
        :root {
            /* Light Theme - Snowy Twilight Palette */
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            background: var(--page-bg) !important;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--text);
            overflow-x: hidden;
        }

        body {
            transition: background-color 0.3s, color 0.3s;
            position: relative;
        }

   /* DARK THEME - Apply to entire document */
[data-theme="dark"] {
    /* Dark Theme Variables */
    --bg: #2c2c3e;
    --text: #f0f0f0;
    --card-bg: #34495e;
    --border: #444;
    --primary: #7B8AB6;
    --secondary: #A09EBB;
    --accent: #575D7F;
    --gradient: linear-gradient(135deg, #7B8AB6, #A09EBB);
    --navbar-bg: rgba(30, 30, 50, 0.9);
    --footer-bg: #16213e;
    --form-bg: #252536;
    --shadow: rgba(0, 0, 0, 0.2);
    --btn-outline-color: #7B8AB6;
    --page-bg: #1a1a2e;
    --muted: #aaa;
    --ref-navy: #a0a8c0;
    --ref-purple: #8a9bcc;
    --ref-light-bg: #2a2a3e;
    --ref-lavender-bg: #2d2d44;
}


[data-theme="dark"] body {
    background: var(--page-bg) !important;
}

/* Explicitly set navbar links to their original color */
[data-theme="dark"] .navbar a,
[data-theme="dark"] .nav-links a,
[data-theme="dark"] .nav-item {
    color: var(--accent) !important; /* Or any color you want */
}

[data-theme="dark"] .navbar a:hover,
[data-theme="dark"] .nav-links a:hover,
[data-theme="dark"] .nav-item:hover {
    color: var(--primary) !important; /* Hover color */
}

/* Dark theme specific overrides */
[data-theme="dark"] .about-section {
    background: var(--bg);
}

[data-theme="dark"] .about-card-enhanced {
    background: var(--card-bg);
    border-color: var(--border);
}

[data-theme="dark"] .impact-section {
    background: var(--card-bg);
    border-color: var(--border);
}

[data-theme="dark"] .events-header {
    background: var(--ref-lavender-bg);
}

[data-theme="dark"] .filter-tab {
    background: var(--card-bg);
    color: var(--text);
    border-color: var(--border);
}

[data-theme="dark"] .filter-tab.active {
    background: var(--ref-purple);
    color: white;
}

[data-theme="dark"] .stat-item {
    background: var(--card-bg);
}

[data-theme="dark"] .stat-value {
    color: var(--text);
}

[data-theme="dark"] .stat-text {
    color: var(--muted);
}

[data-theme="dark"] .projects-grid .project-card {
    background: var(--card-bg);
    border-color: var(--border);
}

[data-theme="dark"] .footer {
    background: var(--footer-bg);
}

[data-theme="dark"] .hero {
    background: linear-gradient(135deg, #3a3a5e, #2d2d4a);
}

[data-theme="dark"] .event-marquee {
    background: var(--primary);


}


/* Ensure body has the data-theme attribute */
body[data-theme="dark"] {
    background: var(--page-bg) !important;
}
.theme-toggle {
    background: none;
    border: none;
    font-size: 1.4rem;
    color: var(--text);
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.3s;
}

.theme-toggle:hover {
    background: var(--border);
}


        /* About Section */
        .about-section {
            padding: 6rem 2rem;
            background: var(--bg);
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 300px;
            background: linear-gradient(180deg, var(--primary) 0%, transparent 100%);
            opacity: 0.04;
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
            position: relative;
            z-index: 1;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-badge {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: var(--gradient);
            color: white;
            border-radius: 50px;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 25px rgba(123, 138, 182, 0.35);
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .section-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--text);
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: var(--muted);
            max-width: 750px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Enhanced About Cards */
        .about-cards-enhanced {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
            margin-bottom: 5rem;
        }

        .about-card-enhanced {
            background: var(--card-bg);
            border-radius: 28px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 15px 50px var(--shadow);
            border: 2px solid var(--border);
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .about-card-enhanced::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(123, 138, 182, 0.1) 0%, transparent 70%);
            transition: all 0.8s ease;
            transform: scale(0);
            opacity: 0;
        }

        .about-card-enhanced:hover::before {
            transform: scale(1);
            opacity: 1;
        }

        .about-card-enhanced:hover {
            transform: translateY(-20px) scale(1.02);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.2);
            border-color: var(--primary);
        }

        .card-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(123, 138, 182, 0.12) 0%, transparent 70%);
        }

        .circle-1 {
            width: 180px;
            height: 180px;
            top: -60px;
            right: -60px;
            animation: float-decoration 10s infinite ease-in-out;
        }

        .circle-2 {
            width: 120px;
            height: 120px;
            bottom: -40px;
            left: -40px;
            animation: float-decoration 12s infinite ease-in-out reverse;
        }

        .decoration-line {
            position: absolute;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            opacity: 0.2;
        }

        .line-1 {
            width: 100%;
            bottom: 0;
            left: 0;
            animation: shimmer 3s infinite ease-in-out;
        }

        @keyframes float-decoration {
            0%, 100% {
                transform: translate(0, 0);
            }
            50% {
                transform: translate(25px, -25px);
            }
        }

        @keyframes shimmer {
            0%, 100% {
                opacity: 0.1;
            }
            50% {
                opacity: 0.3;
            }
        }

        .card-icon-wrapper-enhanced {
            width: 120px;
            height: 120px;
            margin: 0 auto 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            position: relative;
            z-index: 1;
            transition: all 0.5s ease;
            box-shadow: 0 10px 30px rgba(123, 138, 182, 0.2);
        }

        .mission-card .card-icon-wrapper-enhanced {
            background: linear-gradient(135deg, rgba(123, 138, 182, 0.15), rgba(123, 138, 182, 0.25));
            color: #7B8AB6;
        }

        .vision-card .card-icon-wrapper-enhanced {
            background: linear-gradient(135deg, rgba(160, 158, 187, 0.15), rgba(160, 158, 187, 0.25));
            color: #A09EBB;
        }

        .values-card .card-icon-wrapper-enhanced {
            background: linear-gradient(135deg, rgba(87, 93, 127, 0.15), rgba(87, 93, 127, 0.25));
            color: #575D7F;
        }

        .about-card-enhanced:hover .card-icon-wrapper-enhanced {
            transform: scale(1.2) rotate(15deg);
            box-shadow: 0 15px 40px rgba(123, 138, 182, 0.4);
        }

        .about-card-enhanced h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: var(--text);
            position: relative;
            z-index: 1;
        }

        .about-card-enhanced p {
            color: var(--muted);
            line-height: 1.9;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
            font-size: 1.05rem;
        }

        .card-stats {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }

        .stat-mini {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, rgba(123, 138, 182, 0.1), rgba(123, 138, 182, 0.15));
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary);
            border: 1px solid rgba(123, 138, 182, 0.2);
            transition: all 0.3s ease;
        }

        .about-card-enhanced:hover .stat-mini {
            background: var(--gradient);
            color: white;
            border-color: transparent;
            transform: scale(1.05);
        }

        .stat-mini svg {
            flex-shrink: 0;
        }

        /* Impact Section */
        .impact-section {
            background: var(--card-bg);
            border-radius: 28px;
            padding: 3rem;
            box-shadow: 0 15px 50px var(--shadow);
            border: 2px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .impact-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 10% 20%, rgba(123, 138, 182, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(160, 158, 187, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .impact-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--border);
            position: relative;
            z-index: 1;
        }

        .impact-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 30px rgba(123, 138, 182, 0.3);
            flex-shrink: 0;
        }

        .impact-header h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 0.5rem 0;
        }

        .impact-header p {
            font-size: 1.1rem;
            color: var(--muted);
            margin: 0;
        }

        /* Impact Metrics */
        .impact-metrics {
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .metric-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1;
            min-width: 200px;
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(123, 138, 182, 0.06) 0%, rgba(160, 158, 187, 0.04) 100%);
            border-radius: 20px;
            border: 2px solid rgba(123, 138, 182, 0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 5px;
            height: 100%;
            background: var(--gradient);
            transform: scaleY(0);
            transition: transform 0.4s ease;
        }

        .metric-item:hover::before {
            transform: scaleY(1);
        }

        .metric-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(123, 138, 182, 0.25);
            border-color: var(--primary);
        }

        .metric-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(123, 138, 182, 0.3);
            transition: all 0.3s ease;
        }

        .metric-item:hover .metric-icon {
            transform: rotate(10deg) scale(1.1);
        }

        .metric-number {
            color: #e5e9f2ff;
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.25rem;
            line-height: 1;
        }

        .metric-label {
            font-size: 1rem;
            color: var(--muted);
            font-weight: 600;
        }

        .metric-divider {
            width: 2px;
            height: 80px;
            background: linear-gradient(to bottom, transparent, var(--border), transparent);
        }

        /* Events Section */
        .events-section {
            max-width: 1400px;
            margin: 4rem auto;
            padding: 0 2rem;
            position: relative;
        }

        .events-header {
            text-align: center;
            margin-bottom: 4rem;
            position: relative;
            padding: 4rem 3rem;
            background: var(--ref-lavender-bg);
            border-radius: 32px;
            box-shadow: 0 8px 30px rgba(61, 78, 108, 0.12);
            border: none;
            overflow: hidden;
        }

        /* Background Decorations */
        .events-background-decoration {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
            opacity: 0.4;
        }

        .decoration-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(50px);
            opacity: 0.25;
            animation: blob-float 15s infinite ease-in-out;
        }

        .blob-left {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(107, 122, 161, 0.3), transparent);
            top: -120px;
            left: -120px;
        }

        .blob-right {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(107, 122, 161, 0.25), transparent);
            bottom: -100px;
            right: -100px;
            animation-delay: -7s;
        }

        @keyframes blob-float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(20px, -20px) scale(1.08);
            }
            66% {
                transform: translate(-15px, 15px) scale(0.95);
            }
        }

        .header-content {
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .title-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 1.25rem;
            flex-direction: column;
        }

        .events-header h2 {
            font-size: 2.75rem;
            font-weight: 800;
            color: var(--ref-navy);
            margin: 0;
            line-height: 1.3;
        }

        .events-subtitle {
            font-size: 1.1rem;
            color: #6B7AA1;
            max-width: 650px;
            margin: 0 auto;
            line-height: 1.6;
            font-weight: 500;
        }

        .filter-tabs {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
            padding: 0;
            background: transparent;
            border-radius: 0;
            max-width: 100%;
            margin: 0 auto 2.5rem;
            position: relative;
            z-index: 1;
            border: none;
            box-shadow: none;
        }

        .filter-tab {
            padding: 0.85rem 2rem;
            border-radius: 25px;
            border: 2px solid #D5D8E8;
            background: white;
            color: var(--ref-navy);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(61, 78, 108, 0.08);
            position: relative;
            overflow: visible;
        }

        .filter-tab:hover {
            background: #F5F6FA;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(61, 78, 108, 0.12);
            border-color: var(--ref-purple);
        }

        .filter-tab.active {
            background: var(--ref-purple);
            color: white;
            box-shadow: 0 4px 16px rgba(107, 122, 161, 0.3);
            transform: translateY(0);
            border-color: var(--ref-purple);
        }

        .events-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            max-width: 100%;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.75rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(61, 78, 108, 0.1);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: visible;
        }

        .stat-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 24px rgba(61, 78, 108, 0.18);
        }

        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
            background: var(--ref-purple);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 6px 18px rgba(107, 122, 161, 0.25);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .stat-item:hover .stat-icon-wrapper {
            transform: scale(1.08);
            box-shadow: 0 8px 22px rgba(107, 122, 161, 0.35);
        }

        .stat-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--ref-navy);
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .stat-text {
            font-size: 0.95rem;
            color: #6B7AA1;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }

            .about-cards-enhanced {
                grid-template-columns: 1fr;
            }

            .impact-metrics {
                flex-direction: column;
            }

            .metric-divider {
                display: none;
            }

            .events-header h2 {
                font-size: 2rem;
            }

            .events-stats {
                grid-template-columns: 1fr;
            }
        }
              :root {
            /* Light Theme - Snowy Twilight Palette */
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            background: var(--page-bg) !important;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--text);
            overflow-x: hidden;
        }

        body {
            transition: background-color 0.3s, color 0.3s;
            position: relative;
        }

        /* Hero Section */
        /* Hero Section */
.hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 0 2rem;
    background: linear-gradient(135deg, #5f6ca5ff, #5a4d93ff); /* Medium blue */
    color: white;
    position: relative;
    overflow: hidden;
    margin-top: 0;
}


        .hero-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 20s infinite ease-in-out;
        }

        .blob-1 {
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.2);
            top: -100px;
            left: -100px;
        }

        .blob-2 {
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.15);
            bottom: -50px;
            right: 10%;
            animation-delay: -10s;
        }

        .blob-3 {
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.1);
            top: 50%;
            right: -50px;
            animation-delay: -5s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(50px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-30px, 30px) scale(0.9);
            }
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 1;
            padding: 8rem 0 4rem;
        }

        .hero-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: rgba(211, 223, 238, 0.2);
            border-radius: 50px;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeInDown 0.8s ease-out;
        }

        .hero-badge span {
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .gradient-text {
            background: linear-gradient(135deg, #fff, #f0e6ff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 900px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .stat {
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat:hover {
            transform: translateY(-10px);
        }

        .stat-number {
            color: #ffffff !important;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #ffffff !important;
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Event Marquee */
        .event-marquee {
            width: 100%;
            max-width: 100%;
            margin: 0;
            background: var(--primary);
            color: white;
            padding: 12px 0;
            border-radius: 0;
            overflow: hidden;
            position: relative;
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee-loop 20s linear infinite;
        }

        .marquee-content {
            display: inline-block;
            white-space: nowrap;
            font-size: 18px;
            font-weight: 600;
            padding-right: 60px;
        }

        @keyframes marquee-loop {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-stats {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .hero-content {
                padding: 6rem 0 3rem;
            }

            .marquee-content {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-badge span {
                font-size: 0.85rem;
            }
        }
        .navbar {
  display: flex;
  justify-content: space-between;
  padding: 35px 50px;
  position: absolute;
  width: 100% ;
  top: 0;
  left: 0;
  z-index: 1;
  height: auto;
}
.brand a {
  letter-spacing: 6px;
  font-weight: bold;
}
/* Style For Links*/
.nav-item {
  position: relative;
  overflow: hidden;
  padding: 15px 0px 10px 0px;
  margin: 0 15px;
  letter-spacing: 1px;
  font-weight: bold;
}
.nav-item:after {
  content: "";
  position: absolute;
  bottom: 4px;
  right: 0;
  width: 0%;
  height: 2px;
  background: var(--text-color);
  transition: all 0.32s ease-out;
}
.nav-item:hover {
  color: var(--text-color);
}
.nav-item:hover:after {
  left: 0;
  width: 100%;
}
.navbar-toggle {
  position: absolute;
  display: none;
  right: 35px;
  top: 16px;
  width: 30px;
  height: 30px;
  z-index: 1;
}
.toggle-bar {
  width: 20px;
  height: 2px;
  background: var(--link-text);
  top: 10px;
  right: 5px;
  position: absolute;
  transition: 0.26s ease all;
}
.toggle-bar:nth-child(2) {
  top: 15px;
}
.toggle-bar:nth-child(3) {
  top: 20px;
}
@media screen and (min-width:769px) {
    .navbar[data-expanded="true"] {
        height:auto !important;
    }
}
@media screen and (max-width: 1191px) {
  .navbar {
    padding: 35px 32px;
    width: calc(100% - 64px);
  }
}
@media screen and (max-width: 768px) {
  .navbar {
    flex-direction: column;
    justify-content: normal;
    background: var(--bg-color);
    padding: 10px 32px;
    height: 40px;
  }
  li {
    display: block;
    margin: 0;
  }
  .brand a {
    margin: 15px 0px;
  }
  .brand a,
  .nav-item {
    color: var(--link-text);
  }
  .brand a:hover,
  .nav-item:hover {
    color: var(--link-text);
  }
  .nav-item {
    margin: 0px;
  }
  .nav-item:after,
  .nav-item:hover:after {
    background: var(--link-text);
  }
  .navbar-toggle {
    display: block;
  }
  .navbar[data-theme="light"] {
    --link-text: #000;
  }
  .navbar[data-theme="dark"] {
    --link-text: #fff;
  }
  .navbar[data-expanded="false"] li > .nav-item,
  .navbar[data-expanded="false"] .nav-right > .nav-item {
    clip-path: polygon(0% 0%, 0% 0%, 0% 100%, 0% 100%);
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.16s ease 0.16s, visibility 0.01s ease 0.16s,
      clip-path 0.16s ease;
  }
  .navbar[data-expanded="true"] li > .nav-item,
  .navbar[data-expanded="true"] .nav-right > .nav-item {
    clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%);
    visibility: visible;
    opacity: 1;
  }
  .navbar[data-expanded="true"] li:first-child > .nav-item {
    transition: opacity 0.16s ease 0.16s, visibility 0.01s ease,
      clip-path 0.16s ease 0.16s;
  }
  .navbar[data-expanded="true"] li:nth-child(2) > .nav-item {
    transition: opacity 0.16s ease 0.2s, visibility 0.01s ease,
      clip-path 0.16s ease 0.2s;
  }
  .navbar[data-expanded="true"] li:nth-child(3) > .nav-item {
    transition: opacity 0.16s ease 0.24s, visibility 0.01s ease,
      clip-path 0.16s ease 0.24s;
  }
  .navbar[data-expanded="true"] li:nth-child(4) > .nav-item {
    transition: opacity 0.16s ease 0.28s, visibility 0.01s ease,
      clip-path 0.16s ease 0.28s;
  }
  .navbar[data-expanded="true"] li:nth-child(5) > .nav-item {
    transition: opacity 0.16s ease 0.32s, visibility 0.01s ease,
      clip-path 0.16s ease 0.32s;
  }
  .navbar[data-expanded="true"] li:nth-child(6) > .nav-item {
    transition: opacity 0.16s ease 0.36s, visibility 0.01s ease,
      clip-path 0.16s ease 0.36s;
  }
  .navbar[data-expanded="true"] .nav-right > .nav-item {
    transition: opacity 0.16s ease 0.4s, visibility 0.01s ease,
      clip-path 0.16s ease 0.4s;
  }
  .navbar[data-expanded="true"] {
    height: 100%;
  }
  .navbar[data-expanded="true"] .navbar-toggle > .toggle-bar:first-child {
    transform: rotate(135deg);
    top: 15px;
  }
  .navbar[data-expanded="true"] .navbar-toggle > .toggle-bar:nth-child(2) {
    opacity: 0;
  }
  .navbar[data-expanded="true"] .navbar-toggle > .toggle-bar:nth-child(3) {
    transform: rotate(-135deg);
    top: 15px;
  }
}
a {
  text-decoration: none;
  color: var(--text-color);
  display: inline-block;
  margin: 15px;
}
a:hover {
  color: inherit;
}
ul {
  list-style-type: none;
  padding-left: 0;
  margin: 0;
}
li {
  display: inline-block;
  margin: 0 15px;
}
li a {
  margin: 0px 15px;
}
    </style>
</head>
<body>

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
            <li><a href="forum.php"><i class="fas fa-comments"></i> Forum</a></li>
            
            <li><a href="contact.html"><i class="fas fa-comments"></i> Contact</a></li>
          
        </ul>
        <div class="nav-actions">
            <button class="theme-toggle" id="theme-toggle">
                <i class="fas fa-sun"></i>
            </button>
        </div>
    </nav>

<div>
      <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-blob blob-1"></div>
        <div class="hero-blob blob-2"></div>
        <div class="hero-blob blob-3"></div>
        
        <div class="hero-content">
            <div class="hero-badge">
                <span>Made in Tunisia, For Tunisia</span>
            </div>
            <h1 class="hero-title">
                Welcome to Your Community of <span class="gradient-text">Change-Makers</span>
            </h1>
            <p class="hero-subtitle">
                Join hundreds of passionate Tunisians making a real difference. Discover initiatives, connect with volunteers, and be part of something bigger!
            </p>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-number" data-target="500">500</div>
                    <div class="stat-label">Active Volunteers</div>
                </div>
                <div class="stat">
                    <div class="stat-number" data-target="150">150</div>
                    <div class="stat-label">Community Projects</div>
                </div>
                <div class="stat">
                    <div class="stat-number" data-target="25">25</div>
                    <div class="stat-label">Cities Across Tunisia</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Marquee -->
    <div class="event-marquee">
        <div class="marquee-track">
            <span class="marquee-content">. New: Community Health Initiative - Feb 15 . Environmental Clean-up Day - March 10 . Food Aid Distribution - Feb 28 </span>
            <span class="marquee-content">. New: Community Health Initiative - Feb 15 . Environmental Clean-up Day - March 10 . Food Aid Distribution - Feb 28 </span>
        </div>
    </div>

    <script>
        // Counter animation for stats (optional enhancement)
        function animateCounter(element, target) {
            const duration = 2000; // 2 seconds
            const start = 0;
            const increment = target / (duration / 16); // 60fps
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }

        // Trigger counter animation on page load
        window.addEventListener('load', () => {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-target'));
                animateCounter(stat, target);
            });
        });
    </script>
    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">About Us</div>
                <h2 class="section-title">We're Here to Make Impact Easy</h2>
                <p class="section-subtitle">
                    Lumina connects passionate people with meaningful projects. Whether you want to volunteer, start an initiative, or just stay inspired—we've got you covered!
                </p>
            </div>

            <div class="about-cards-enhanced">
                <div class="about-card-enhanced mission-card">
                    <div class="card-decoration">
                        <div class="decoration-circle circle-1"></div>
                        <div class="decoration-circle circle-2"></div>
                        <div class="decoration-line line-1"></div>
                    </div>
                    <div class="card-icon-wrapper-enhanced">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To create a vibrant community where every Tunisian can easily find and join initiatives that match their passion and skills.</p>
                    <div class="card-stats">
                        <div class="stat-mini">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>1000+ Members</span>
                        </div>
                    </div>
                </div>

                <div class="about-card-enhanced vision-card">
                    <div class="card-decoration">
                        <div class="decoration-circle circle-1"></div>
                        <div class="decoration-circle circle-2"></div>
                        <div class="decoration-line line-1"></div>
                    </div>
                    <div class="card-icon-wrapper-enhanced">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <h3>Our Vision</h3>
                    <p>A Tunisia where social impact is accessible to everyone, and communities thrive through collaboration and positive action.</p>
                    <div class="card-stats">
                        <div class="stat-mini">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </svg>
                            <span>All Tunisia</span>
                        </div>
                    </div>
                </div>

                <div class="about-card-enhanced values-card">
                    <div class="card-decoration">
                        <div class="decoration-circle circle-1"></div>
                        <div class="decoration-circle circle-2"></div>
                        <div class="decoration-line line-1"></div>
                    </div>
                    <div class="card-icon-wrapper-enhanced">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h3>Our Values</h3>
                    <p>Transparency, inclusivity, and empowerment. We believe in the power of community and the impact of small actions.</p>
                    <div class="card-stats">
                        <div class="stat-mini">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                <path d="M12 4v1M17.66 6.34l-.7.7M20 12h-1M17.66 17.66l-.7-.7M12 19v1M6.34 17.66l.7-.7M4 12h1M6.34 6.34l.7.7"></path>
                            </svg>
                            <span>Impact First</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="impact-section">
                <div class="impact-header">
                    <div class="impact-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                            <polyline points="16 7 22 7 22 13"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h3>Our Impact Across Tunisia</h3>
                        <p>Making a real difference in communities nationwide</p>
                    </div>
                </div>
                
                <div class="impact-metrics">
                    <div class="metric-item">
                        <div class="metric-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="metric-number">500+</div>
                            <div class="metric-label">Active Volunteers</div>
                        </div>
                    </div>
                    <div class="metric-divider"></div>
                    <div class="metric-item">
                        <div class="metric-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="6"></circle>
                                <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="metric-number">150+</div>
                            <div class="metric-label">Completed Projects</div>
                        </div>
                    </div>
                    <div class="metric-divider"></div>
                    <div class="metric-item">
                        <div class="metric-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                        </div>
                        <div>
                            <div class="metric-number">25+</div>
                            <div class="metric-label">Cities Reached</div>
                        </div>
                    </div>
                    <div class="metric-divider"></div>
                    <div class="metric-item">
                        <div class="metric-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="metric-number">10K+</div>
                            <div class="metric-label">Lives Impacted</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <div class="events-section" id="events">
        <div class="events-header">
            <div class="events-background-decoration">
                <div class="decoration-blob blob-left"></div>
                <div class="decoration-blob blob-right"></div>
            </div>
            
            <div class="header-content">
                <div class="title-group">
                    <h2>Discover Amazing Events</h2>
                </div>
                <p class="events-subtitle">
                    Join incredible experiences and make a difference in your community
                </p>
            </div>
            
          <div class="filter-tabs">
    <a href="topic-template.html?category=all" class="filter-tab active">All</a>
    <a href="topic-template.html?category=health" class="filter-tab">Health</a>
    <a href="topic-template.html?category=environment" class="filter-tab">Environment</a>
    <a href="topic-template.html?category=charity" class="filter-tab">Charity</a>
    <a href="topic-template.html?category=education" class="filter-tab">Education</a>
    <a href="topic-template.html?category=community" class="filter-tab">Community</a>
</div>
            <div class="events-stats">
                <div class="stat-item">
                    <div class="stat-icon-wrapper">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                            <line x1="16" x2="16" y1="2" y2="6"></line>
                            <line x1="8" x2="8" y1="2" y2="6"></line>
                            <line x1="3" x2="21" y1="10" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-value">6</div>
                        <div class="stat-text">Events Available</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon-wrapper">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                            <polyline points="16 7 22 7 22 13"></polyline>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-value">500+</div>
                        <div class="stat-text">Participants</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon-wrapper">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-value">25+</div>
                        <div class="stat-text">Cities</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <main class="main-content">
            <div id="eventList" class="fade-in">
               
               <div class="projects-grid">
<?php
// === 1. List of event images (add more if you want) ===
$eventImages = [
    "https://images.unsplash.com/photo-1503264116251-35a269479413?w=800",
    "https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?w=800",
    "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=800",
    "https://images.unsplash.com/photo-1517487881594-2787fef5ebf7?w=800",
    "https://images.unsplash.com/photo-1526045612212-70caf35c14df?w=800",
    "https://images.unsplash.com/photo-1482192596544-9eb780fc7f66?w=800"
];

$imgIndex = 0; // used to rotate images for each event
?>

<?php foreach ($events as $e): ?>
    <?php 
        // Pick image for this event
        $currentImage = $eventImages[$imgIndex % count($eventImages)];
        $imgIndex++; 
    ?>

    <div class="project-card">

        <div class="project-image">
            <img src="<?= $currentImage ?>" alt="Event Image">

            <!-- Category badge -->
            <div class="project-badge">
                <?= !empty($e["category"]) ? htmlspecialchars($e["category"]) : "Event" ?>
            </div>
        </div>

        <div class="project-content">
            <h3><?= htmlspecialchars($e["title"]) ?></h3>

            <p><?= htmlspecialchars(substr($e["description"], 0, 120)) ?>...</p>

            <div class="project-meta">
                <span class="meta-item">📍 <?= htmlspecialchars($e["location"]) ?></span>
                <span class="meta-item">📅 <?= date("d M Y", strtotime($e["date"])) ?></span>
            </div>

            <button class="btn-join"
                onclick="openEvent(
                    '<?= addslashes($e['title']) ?>',
                    '<?= addslashes($e['location']) ?>',
                    '<?= date('d M Y', strtotime($e['date'])) ?>',
                    '<?= addslashes($e['description']) ?>',
                    <?= $e['id'] ?>
                )">
                Join Event →
            </button>
        </div>
    </div>
<?php endforeach; ?>

</div>



            </div>

            <div id="eventDetails" class="fade-in" style="display:none;">
                <button class="back-btn" onclick="goBack()">← Back to events</button>

                <h2 id="detailTitle"></h2>
                <p><strong>Location:</strong> <span id="detailLocation"></span></p>
                <p><strong>Date:</strong> <span id="detailDate"></span></p>
                <p id="detailDescription"></p>

                <h3>Join This Event</h3>
    <form id="joinForm" action="../../controller/addParticipants.php " method="POST">
    <input type="text" name="firstName" id="firstName" placeholder="Your Name">
    <input type="text" name="lastName" id="lastName" placeholder="Last Name">
    <input type="email" name="userEmail" id="userEmail" placeholder="Your Email">
    <input type="text" name="userPhone" id="userPhone" placeholder="Phone Number (8 digits)">
    <!-- Add a hidden input for event_id -->
    <input type="hidden" id="event_id" name="event_id">

    <button type="submit" class="submit">Join Event</button>
</form>

                <div id="confirmation"></div>
            </div>

        </main>
    </div>
</div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <h3>Lumina</h3>
                <p>An innovative national solidarity platform in Tunisia that efficiently connects citizens with the resources they need..</p>
            </div>
            <div class="footer-column">
                <h3>Contact</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> Tunis, Tunisia</a></li>
                    <li><a href="mailto:contact@lumina.tn"><i class="fas fa-envelope"></i> contact@lumina.tn</a></li>
                    <li><a href="tel:+21670123456"><i class="fas fa-phone"></i>+216 70 123 456</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Réseaux sociaux</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li><a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Lumina- Plateforme of Solidarity Nationale Tunisian. Every rights are reserved.</p>
        </div>
    </footer>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const joinForm = document.getElementById('joinForm');
    
    if (joinForm) {
        joinForm.addEventListener('submit', function(e) {
            // COMPLETELY prevent any default behavior
            e.preventDefault();
            e.stopImmediatePropagation();
            
            // Show animation immediately
            showSuccessAnimation();
            
            // Hide the form
            this.style.display = 'none';
            
            // Submit silently using fetch with no-cors mode
            const formData = new FormData(this);
            
            // This will send the data but won't read any response
            fetch('../../controller/addParticipants.php', {
                method: 'POST',
                body: formData,
                mode: 'no-cors' // Critical: prevents reading response
            }).catch(() => {}); // Ignore all errors
            
            return false; // Additional prevention
        });
    }
// Theme Toggle Functionality
const themeToggle = document.getElementById('theme-toggle');
const themeIcon = themeToggle.querySelector('i');

// Check for saved theme preference or default to light
const currentTheme = localStorage.getItem('theme') || 'light';
document.body.setAttribute('data-theme', currentTheme);
updateThemeIcon(currentTheme);

// Toggle theme on button click
themeToggle.addEventListener('click', () => {
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    // Update theme
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
});

// Update theme icon
function updateThemeIcon(theme) {
    if (theme === 'dark') {
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
    } else {
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
    }
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
});
    // Function to show success animation (same as before)
    function showSuccessAnimation() {
        // Create animation element
        const animation = document.createElement('div');
        animation.className = 'success-animation';
        animation.innerHTML = `
            <div class="animation-content">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
                <h3>Successfully Registered!</h3>
                <p>Your participation has been confirmed.</p>
            </div>
        `;
        
        // Insert animation where the form was
        const eventDetails = document.getElementById('eventDetails');
        if (eventDetails) {
            const form = eventDetails.querySelector('form');
            if (form) {
                form.parentNode.insertBefore(animation, form.nextSibling);
            }
        }
        
        // Remove animation after 5 seconds
        setTimeout(() => {
            if (animation.parentNode) {
                animation.parentNode.removeChild(animation);
            }
        }, 8000);
    }
});

// Add CSS for animation
const style = document.createElement('style');
style.textContent = `
    .success-animation {
        text-align: center;
        padding: 30px;
        margin-top: 20px;
        background: linear-gradient(135deg, rgba(123, 138, 182, 0.1), rgba(160, 158, 187, 0.1));
        border-radius: 15px;
        border: 2px solid var(--primary);
    }
    
    .animation-content {
        animation: fadeIn 0.5s ease-out;
    }
    
    .checkmark {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: var(--primary);
        stroke-miterlimit: 10;
        margin: 20px auto;
        box-shadow: inset 0px 0px 0px var(--primary);
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }
    
    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: var(--primary);
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    
    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }
    
    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }
    
    @keyframes scale {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }
    
    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 50px var(--primary);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .success-message {
        background: linear-gradient(135deg, rgba(123, 138, 182, 0.1), rgba(160, 158, 187, 0.1));
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        border-left: 5px solid var(--primary);
        animation: fadeIn 0.5s ease-out;
    }
    
    .success-message h3 {
        color: var(--primary);
        margin-bottom: 10px;
    }
`;
document.head.appendChild(style);
</script>
<script src="eventt.js"></script>




</body>
</html>