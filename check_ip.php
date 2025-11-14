<?php
// check_ip.php
error_reporting(0);
header('Content-Type: text/plain; charset=UTF-8');

$ip = isset($_GET['ip']) ? trim($_GET['ip']) : '';
if (!filter_var($ip, FILTER_VALIDATE_IP)) { echo "0"; exit; }

$db = new SQLite3(__DIR__ . '/ip_lock.db');

// Tạo bảng
$db->exec("CREATE TABLE IF NOT EXISTS ip_lock (
    id INTEGER PRIMARY KEY,
    locked_ip TEXT
)");

// Đảm bảo luôn có row id=1
$db->exec("INSERT OR IGNORE INTO ip_lock (id, locked_ip) VALUES (1, '')");

// Nếu đang trống → khóa IP này
$stmt = $db->prepare("UPDATE ip_lock SET locked_ip = ? WHERE id = 1 AND locked_ip = ''");
$stmt->bindValue(1, $ip, SQLITE3_TEXT);
$stmt->execute();

if ($db->changes() > 0) {
    echo "1"; // Đã khóa IP đầu tiên
    $db->close();
    exit;
}

// Đọc IP hiện có
$res = $db->query("SELECT locked_ip FROM ip_lock WHERE id = 1");
$row = $res->fetchArray(SQLITE3_ASSOC);
$current = $row['locked_ip'] ?? '';

// Nếu trùng → OK
if ($current === $ip) echo "1";
else echo "0";

$db->close();
