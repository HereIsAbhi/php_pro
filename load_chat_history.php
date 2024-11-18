<?php
include 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

try {
    // Prepare the SQL statement
    $stmt = $conn->prepare("
        SELECT message, response, created_at
        FROM chat_messages
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 50
    ");

    // Bind the user_id parameter
    $stmt->bind_param("i", $_SESSION['user_id']);

    // Execute the statement
    $stmt->execute();

    // Fetch results
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    // Return JSON response
    echo json_encode([
        'success' => true,
        'messages' => $messages
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred',
        'message' => $e->getMessage()
    ]);
}
