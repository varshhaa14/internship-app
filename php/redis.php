<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'config.php';

function getRedisClient() {
    return new Predis\Client([
        'scheme' => 'tcp',
        'host'   => REDIS_HOST,
        'port'   => REDIS_PORT
    ]);
}
?>