<?php
error_reporting(0);
header("Content-Type: text/plain; charset=UTF-8");

$ip = isset($_GET["ip"]) ? trim($_GET["ip"]) : "";
if (!filter_var($ip, FILTER_VALIDATE_IP)) {
    echo "0";
    exit;
}

$db = new SQLite3(__DIR__ . "/ip_lock.db");
$db->exec("CREATE TABLE IF NOT EXISTS ip_lock (id INTEGER PRIMARY KEY, locked_ip TEXT)");

$res = $db->query("SELECT locked_ip FROM ip_lock WHERE id = 1");
$row = $res->fetchArray(SQLITE3_ASSOC);

$current = $row["locked_ip"] ?? "";

if ($current === $ip) {
    $db->exec("UPDATE ip_lock SET locked_ip = '' WHERE id = 1");
    echo "1";
} else {
    echo "0";
}
