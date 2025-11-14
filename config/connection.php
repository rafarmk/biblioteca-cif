<?php
require_once __DIR__ . '/Database.php';

function getDB() {
    $database = new Database();
    return $database->getConnection();
}