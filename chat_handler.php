<?php
require_once 'vendor/autoload.php';

use Smalot\PdfParser\Parser;

require 'config.php';
include "create_tables.php";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthTech_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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

function getHealthGoals($conn, $user_id)
{
    $stmt = $conn->prepare("
        SELECT goal_type, goal_title, target, timeline, progress, status 
        FROM health_goals 
        WHERE user_id = :user_id 
        ORDER BY created_at DESC
    ");
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll();
}

function getAppointments($conn, $user_id)
{
    $stmt = $conn->prepare("
        SELECT doctor_name, appointment_date, appointment_time, notes, status 
        FROM appointments 
        WHERE user_id = :user_id 
        ORDER BY appointment_date DESC
        LIMIT 5
    ");
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll();
}
function getReportContents($conn, $user_id)
{
    $stmt = $conn->prepare("
        SELECT file_path, file_type 
        FROM reports 
        WHERE user_id = :user_id 
        ORDER BY upload_date DESC
        LIMIT 5
    ");
    $stmt->execute([':user_id' => $user_id]);
    $reports = $stmt->fetchAll();

    $reportContents = [];
    $pdfParser = new Parser();

    foreach ($reports as $report) {
        if (file_exists($report['file_path'])) {
            try {
                switch ($report['file_type']) {
                    case 'text/plain':
                        $reportContents[] = file_get_contents($report['file_path']);
                        break;

                    case 'application/pdf':
                        $pdf = $pdfParser->parseFile($report['file_path']);
                        $text = $pdf->getText();
                        // Clean up common PDF parsing artifacts
                        $text = preg_replace('/\s+/', ' ', $text); // Remove multiple spaces
                        $text = trim($text); // Remove trailing spaces
                        $reportContents[] = $text;
                        break;

                    default:
                        // Log unsupported file type
                        error_log("Unsupported file type: " . $report['file_type']);
                        break;
                }
            } catch (Exception $e) {
                error_log("Error processing file {$report['file_path']}: " . $e->getMessage());
                continue;
            }
        }
    }
    return $reportContents;
}

function getPrescriptionContents($conn, $user_id)
{
    $stmt = $conn->prepare("
        SELECT file_path, file_type 
        FROM prescriptions 
        WHERE user_id = :user_id 
        ORDER BY upload_date DESC
        LIMIT 5
    ");
    $stmt->execute([':user_id' => $user_id]);
    $prescriptions = $stmt->fetchAll();

    $prescriptionContents = [];
    $pdfParser = new Parser();

    foreach ($prescriptions as $prescription) {
        if (file_exists($prescription['file_path'])) {
            try {
                switch ($prescription['file_type']) {
                    case 'text/plain':
                        $prescriptionContents[] = file_get_contents($prescription['file_path']);
                        break;

                    case 'application/pdf':
                        $pdf = $pdfParser->parseFile($prescription['file_path']);
                        $text = $pdf->getText();
                        // Clean up common PDF parsing artifacts
                        $text = preg_replace('/\s+/', ' ', $text);
                        $text = trim($text);
                        $prescriptionContents[] = $text;
                        break;

                    default:
                        error_log("Unsupported file type: " . $prescription['file_type']);
                        break;
                }
            } catch (Exception $e) {
                error_log("Error processing file {$prescription['file_path']}: " . $e->getMessage());
                continue;
            }
        }
    }
    return $prescriptionContents;
}

function getChatHistory($conn, $user_id)
{
    $stmt = $conn->prepare("
        SELECT message, response 
        FROM chat_messages 
        WHERE user_id = :user_id 
        ORDER BY id DESC 
        LIMIT 5
    ");
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll();
}

try {
    // Gather all context
    $healthGoals = getHealthGoals($conn, $user_id);
    $appointments = getAppointments($conn, $user_id);
    $reportContents = getReportContents($conn, $user_id);
    $prescriptionContents = getPrescriptionContents($conn, $user_id);
    $chatHistory = getChatHistory($conn, $user_id);

    // Format context for Gemini
    $contextString = "User Context:\n\n";

    if (!empty($healthGoals)) {
        $contextString .= "Health Goals:\n";
        foreach ($healthGoals as $goal) {
            $contextString .= "- {$goal['goal_type']}: {$goal['goal_title']} (Target: {$goal['target']}, Progress: {$goal['progress']}%)\n";
        }
    }

    if (!empty($appointments)) {
        $contextString .= "\nUpcoming Appointments:\n";
        foreach ($appointments as $apt) {
            $contextString .= "- {$apt['appointment_date']} {$apt['appointment_time']} with Dr. {$apt['doctor_name']}\n";
        }
    }

    if (!empty($reportContents)) {
        $contextString .= "\nRecent Medical Reports:\n";
        foreach ($reportContents as $report) {
            $contextString .= "- " . substr($report, 0, 200) . "...\n";
        }
    }

    if (!empty($prescriptionContents)) {
        $contextString .= "\nCurrent Prescriptions:\n";
        foreach ($prescriptionContents as $prescription) {
            $contextString .= "- " . substr($prescription, 0, 200) . "...\n";
        }
    }

    // Initialize Gemini client with context
    $client = Gemini::client("AIzaSyDh3d-c1NvTtNBafHL83BHemQ4UsBEVlzE");
    $chat = $client->geminiPro()->startChat([
        Content::parse(part: 'You are an AI healthcare assistant designed to provide helpful, general health advice and support. Below is the context for the current user, including their health goals, appointments, and medical history. Use this information to provide more personalized and relevant responses. Respond only in plain text' . $contextString, role: Role::USER),
        Content::parse(part: 'I understand the user context and will provide personalized assistance while maintaining medical privacy and ethical guidelines. I will reference their specific health goals, appointments, and medical history when relevant to their questions.', role: Role::MODEL),
        Content::parse(part: 'Introduce yourself and tell me that you have my appointments goals and reports', role: Role::USER)
    ]);

    // Load chat history
    if (!empty($chatHistory)) {
        foreach ($chatHistory as $chat_msg) {
            $chat->sendMessage($chat_msg['message']);
        }
    }

    // Send current message and get response
    $response = $chat->sendMessage($message);
    $ai_response = $response->text();

    // Save message
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
