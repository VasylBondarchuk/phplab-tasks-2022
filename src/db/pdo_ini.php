<?php
$config = require_once './config.php';

try {
    $pdo = new \PDO(
        sprintf('mysql:host=%s;', $config['host']), $config['user'], $config['pass']);
    $pdo->exec(sprintf("CREATE DATABASE IF NOT EXISTS %s", $config['dbname']));
    $pdo->exec(sprintf("USE %s", $config['dbname']));
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}