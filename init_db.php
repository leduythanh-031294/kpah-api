<?php
$db = new SQLite3(__DIR__ . '/ip_lock.db');

// Bảng khóa IP
$db->exec("
CREATE TABLE IF NOT EXISTS ip_lock (
    id INTEGER PRIMARY KEY,
    locked_ip TEXT
);
");

// Đảm bảo luôn có row id=1
$db->exec("INSERT OR IGNORE INTO ip_lock (id, locked_ip) VALUES (1, '')");

// Bảng lưu thời gian ping
$db->exec("
CREATE TABLE IF NOT EXISTS ping_time (
    ip TEXT PRIMARY KEY,
    last_ping INTEGER
);
");

echo "Database initialized OK!";
