<?php
$db = new SQLite3(__DIR__ . '/ip_lock.db');

// Tạo bảng nếu chưa có
$db->exec("CREATE TABLE IF NOT EXISTS ip_lock (
    id INTEGER PRIMARY KEY,
    locked_ip TEXT,
    time INTEGER
)");

// Chèn hàng nếu chưa tồn tại
$db->exec("INSERT OR IGNORE INTO ip_lock (id, locked_ip, time) VALUES (1, '', 0)");

// Lấy dữ liệu để debug
$res = $db->query('SELECT * FROM ip_lock WHERE id = 1');
$row = $res->fetchArray(SQLITE3_ASSOC);

echo "locked_ip = " . ($row['locked_ip'] ?? '(null)') . "<br>";
echo "time = " . ($row['time'] ?? '(null)') . "<br>";
echo "db_path = " . __DIR__ . '/ip_lock.db';
