<?php
error_reporting(0);
header('Content-Type: text/plain; charset=UTF-8');

$ip = trim($_GET['ip'] ?? '');

if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo "0"; exit;
}

$db = new SQLite3(__DIR__ . '/ip_lock.db');

// auto unlock trước khi check
$timeout = 60;
$now = time();
$db->exec("UPDATE ip_lock 
           SET locked_ip = '', last_ping = 0
           WHERE last_ping > 0 AND ($now - last_ping) > $timeout");

// lấy IP hiện tại
$res = $db->query("SELECT locked_ip FROM ip_lock WHERE id = 1");
$row = $res->fetchArray(SQLITE3_ASSOC);
$current = $row['locked_ip'] ?? "";

// nếu chưa có ai → lock IP
if ($current == "") {
    $stmt = $db->prepare("UPDATE ip_lock SET locked_ip = ?, last_ping = ? WHERE id = 1");
    $stmt->bindValue(1, $ip);
    $stmt->bindValue(2, time());
    $stmt->execute();
    echo "1"; exit;
}

// nếu IP đang giữ là của mình → OK
if ($current == $ip) {
    echo "1"; exit;
}

// còn lại → bị chặn
echo "0";
