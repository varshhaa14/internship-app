<?php
header('Content-Type: application/json');
require_once 'redis.php';

try {
    $token = trim($_POST['token'] ?? $_GET['token'] ?? '');

    if ($token !== '') {
        $redis = getRedisClient();
        $redis->del('session:' . $token);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Logged out successfully.'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Logout failed: ' . $e->getMessage()
    ]);
}
?>