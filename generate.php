<?php
include 'config.php';

// Suppress errors and set header for JSON
error_reporting(0);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = $_POST['prompt'] ?? '';
    $type = $_POST['type'] ?? 'Narrative';
    $style = $_POST['style'] ?? 'Contemporary';
    $length = $_POST['length'] ?? 'standard';
    $apiKey = $_ENV['GEMINI_API_KEY'] ?? '';

    // if (empty($apiKey) || $apiKey === 'AIzaSyCxhrRorN2SU48mjoYwhpLiYP0kiuKmZkU') {
    //     echo json_encode(['error' => 'API Key is missing or invalid in .env file.']);
    //     exit;
    // }

    if (empty($prompt)) {
        echo json_encode(['error' => 'Please enter a poem topic.']);
        exit;
    }

    $aiPrompt = "Write a $type poem in a $style style about: $prompt. The length should be $length. Provide only the poem text without any intro or outro.";

    // Using the most widely compatible endpoint and model
    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $aiPrompt]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    // Some local servers have SSL issues, this helps if standard curl fails
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo json_encode(['error' => 'Connection Error: ' . $err]);
        exit;
    }

    $result = json_decode($response, true);

    if ($httpCode !== 200) {
        $msg = $result['error']['message'] ?? "Status Code: $httpCode";
        echo json_encode(['error' => 'Gemini API Error: ' . $msg]);
        exit;
    }

    $poemContent = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

    if (!empty($poemContent)) {
        if (isLoggedIn()) {
            try {
                $stmt = $pdo->prepare("INSERT INTO poems (user_id, prompt, content, style) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $prompt, $poemContent, "$type ($style)"]);
            } catch (Exception $e) {}
        }
        echo json_encode(['poem' => $poemContent]);
    } else {
        echo json_encode(['error' => 'Poem generation failed. Check safety filters.']);
    }
}
?>