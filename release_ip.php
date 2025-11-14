<?php
error_reporting(0);
header('Content-Type: text/plain');

// --- THÔNG TIN DATABASE ---
$db_host = "sql104.infinityfree.com";
$db_user = "if0_40256457";
$db_pass = "Thanh03121994";
$db_name = "if0_40256457_leduythanh";
// --------------------------

$ip_to_release = isset($_GET['ip']) ? trim($_GET['ip']) : '';
if ($ip_to_release == '') { echo "0"; exit; }

$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    echo "0"; 
    exit;
}

// Đọc IP đang hoạt động
$result = $conn->query("SELECT locked_ip FROM ip_lock WHERE id = 1 LIMIT 1");
$active_ip = '';
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $active_ip = trim($row['locked_ip']);
}

// Giải phóng IP nếu khớp
if ($active_ip == $ip_to_release && $active_ip != '') {
    $empty_ip = '';
    $stmt = $conn->prepare("UPDATE ip_lock SET locked_ip = ? WHERE id = 1 AND locked_ip = ?");
    $stmt->bind_param("ss", $empty_ip, $ip_to_release);
    if ($stmt->execute()) echo "1"; else echo "0";
    $stmt->close();
} else {
    echo "0";
}

$conn->close();
?>
