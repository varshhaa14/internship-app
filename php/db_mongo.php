<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'config.php';

function getMongoCollection() {
    $client = new MongoDB\Client(MONGO_URI);
    $database = $client->selectDatabase(MONGO_DB);
    return $database->selectCollection(MONGO_COLLECTION);
}
?>