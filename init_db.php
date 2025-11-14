<?php
$db = new SQLite3(__DIR__ . '/ip_lock.db');

// Tạo bảng nếu chưa có
$db->exec("
    CREATE TABLE IF NOT EXISTS ip_lock (
        id INTEGER PRIMARY KEY,
        locked_ip TEXT
    )
");

// Nếu chưa có dòng id=1 thì thêm
$res = $db->query("SELECT COUNT(*) AS c FROM ip_lock WHERE id = 1");
$row = $res->fetchArray(SQLITE3_ASSOC);

if ($row['c'] == 0) {
    $stmt = $db->prepare("INSERT INTO ip_lock (id, locked_ip) VALUES (1, '')");
    $stmt->execute();
    echo "Đã tạo dòng mặc định.\n";
}

echo "Database OK!";
