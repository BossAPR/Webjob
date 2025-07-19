<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินในฐานะผู้ดูแลหรือไม่
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// รับค่า ID จาก URL
$contact_id = $_GET['id'] ?? null;

if (!$contact_id) {
    echo "ไม่พบข้อมูลที่ต้องการลบ";
    exit;
}

// ลบข้อมูลการติดต่อจากฐานข้อมูล
$query = "DELETE FROM contact_form WHERE id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $contact_id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: manage-contacts.php');
    exit;
} else {
    echo "Error deleting contact: " . mysqli_error($connect);
}

mysqli_stmt_close($stmt);
mysqli_close($connect);
?>
