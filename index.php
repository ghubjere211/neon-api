<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Neon connection config
$dsn = "pgsql:host=ep-wispy-darkness-a8z5f009-pooler.eastus2.azure.neon.tech;port=5432;dbname=neondb;sslmode=require;options=--endpoint%3Dep-wispy-darkness-a8z5f009";
$username = "neondb_owner";
$password = "npg_7mWRdEUr6Bnk";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $stmt = $pdo->query("SELECT * FROM barbers LIMIT 5");
    $rows = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $rows]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
