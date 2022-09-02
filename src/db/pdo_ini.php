<?php
$config = require_once './config.php';

try {
    $pdo = new \PDO(
        sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['dbname']),
        $config['user'],
        $config['pass'],
    );
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}