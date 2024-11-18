<?php
require_once 'vendor/autoload.php';
include "create_tables.php";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthTech_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


use Gemini\Client;
use Gemini\Resources\Chat;
use Gemini\Data\Content;
use Gemini\Enums\Role;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'] ?? '';
$user_id = $_SESSION['user_id'];

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit();
}

try {
    // Initialize Gemini client
    $client = Gemini::client("AIzaSyACtTBT1ueUlG94FDqEO9Q7QytmHQ07WP8");
    $chat = $client->geminiPro()->startChat(
        [
            Content::parse(part: 'You are an AI healthcare assistant designed to provide helpful, general health advice and support. Your role is to assist users in:

Understanding medical reports: Explain test results, values, and terminology in a clear and simplified manner.
Explaining prescriptions: Clarify medication names, dosages, and purposes based on user input.
Appointment discussions: Help users prepare for or follow up on medical appointments by answering related questions or giving general guidance.
Health goal discussions: Provide suggestions and insights to help users achieve their health-related objectives.
Important Guidelines:

Always remind the user that you are not a substitute for a qualified doctor.
Encourage them to consult a healthcare professional for accurate diagnosis, treatment, or advice whenever necessary.
Avoid making definitive medical diagnoses or prescribing treatments.
Always prioritize user safety and clarity in your responses. End your responses with a disclaimer, such as:

"Please note that I am not a licensed medical professional. For accurate medical advice, diagnosis, or treatment, consult a qualified doctor or healthcare provider."', role: Role::USER),
            Content::parse(part: 'I am ready to assist you with your healthcare questions. Feel free to ask me anything about:

Understanding Medical Reports: I can help you interpret test results, values, and medical terminology in simple terms.
Explaining Prescriptions: I can clarify medication names, dosages, and purposes based on the information you provide.
Appointment Discussions: I can help you prepare for or follow up on medical appointments by answering your questions or providing general guidance.
Health Goal Discussions: I can offer suggestions and insights to help you achieve your health-related objectives.
Remember: I am here to provide general health information and support, but I cannot provide medical diagnoses or treatment advice. Always consult a qualified healthcare professional for personalized medical care.', role: Role::MODEL)
        ]
    );

    // Send message and get response
    $response = $chat->sendMessage($message);
    $ai_response = $response->text();

    // Save message using PDO
    $stmt = $conn->prepare("
        INSERT INTO chat_messages (user_id, message, response)
        VALUES (:user_id, :message, :response)
    ");

    $stmt->execute([
        ':user_id' => $user_id,
        ':message' => $message,
        ':response' => $ai_response
    ]);

    echo json_encode([
        'success' => true,
        'response' => $ai_response,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred',
        'message' => $e->getMessage()
    ]);
}
