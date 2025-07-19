<?php
session_start(); // เริ่มเซสชัน
require('connectdb.php');
require 'vendor/autoload.php'; // โหลด Composer autoloader สำหรับ PHPMailer

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // ดึงข้อมูลผู้สมัครที่สมัครงานนี้ก่อนลบงาน
    $applicants_query = "
        SELECT u.account_email, u.account_name 
        FROM job_applications ja
        JOIN users_account u ON ja.account_id = u.account_id
        WHERE ja.job_id = ?";
    $stmt = $connect->prepare($applicants_query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // ส่งอีเมลให้กับผู้สมัครทุกคนก่อนลบ
    while ($row = $result->fetch_assoc()) {
        $email = $row['account_email'];
        $name = $row['account_name'];

        // ตั้งค่า PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jirathep28@gmail.com'; // อีเมลผู้ส่ง
        $mail->Password = 'vjwy vtvw zker gzbk'; // รหัสผ่านของอีเมล
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom('your_email@gmail.com', '"Webjob Support"');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'การอัพเดตประกาศงานจากบริษัท';
        $mail->Body = 'สวัสดีครับคุณ ' . $name . ' ,<br><br><p>ขอแจ้งให้ทราบว่า ประกาศงานที่คุณสมัครได้ถูกลบออกจากระบบแล้ว</p>';

        // ส่งอีเมล
        if (!$mail->send()) {
            echo "ส่งอีเมลไปยัง $email ไม่สำเร็จ: " . $mail->ErrorInfo;
        }
    }

    // ลบข้อมูลจากตาราง job_applications ที่เกี่ยวข้อง
    $delete_applications_sql = "DELETE FROM job_applications WHERE job_id = $id";
    if ($connect->query($delete_applications_sql) === TRUE) {
        // ลบงานจากตาราง job_ad
        $sql = "DELETE FROM job_ad WHERE job_ad_id = $id";

        if ($connect->query($sql) === TRUE) {
            $_SESSION['message'] = "ลบข้อมูลสำเร็จ";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: " . $connect->error;
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Error: " . $connect->error;
        $_SESSION['message_type'] = "error";
    }

    $connect->close();
    header("Location: job_announcement.php");
    exit();
}
?>
