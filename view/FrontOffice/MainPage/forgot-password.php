<?php
// view/FrontOffice/forgot-password.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the User model with proper error handling
try {
    require_once __DIR__ . '/../../model/User.php';
} catch (Exception $e) {
    echo "ERROR: Could not load user model";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $email = $_POST['email'] ?? '';
    
    try {
        $userModel = new User();
        
        switch ($action) {
            case 'send_code':
                handleSendCode($userModel, $email);
                break;
                
            case 'verify_code':
                $code = $_POST['code'] ?? '';
                handleVerifyCode($userModel, $email, $code);
                break;
                
            case 'reset_password':
                $newPassword = $_POST['new_password'] ?? '';
                handleResetPassword($userModel, $email, $newPassword);
                break;
                
            default:
                echo "ERROR: Invalid action";
                break;
        }
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage();
    }
}

function handleSendCode($userModel, $email) {
    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "ERROR: Please enter a valid email address";
        return;
    }
    
    // Check if email exists
    if (!$userModel->emailExists($email)) {
        echo "ERROR: No account found with this email address";
        return;
    }
    
    // Generate 6-digit verification code
    $code = str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
    
    // Store code in session with expiration (10 minutes)
    $_SESSION['reset_code'] = $code;
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_code_expires'] = time() + 600; // 10 minutes
    
    // For testing - always return success and log the code
    error_log("PASSWORD_RESET: Code $code generated for $email");
    
    // Try to send email, but don't fail if email sending doesn't work
    if (sendVerificationEmail($email, $code)) {
        echo "SUCCESS";
    } else {
        // Still return success for testing, but log the issue
        error_log("PASSWORD_RESET: Email sending failed for $email, but code is: $code");
        echo "SUCCESS";
    }
}

function handleVerifyCode($userModel, $email, $code) {
    // Check if code exists and matches
    if (!isset($_SESSION['reset_code']) || 
        !isset($_SESSION['reset_email']) || 
        !isset($_SESSION['reset_code_expires'])) {
        echo "ERROR: No verification code requested. Please request a new code.";
        return;
    }
    
    // Check if code has expired
    if (time() > $_SESSION['reset_code_expires']) {
        session_destroy();
        echo "ERROR: Verification code has expired. Please request a new one.";
        return;
    }
    
    // Check if email matches and code is correct
    if ($_SESSION['reset_email'] !== $email || $_SESSION['reset_code'] !== $code) {
        echo "ERROR: Invalid verification code";
        return;
    }
    
    // Code is valid - mark as verified
    $_SESSION['reset_verified'] = true;
    echo "SUCCESS";
}

function handleResetPassword($userModel, $email, $newPassword) {
    // Check if verification was completed
    if (!isset($_SESSION['reset_verified']) || !$_SESSION['reset_verified']) {
        echo "ERROR: Please verify your email first";
        return;
    }
    
    // Check if email matches the one that was verified
    if (!isset($_SESSION['reset_email']) || $_SESSION['reset_email'] !== $email) {
        echo "ERROR: Email verification mismatch";
        return;
    }
    
    // Validate password
    if (strlen($newPassword) < 6) {
        echo "ERROR: Password must be at least 6 characters long";
        return;
    }
    
    // Reset password
    if ($userModel->resetPassword($email, $newPassword)) {
        // Clear reset session data
        unset($_SESSION['reset_code']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_code_expires']);
        unset($_SESSION['reset_verified']);
        
        echo "SUCCESS";
    } else {
        echo "ERROR: Failed to reset password. Please try again.";
    }
}

function sendVerificationEmail($email, $code) {
    // ←←← PUT YOUR RESEND API KEY HERE
    $apiKey = 're_EPoCbYzR_AwL6yuAdRdxNdeLxVoztDKUW';   // ← change this line only!

    $data = [
        "from"    => "Lumina <onboarding@resend.dev>",   // this domain works without any setup
        "to"      => [$email],
        "subject" => "Your Lumina verification code",
        "html"    => "<p style='font-size:18px'><strong>$code</strong></p>
                      <p>This code expires in 10 minutes.</p>
                      <p>If you didn't request this, ignore this email.</p>",
        "text"    => "Your Lumina verification code is: $code"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.resend.com/emails");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // For testing: always say success + log the code
    error_log("RESEND RESPONSE: $httpCode | Code sent to $email: $code");

    return $httpCode === 200;
}
?>