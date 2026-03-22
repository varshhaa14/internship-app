<?php
header('Content-Type: application/json');
require_once 'db_mysql.php';
require_once 'db_mongo.php';
require_once 'redis.php';

try {
    $token = trim($_GET['token'] ?? '');

    if ($token === '') {
        echo json_encode([
            'success' => false,
            'message' => 'No token provided'
        ]);
        exit;
    }

    $redis = getRedisClient();
    $userId = $redis->get('session:' . $token);

    if (!$userId) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid or expired session'
        ]);
        exit;
    }

    $pdo = getMySQLConnection();

    $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
        exit;
    }

    $profile = null;
    try {
        $profiles = getMongoCollection();
        $profile = $profiles->findOne(['user_id' => (int)$userId]);
    } catch (Throwable $e) {
        $profile = null;
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'name' => $user['name'] ?? '',
            'email' => $user['email'] ?? '',
            'age' => $profile['age'] ?? '',
            'dob' => $profile['dob'] ?? '',
            'contact' => $profile['contact'] ?? ''
        ]
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>