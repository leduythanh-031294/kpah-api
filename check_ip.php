<?php
error_reporting(0);
header("Content-Type: text/plain; charset=UTF-8");

$ip = $_GET["ip"] ?? "";
if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo "0";
    exit;
}

$db = new SQLite3(__DIR__ . "/ip_lock.db");

// Đảm bảo row id=1 luôn tồn tại
$db->exec("INSERT OR IGNORE INTO ip_lock (id, locked_ip) VALUES (1, '')");

// Lấy IP hiện tại
$res = $db->query("SELECT locked_ip FROM ip_lock WHERE id = 1 LIMIT 1");
$row = $res->fetchArray(SQLITE3_ASSOC);

$current = $row ? $row["locked_ip"] : "";

// Nếu đang trống → khóa IP mới
if ($current === "") {
    $stmt = $db->prepare("UPDATE ip_lock SET locked_ip = ? WHERE id = 1");
    $stmt->bindValue(1, $ip, SQLITE3_TEXT);
    $stmt->execute();
    echo "1";
    exit;
}

// Nếu IP trùng → hợp lệ
if ($current === $ip) {
    echo "1";
} else {
    echo "0";
}
