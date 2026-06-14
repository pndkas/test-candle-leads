<?php
/**
 * save_lead.php — Receives form data and saves it to the database
 *
 * Acts as an API endpoint accepting only POST requests
 * Returns JSON response
 */

header('Content-Type: application/json; charset=utf-8');

// Allow only POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

require_once __DIR__ . '/db.php';

// ============================================================
// 1. Retrieve POST data and perform basic sanitization
// ============================================================
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$scent   = trim($_POST['scent']   ?? '');
$message = trim($_POST['message'] ?? '');

// ============================================================
// 2. Server-side validation
//    Repeated from JS because users can bypass JS
// ============================================================
$errors = [];

if ($name === '') {
    $errors[] = 'Please enter your name';
} elseif (mb_strlen($name) > 100) {
    $errors[] = 'Name must not exceed 100 characters';
}

if ($email === '') {
    $errors[] = 'Please enter your email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Use PHP built-in filter to validate email format
    $errors[] = 'Invalid email format';
} elseif (mb_strlen($email) > 254) {
    $errors[] = 'Email is too long';
}

$allowedScents = ['Vanilla', 'Lavender', 'Sandalwood', 'Citrus', 'Unsure'];
if ($scent === '') {
    $errors[] = 'Please select your preferred scent';
} elseif (!in_array($scent, $allowedScents, true)) {
    // Whitelist validation — prevents unexpected values
    $errors[] = 'Invalid scent selection';
}

if (mb_strlen($message) > 500) {
    $errors[] = 'Message must not exceed 500 characters';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// ============================================================
// 3. Save data to database using Prepared Statement
//    Prevents SQL Injection — user input will never be
//    interpreted as SQL commands
// ============================================================
try {
    $pdo = getDB();

    $stmt = $pdo->prepare("
        INSERT INTO leads (name, email, scent, message)
        VALUES (:name, :email, :scent, :message)
    ");

    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':scent'   => $scent,
        ':message' => $message !== '' ? $message : null,
    ]);

    $insertedId = $pdo->lastInsertId();

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Thank you! We have received your information and will contact you soon 🕯️',
        'id'      => (int) $insertedId,
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    // In production, log error to a file instead of echoing
    error_log('DB Insert Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred while saving data. Please try again.']);
}
