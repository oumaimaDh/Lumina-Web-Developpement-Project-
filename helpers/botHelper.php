<?php
// =======================================
// FILE: controller/botHelper.php
// OpenAI Forum AI Assistant (FULL WORKING)
// =======================================

require_once __DIR__ . '/../config/Database.php';

// ✅ MAIN FUNCTION CALLED AFTER A QUESTION IS POSTED
function sendToBot($questionText, $id_question, $pdo) {

    // ✅ PUT YOUR OPENAI API KEY HERE
    $apiKey = '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';

    if (empty($apiKey)) {
        error_log("❌ No OpenAI API key configured");
        return "AI service is not configured.";
    }

    // ✅ OpenAI Responses API (Current & Official)
    $apiUrl = "https://api.openai.com/v1/responses";

    $payload = [
        "model" => "gpt-4.1-mini",
        "input" => [
            [
                "role" => "system",
                "content" => "You are a helpful forum assistant. Answer clearly, accurately, and politely."
            ],
            [
                "role" => "user",
                "content" => $questionText
            ]
        ]
    ];

    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . $apiKey
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 60
    ]);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // ✅ CURL ERROR
    if ($curlError) {
        error_log("❌ CURL ERROR: $curlError");
        return "AI connection error.";
    }

    // ✅ OPENAI ERROR
    if ($httpCode !== 200) {
        error_log("❌ OPENAI HTTP ERROR $httpCode: $result");
        return "AI is temporarily unavailable.";
    }

    $response = json_decode($result, true);

    // ✅ SAFE EXTRACTION OF OPENAI TEXT
    if (!isset($response['output'][0]['content'][0]['text'])) {
        error_log("❌ Invalid OpenAI response: " . print_r($response, true));
        return "AI failed to generate a response.";
    }

    $botReply = trim($response['output'][0]['content'][0]['text']);

    // ✅ SAVE RESPONSE TO DATABASE
    try {
        $stmt = $pdo->prepare("
            INSERT INTO forum_responses 
            (ID_QUESTION, ID_USER, CONTENT, date_response, likes) 
            VALUES (?, 1, ?, NOW(), 0)
        ");
        $stmt->execute([$id_question, $botReply]);
    } catch (PDOException $e) {
        error_log("❌ DATABASE ERROR: " . $e->getMessage());
    }

    return $botReply;
}

// ✅ OPTIONAL FUNCTION: FLAG QUESTION FOR MODERATOR
function notifyAdvancedAgent($id_question, $message, $pdo) {
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM questions LIKE 'status'");

        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->prepare("UPDATE questions SET status='flagged' WHERE id_question=?");
            $stmt->execute([$id_question]);
        }

        error_log("⚠️ AI FLAGGED QUESTION #$id_question: " . substr($message, 0, 100));

    } catch (PDOException $e) {
        error_log("❌ FLAG ERROR: " . $e->getMessage());
    }
}
?>
