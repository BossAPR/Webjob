<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินในฐานะผู้ดูแลหรือไม่
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// ตรวจสอบว่ามีการส่ง doc_id หรือไม่
if (!isset($_POST['doc_id']) || !is_numeric($_POST['doc_id'])) {
    die("ID ไม่ถูกต้อง");
}

$doc_id = $_POST['doc_id'];

// ค้นหาไฟล์ PDF ที่จะลบ
$query = "SELECT doc_file, account_id FROM tbl_pdf WHERE no = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $doc_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die("ไม่พบเอกสารที่ต้องการลบ");
}

$pdf = mysqli_fetch_assoc($result);
$file_to_delete = 'docs/' . $pdf['doc_file'];
$account_id = $pdf['account_id'];

// ลบไฟล์จากเซิร์ฟเวอร์
if (file_exists($file_to_delete)) {
    unlink($file_to_delete);
}

// ลบข้อมูลจากฐานข้อมูล
$query_delete = "DELETE FROM tbl_pdf WHERE no = ?";
$stmt_delete = mysqli_prepare($connect, $query_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $doc_id);
mysqli_stmt_execute($stmt_delete);

// เปลี่ยนเส้นทางกลับไปยัง edit-user.php พร้อมกับ account_id
header('Location: edit-user.php?id=' . $account_id);
exit;

mysqli_close($connect);
?>
