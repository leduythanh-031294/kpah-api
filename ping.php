<?php
error_reporting(0);
header("Content-Type: text/plain; charset=UTF-8");

$ip = $_GET["ip"] ?? "";
if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo "0";
    exit;
}

$db = new SQLite3(__DIR__ . "/ip_lock.db");

// Update thời gian ping
$stmt = $db->prepare("
    INSERT INTO ping_time (ip, last_ping)
    VALUES (?, strftime('%s','now'))
    ON CONFLICT(ip) DO UPDATE SET last_ping = strftime('%s','now')
");
$stmt->bindValue(1, $ip, SQLITE3_TEXT);
$stmt->execute();

echo "1";
