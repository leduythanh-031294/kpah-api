<?php
$db = new SQLite3(__DIR__ . '/ip_lock.db');
$res = $db->query('SELECT locked_ip FROM ip_lock WHERE id = 1');
$row = $res->fetchArray(SQLITE3_ASSOC);
echo "locked_ip = " . ($row['locked_ip'] ?? '(null)');
