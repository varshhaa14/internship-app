<?php
header('Content-Type: application/json');
require_once 'db_mysql.php';
require_once 'redis.php';
require_once 'config.php';

try {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Email and password are required.'
        ]);
        exit;
    }

    $pdo = getMySQLConnection();

    $stmt = $pdo->prepare("SELECT id, name, email, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid credentials'
        ]);
        exit;
    }

    $token = bin2hex(random_bytes(32));

    $redis = getRedisClient();
    $redis->setex('session:' . $token, REDIS_SESSION_TTL, (string)$user['id']);

    echo json_encode([
        'success' => true,
        'token' => $token
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Login failed: ' . $e->getMessage()
    ]);
}
?>