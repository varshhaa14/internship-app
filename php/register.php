<?php
header('Content-Type: application/json');
require_once 'db_mysql.php';

try {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        echo json_encode([
            'success' => false,
            'message' => 'All fields are required.'
        ]);
        exit;
    }

    $pdo = getMySQLConnection();

    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);

    if ($checkStmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'Email already exists.'
        ]);
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $passwordHash]);

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful.'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Registration failed: ' . $e->getMessage()
    ]);
}
?>