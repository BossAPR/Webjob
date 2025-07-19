<?php
session_start();
require('connectdb.php');

$id = $_GET['id'];

// เริ่มต้นการทำธุรกรรม
mysqli_begin_transaction($connect);

try {
    // ลบข้อมูลในตาราง applicant ที่มี account_id ตรงกัน
    $query_applicant = "DELETE FROM applicant WHERE account_id = ?";
    $stmt_applicant = mysqli_prepare($connect, $query_applicant);
    mysqli_stmt_bind_param($stmt_applicant, 'i', $id);
    mysqli_stmt_execute($stmt_applicant);
    
    // ลบข้อมูลในตาราง users_account
    $query_users = "DELETE FROM users_account WHERE account_id = ?";
    $stmt_users = mysqli_prepare($connect, $query_users);
    mysqli_stmt_bind_param($stmt_users, 'i', $id);
    mysqli_stmt_execute($stmt_users);
    
    // ยืนยันการทำธุรกรรม
    mysqli_commit($connect);
    
    // ย้ายไปยังหน้า manage-users.php
    header('Location: manage-users.php');
    exit;
} catch (Exception $e) {
    // หากมีข้อผิดพลาดเกิดขึ้น ให้ยกเลิกธุรกรรม
    mysqli_rollback($connect);
    // คุณสามารถจัดการข้อผิดพลาดที่นี่ได้
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}

// ปิดการเชื่อมต่อ
mysqli_close($connect);
?>
