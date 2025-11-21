<?php
error_reporting(0);

$db = new SQLite3(__DIR__ . '/ip_lock.db');

$now = time();

// Đảm bảo bảng tồn tại
$db->exec("CREATE TABLE IF NOT EXISTS ip_lock (
    id INTEGER PRIMARY KEY,
    locked_ip TEXT
)");

$db->exec("CREATE TABLE IF NOT EXISTS ping_time (
    ip TEXT PRIMARY KEY,
    last_ping INTEGER
)");

// Đảm bảo row id=1 tồn tại
$db->exec("INSERT OR IGNORE INTO ip_lock (id, locked_ip) VALUES (1, '')");

// Lấy IP đang khóa
$res = $db->query("SELECT locked_ip FROM ip_lock WHERE id = 1");
$row = $res->fetchArray(SQLITE3_ASSOC);
$locked_ip = $row["locked_ip"] ?? "";

// Nếu không có IP nào bị khóa thì thôi
if ($locked_ip === "") exit;

// Lấy last_ping của IP
$stmt = $db->prepare("SELECT last_ping FROM ping_time WHERE ip = ?");
$stmt->bindValue(1, $locked_ip, SQLITE3_TEXT);
$res = $stmt->execute();
$row = $res->fetchArray(SQLITE3_ASSOC);

$last = intval($row["last_ping"] ?? 0);

// Nếu quá 60s không ping → unlock
if ($now - $last > 60) {
    $db->exec("UPDATE ip_lock SET locked_ip = '' WHERE id = 1");
    echo "unlocked";
} else {
    echo "still_locked";
}
