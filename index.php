<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Load env vars from Render's environment panel
$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$env = getenv('APP_ENV') ?: 'production';
$debug = getenv('APP_DEBUG') === 'true';

// Extract endpoint ID from host
$host_parts = explode('.', $host);
$endpoint_segment = $host_parts[0];
if (substr($endpoint_segment, -7) === '-pooler') {
    $endpoint_id = substr($endpoint_segment, 0, -7);
} else {
    $endpoint_id = $endpoint_segment;
}

// Build secure DSN for Neon
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require;options=--endpoint%3D$endpoint_id";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Example query: fetch barbers
    $stmt = $pdo->query("SELECT * FROM barbers LIMIT 5");
    $rows = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $rows]);

} catch (PDOException $e) {
    $error = $debug ? $e->getMessage() : 'Database connection failed';
    echo json_encode(['success' => false, 'error' => $error]);
}
