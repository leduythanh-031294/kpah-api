<?php
error_reporting(0);

$db = new SQLite3(__DIR__ . "/ip_lock.db");

$now = time();

// Lấy IP đang khóa
$res = $db->query("SELECT locked_ip FROM ip_lock WHERE id = 1");
$row = $res->fetchArray(SQLITE3_ASSOC);
$locked_ip = $row["locked_ip"] ?? "";

if ($locked_ip === "") exit;

// Lấy thời gian ping cuối cùng
$stmt = $db->prepare("SELECT last_ping FROM ping_time WHERE ip = ?");
$stmt->bindValue(1, $locked_ip, SQLITE3_TEXT);
$res = $stmt->execute();
$row = $res->fetchArray(SQLITE3_ASSOC);

$last = $row["last_ping"] ?? 0;

// Nếu không ping > 60s → mở khóa
if ($now - $last > 60) {
    $db->exec("UPDATE ip_lock SET locked_ip = '' WHERE id = 1");
}

