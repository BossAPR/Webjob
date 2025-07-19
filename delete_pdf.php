<?php
require_once 'connect.php';

if (isset($_POST['doc_id'])) {
    $doc_id = $_POST['doc_id'];
    
    // ตรวจสอบว่ามี record ในฐานข้อมูลหรือไม่
    $stmt = $conn->prepare("SELECT doc_file FROM tbl_pdf WHERE no = :doc_id");
    $stmt->bindParam(':doc_id', $doc_id, PDO::PARAM_INT);
    $stmt->execute();
    $file = $stmt->fetch();

    if ($file) {
        // ลบไฟล์ออกจากโฟลเดอร์
        $file_path = 'docs/' . $file['doc_file'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // ลบข้อมูลจากฐานข้อมูล
        $stmt = $conn->prepare("DELETE FROM tbl_pdf WHERE no = :doc_id");
        $stmt->bindParam(':doc_id', $doc_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo '<script>alert("ลบไฟล์สำเร็จ"); window.location="upload_pdf.php";</script>';
        } else {
            echo '<script>alert("เกิดข้อผิดพลาดในการลบไฟล์"); window.location="upload_pdf.php";</script>';
        }
    }
}
?>
