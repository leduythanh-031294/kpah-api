<?php
// release_ip.php
error_reporting(0);
header('Content-Type: text/plain; charset=UTF-8');

$ip = isset($_GET['ip']) ? trim($_GET['ip']) : '';
if (!filter_var($ip, FILTER_VALIDATE_IP)) { echo "0"; exit; }

$db = new SQLite3(__DIR__ . '/ip_lock.db');

$db->exec("CREATE TABLE IF NOT EXISTS ip_lock (
    id INTEGER PRIMARY KEY,
    locked_ip TEXT
)");

$db->exec("INSERT OR IGNORE INTO ip_lock (id, locked_ip) VALUES (1, '')");

// Chỉ xóa nếu đúng IP đang khóa
$stmt = $db->prepare("UPDATE ip_lock SET locked_ip = '' WHERE id = 1 AND locked_ip = ?");
$stmt->bindValue(1, $ip, SQLITE3_TEXT);
$stmt->execute();

echo ($db->changes() > 0) ? "1" : "0";

$db->close();
