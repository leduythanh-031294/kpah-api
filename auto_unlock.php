<?php
error_reporting(0);

$db = new SQLite3(__DIR__ . '/ip_lock.db');

$timeout = 60; // 60s không ping ⇒ auto unlock
$now = time();

$db->exec("UPDATE ip_lock 
           SET locked_ip = '', last_ping = 0
           WHERE last_ping > 0 AND ($now - last_ping) > $timeout");

echo "OK";
