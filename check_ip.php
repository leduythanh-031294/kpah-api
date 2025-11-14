<?php
// check_ip.php
error_reporting(0);
header('Content-Type: text/plain; charset=UTF-8');

$ip = isset($_GET['ip']) ? trim($_GET['ip']) : '';
if (!filter_var($ip, FILTER_VALIDATE_IP)) { echo "0"; exit; }

// dùng SQLite (file ip_lock.db trong cùng thư mục)
$db = new SQLite3(__DIR__ . '/ip_lock.db');
$db->exec('CREATE TABLE IF NOT EXISTS ip_lock (id INTEGER PRIMARY KEY, locked_ip TEXT)');

// Try lock if empty
$stmt = $db->prepare("UPDATE ip_lock SET locked_ip = ? WHERE id = 1 AND (locked_ip IS NULL OR locked_ip = '')");
if ($stmt) {
    $stmt->bindValue(1, $ip, SQLITE3_TEXT);
    $stmt->execute();
    if ($db->changes() > 0) { echo "1"; $db->close(); exit; }
}

// Otherwise read current
$res = $db->query('SELECT locked_ip FROM ip_lock WHERE id = 1 LIMIT 1');
$row = $res ? $res->fetchArray(SQLITE3_ASSOC) : null;
$current = $row['locked_ip'] ?? '';

// Nếu current khớp ip -> ok, ngược lại từ chối
if ($current === $ip) echo "1"; else echo "0";

$db->close();
