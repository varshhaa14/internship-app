<?php
require_once 'config.php';

function getMySQLConnection() {
    $dsn = "pgsql:host=" . getenv("MYSQL_HOST") .
           ";port=" . getenv("MYSQL_PORT") .
           ";dbname=" . getenv("MYSQL_DB");

    return new PDO($dsn, getenv("MYSQL_USER"), getenv("MYSQL_PASS"), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}
?>
