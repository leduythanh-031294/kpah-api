<?php
error_reporting(0);
header('Content-Type: text/plain');

// --- THÔNG TIN DATABASE ---
$db_host = "sql104.infinityfree.com";
$db_user = "if0_40256457";
$db_pass = "Thanh03121994";
$db_name = "if0_40256457_leduythanh";
// --------------------------

$ip = isset($_GET['ip']) ? trim($_GET['ip']) : '';
if ($ip == '') {
    echo "0"; // Không có IP -> từ chối
    exit;
}

// 1. Kết nối Database
$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    echo "0"; 
    exit;
}

// 2. Đọc IP đang khóa
$result = $conn->query("SELECT locked_ip FROM ip_lock WHERE id = 1 LIMIT 1");
$locked_ip = '';
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $locked_ip = trim($row['locked_ip']);
}

// 3. Xử lý logic kiểm tra
if ($locked_ip == '') {
    // Chưa có IP nào -> khóa IP hiện tại
    $stmt = $conn->prepare("UPDATE ip_lock SET locked_ip = ? WHERE id = 1");
    $stmt->bind_param("s", $ip);
    if ($stmt->execute()) {
        echo "1"; // Thành công
    } else {
        echo "0"; // Lỗi ghi DB
    }
    $stmt->close();

} elseif ($locked_ip == $ip) {
    // Cùng IP đang hoạt động -> cho phép
    echo "1";

} else {
    // IP khác đang bị khóa -> từ chối
    echo "0";
}

$conn->close();
?>
