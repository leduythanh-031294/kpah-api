<?php
error_reporting(0);
header('Content-Type: text/plain; charset=UTF-8');

$ip = trim($_GET['ip'] ?? '');

if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo "0"; exit;
}

$db = new SQLite3(__DIR__ . '/ip_lock.db');

$stmt = $db->prepare("UPDATE ip_lock SET last_ping = :ts WHERE locked_ip = :ip");
$stmt->bindValue(":ts", time(), SQLITE3_INTEGER);
$stmt->bindValue(":ip", $ip, SQLITE3_TEXT);
$stmt->execute();

echo "1";
