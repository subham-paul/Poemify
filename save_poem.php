<?php
include 'config.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'You must be logged in to save poems.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = $_POST['prompt'] ?? '';
    $poem_text = $_POST['content'] ?? '';
    $poem_length = $_POST['length'] ?? '';
    $style = $_POST['style'] ?? 'Contemporary';
    $type = $_POST['type'] ?? 'Narrative'; // Using 'type' as tone for table consistency
    $is_public = 0; // Default to private

    // Validate inputs
    if (empty($poem_text)) {
        echo json_encode(['error' => 'Poem content cannot be empty.']);
        exit;
    }
    if (empty($prompt)) {
        echo json_encode(['error' => 'Poem prompt cannot be empty.']);
        exit;
    }

    try {
        $user_id = (int)$_SESSION['user_id']; // Explicitly cast to int

        // Table: poems (user_id, title, type, length, style, poem_text, is_public, created_at)
        $stmt = $pdo->prepare("INSERT INTO poems (user_id, title, type, length, style, poem_text, is_public, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $prompt, $type, $poem_length, $style, $poem_text, $is_public]);
        echo json_encode(['success' => 'Poem saved successfully!']);
    } catch (PDOException $e) {
        // Log the error for server-side debugging
        error_log("Poem save error: " . $e->getMessage());
        // Return the specific database error message to the client
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Catch any other unexpected errors
        error_log("Unexpected error during poem save: " . $e->getMessage());
        echo json_encode(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>