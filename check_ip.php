<?php
error_reporting(0);
header("Content-Type: text/plain; charset=UTF-8");

// Lấy IP gửi từ MIDlet
$ip = isset($_GET['ip']) ? trim($_GET['ip']) : "";

if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo "0";
    exit;
}

// kết nối SQLite
$db = new SQLite3(__DIR__ . "/ip_lock.db");
$db->exec("CREATE TABLE IF NOT EXISTS ip_lock (id INTEGER PRIMARY KEY, locked_ip TEXT)");

// lấy IP hiện tại
$res = $db->query("SELECT locked_ip FROM ip_lock WHERE id = 1");
$row = $res->fetchArray(SQLITE3_ASSOC);

$current = $row["locked_ip"] ?? "";

// nếu trống → ghi IP này
if ($current === "" || $current === null) {
    $stmt = $db->prepare("INSERT OR REPLACE INTO ip_lock (id, locked_ip) VALUES (1, ?)");
    $stmt->bindValue(1, $ip, SQLITE3_TEXT);
    $stmt->execute();
    echo "1";
    exit;
}

// nếu đúng IP → cho phép
if ($current === $ip) {
    echo "1";
    exit;
}

// sai IP → từ chối
echo "0";
