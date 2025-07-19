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
    // ดึงข้อมูลอีเมลและชื่อจากฐานข้อมูลตาม id
    $query = "SELECT account_email, account_name FROM users_account WHERE account_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $email = $row['account_email'];
        $name = $row['account_name'];

        // ตั้งค่า PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jirathep28@gmail.com';
        $mail->Password = 'vjwy vtvw zker gzbk';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // กำหนดชุดอักขระเป็น UTF-8
        $mail->CharSet = 'UTF-8';

        // ตั้งค่าอีเมลผู้ส่ง/ผู้รับ
        $mail->setFrom('your_email@gmail.com', '"Webjob Support"');
        $mail->addAddress($email);

        // เนื้อหาอีเมล
        $mail->isHTML(true);
        $mail->Subject = 'การติดต่อกลับจากผู้ดูแลระบบ';
        $mail->Body = 'สวัสดีครับคุณ ' . $name . ' ,<br><br>เรามีเรื่องที่ต้องการติดต่อคุณ หากได้รับข้อความแล้ว<br>โปรดติดต่อกลับทางอีเมลข้างต้น<br><br>ทีมงาน';

        // ส่งอีเมล
        if ($mail->send()) {
            echo "<script>
                    alert('ส่งอีเมลไปยัง $email สำเร็จ');
                    history.back();
                  </script>";
        } else {
            $error = $mail->ErrorInfo;
            echo "<script>
                    alert('เกิดข้อผิดพลาดในการส่งอีเมล: $error');
                    history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('ไม่พบที่อยู่อีเมล');
                history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('ไม่พบ ID');
            history.back();
          </script>";
}
?>
