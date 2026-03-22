<?php
require_once 'config.php';

function getMySQLConnection() {
    $dsn = "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    return new PDO($dsn, MYSQL_USER, MYSQL_PASS, $options);
}
?>