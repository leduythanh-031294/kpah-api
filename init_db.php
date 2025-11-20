<?php
$db = new SQLite3(__DIR__ . '/ip_lock.db');
$db->exec("CREATE TABLE IF NOT EXISTS ip_lock (
    id INTEGER PRIMARY KEY,
    locked_ip TEXT,
    last_ping INTEGER
)");
$db->exec("INSERT OR IGNORE INTO ip_lock (id, locked_ip, last_ping) VALUES (1, '', 0)");
echo "DB initialized";
