<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินในฐานะผู้ดูแลหรือไม่
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// ตรวจสอบว่ามีการส่ง account_id หรือไม่
if (!isset($_POST['account_id']) || !is_numeric($_POST['account_id'])) {
    die("ข้อมูลไม่ถูกต้อง");
}

$account_id = $_POST['account_id'];

// ตรวจสอบการอัปโหลดไฟล์ PDF ใหม่
if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
    $pdf_file = $_FILES['pdf_file'];
    $upload_dir = 'docs/';
    
    // สร้างชื่อไฟล์ใหม่
    $numrand = mt_rand(); // สุ่มหมายเลข
    $date1 = date('Ymd_His'); // วันที่ปัจจุบัน
    $typefile = strrchr($pdf_file['name'], "."); // ดึงนามสกุลไฟล์
    $newname = 'doc_' . $numrand . '_' . $date1 . $typefile; // ตั้งชื่อไฟล์ใหม่
    $path_copy = $upload_dir . $newname;

    // ย้ายไฟล์
    if (move_uploaded_file($pdf_file['tmp_name'], $path_copy)) {
        // อัปเดตข้อมูลไฟล์ PDF ในฐานข้อมูล
        $query = "INSERT INTO tbl_pdf (account_id, doc_name, doc_file) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, "iss", $account_id, $_POST['new_doc_name'], $newname); // ใช้ชื่อไฟล์ใหม่
        mysqli_stmt_execute($stmt);
    } else {
        die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์");
    }
}

// ตรวจสอบการอัปเดตชื่อเอกสารที่มีอยู่
if (isset($_POST['doc_name']) && isset($_POST['pdf_id'])) {
    $doc_names = $_POST['doc_name'];
    $pdf_ids = $_POST['pdf_id'];

    // ทำการอัปเดตชื่อเอกสารสำหรับแต่ละรายการ
    for ($i = 0; $i < count($doc_names); $i++) {
        $doc_name = $doc_names[$i];
        $pdf_id = $pdf_ids[$i];

        $query_update = "UPDATE tbl_pdf SET doc_name = ? WHERE no = ?";
        $stmt_update = mysqli_prepare($connect, $query_update);
        mysqli_stmt_bind_param($stmt_update, "si", $doc_name, $pdf_id);
        mysqli_stmt_execute($stmt_update);
    }
}

// เปลี่ยนไปยังหน้า edit-user.php พร้อมกับ account_id
header('Location: edit-user.php?id=' . $account_id);
exit;

mysqli_close($connect);
?>
