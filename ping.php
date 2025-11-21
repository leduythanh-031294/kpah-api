<?php
error_reporting(0);

$ip = $_GET['ip'] ?? '';

if (!filter_var($ip, FILTER_VALIDATE_IP)) exit("0");

$db = new SQLite3(__DIR__ . '/ip_lock.db');

// Tạo bảng lưu thời gian ping
$db->exec("CREATE TABLE IF NOT EXISTS ping_time (
    ip TEXT PRIMARY KEY,
    last_ping INTEGER
)");

// Ghi thời gian ping mới
$stmt = $db->prepare("
    INSERT INTO ping_time (ip, last_ping) 
    VALUES (?, ?) 
    ON CONFLICT(ip) DO UPDATE SET last_ping = excluded.last_ping
");
$stmt->bindValue(1, $ip, SQLITE3_TEXT);
$stmt->bindValue(2, time(), SQLITE3_INTEGER);
$stmt->execute();

echo "1";
