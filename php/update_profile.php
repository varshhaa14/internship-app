<?php
header('Content-Type: application/json');
require_once 'db_mysql.php';
require_once 'db_mongo.php';
require_once 'redis.php';

try {
    $token = trim($_GET['token'] ?? $_POST['token'] ?? '');

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
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
        exit;
    }

    $age = trim($_POST['age'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $contact = trim($_POST['contact'] ?? '');

    $profiles = getMongoCollection();

    $profiles->updateOne(
        ['user_id' => (int)$userId],
        [
            '$set' => [
                'user_id' => (int)$userId,
                'age' => $age,
                'dob' => $dob,
                'contact' => $contact
            ]
        ],
        ['upsert' => true]
    );

    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully.'
    ]);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Update error: ' . $e->getMessage()
    ]);
}
?>