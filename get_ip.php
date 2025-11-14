<?php
// Tắt báo lỗi để J2ME client dễ xử lý hơn
error_reporting(0); 

// Lấy địa chỉ IP của client
$client_ip = $_SERVER['REMOTE_ADDR'];

// Đặt Header Content-Type là text/plain (văn bản thuần túy)
header('Content-Type: text/plain');

// Trả về IP
echo $client_ip;
?>