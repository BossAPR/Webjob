<?php
require 'vendor/autoload.php'; // โหลด Composer autoloader

// ตรวจสอบการล็อกอิน
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// เชื่อมต่อกับฐานข้อมูล
require('connectdb.php');

// รับค่า id จาก URL
$id = $_GET['id'] ?? null;

if ($id) {
    // ดึงข้อมูลอีเมลจากฐานข้อมูลตาม id
    $query = "SELECT email FROM contact_form WHERE id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $email = $row['email'];

        // ตั้งค่า PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // ระบุ SMTP server ของ Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'jirathep28@gmail.com'; // ใส่อีเมลของคุณ
        $mail->Password = 'vjwy vtvw zker gzbk'; // ใส่รหัสผ่านของคุณ
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // กำหนดชุดอักขระเป็น UTF-8
        $mail->CharSet = 'UTF-8';

        // ตั้งค่าอีเมลผู้ส่ง/ผู้รับ
        $mail->setFrom('your_email@gmail.com', '"Webjob Support"');
        $mail->addAddress($email); // ส่งไปยังอีเมลที่ดึงมาจากฐานข้อมูล

        // เนื้อหาอีเมล
        $mail->isHTML(true);
        $mail->Subject = 'การติดต่อกลับจากผู้ดูแลระบบ';
        $mail->Body    = 'สวัสดีครับคุณ,<br><br>เราพร้อมจะช่วยเหลือคุณ  ขอบคุณสำหรับการติดต่อ  ทางเราได้รับข้อมูลของคุณแล้วหากมีปัญหาเพิ่มเติมโปรดติดต่อกลับทางอีเมลข้างต้น<br><br>ทีมงาน';

        // ส่งอีเมล
        if ($mail->send()) {
            echo "<script>
                    alert('ส่งอีเมลไปยัง $email สำเร็จ');
                    window.location.href = 'manage-contacts.php';
                  </script>";
        } else {
            $error = $mail->ErrorInfo;
            echo "<script>
                    alert('เกิดข้อผิดพลาดในการส่งอีเมล: $error');
                    window.location.href = 'manage-contacts.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('ไม่พบที่อยู่อีเมล');
                window.location.href = 'manage-contacts.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ไม่พบ ID');
            window.location.href = 'manage-contacts.php';
          </script>";
}
?>
