<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=peilingen", "root", "");
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}