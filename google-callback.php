<?php

require_once __DIR__ . '/google-config.php';
require_once __DIR__ . '/config.php';

// Ensure you have the Google API Client Library installed via Composer
// composer require google/apiclient

require_once 'vendor/autoload.php'; // Adjust path if necessary

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URL']);
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $google_id = $google_account_info->id;

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE provider_id = ?");
    $stmt->execute([$google_id]);
    $user = $stmt->fetch();

    if ($user) {
        // User exists, log them in
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
    } else {
        // New user, register them
        $stmt = $pdo->prepare("INSERT INTO users (name, email, provider_id, provider) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $google_id, 'google']);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $name;
    }

    header("Location: index.php");
    exit;

} else {
    header("Location: login.php");
    exit;
}
?>