<?php
error_reporting(0);
header("Content-Type: text/plain; charset=UTF-8");

$ip = $_GET["ip"] ?? "";
if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo "0";
    exit;
}

$db = new SQLite3(__DIR__ . "/ip_lock.db");

// Mở khóa nếu đúng IP đang giữ
$stmt = $db->prepare("UPDATE ip_lock SET locked_ip = '' WHERE id = 1 AND locked_ip = ?");
$stmt->bindValue(1, $ip, SQLITE3_TEXT);
$stmt->execute();

echo ($db->changes() > 0) ? "1" : "0";
