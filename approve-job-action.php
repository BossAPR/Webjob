<!--?php
session_start();
require('connectdb.php');

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];

    // Update job status to approved
    //$sql = "UPDATE job_ad SET job_status = 'approved' WHERE job_ad_id = ?";
    $sql = "UPDATE job_ad SET job_status = 'approved'and job_create_at = now() and job_expire_at = DATE_ADD(NOW(), INTERVAL 7 DAY)  WHERE job_ad_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $job_id);

    if ($stmt->execute()) {
        // Redirect back to the approve-job.php page with success message
        $_SESSION['message'] = "งานได้รับการอนุมัติเรียบร้อยแล้ว!";
        header("Location: approve-job.php");
        exit();
    } else {
        // Handle error
        $_SESSION['error'] = "ไม่สามารถอนุมัติงานได้!";
        header("Location: approve-job.php");
        exit();
    }
} else {
    // Redirect back if no ID is provided
    header("Location: approve-job.php");
    exit();
}
?-->


<?php 
session_start();
require('connectdb.php');
require 'vendor/autoload.php'; // PHPMailer

// เปิดการแสดงข้อผิดพลาด
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];

    // อัปเดตสถานะงาน
    $sql = "UPDATE job_ad SET job_status = 'approved', job_create_at = NOW() WHERE job_ad_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $job_id);

    if ($stmt->execute()) {
        echo "งานได้รับการอนุมัติเรียบร้อยแล้ว!<br>";

        // ส่งอีเมลถึงผู้สมัคร
        sendRecommendationEmails($job_id, $connect);
        
        $_SESSION['message'] = "งานได้รับการอนุมัติเรียบร้อยแล้ว!";
        header("Location: approve-job.php");
        exit();
    } else {
        echo "ไม่สามารถอัปเดตสถานะงานได้";
    }
} else {
    echo "ไม่พบข้อมูลงาน";
}

function sendRecommendationEmails($job_id, $connect) {
    // Fetch job information from the database
    $sql_job = "SELECT job_name, job_location, job_salary, job_detail AS job_description, job_logo, job_create_at 
                FROM job_ad WHERE job_ad_id = ?";
    $stmt_job = $connect->prepare($sql_job);
    $stmt_job->bind_param("i", $job_id);
    $stmt_job->execute();
    $result_job = $stmt_job->get_result();
    $job = $result_job->fetch_assoc();
    
    $job_name = $job['job_name'];
    $job_location = $job['job_location'];
    $job_salary = $job['job_salary'] . ' บาท';  // เพิ่ม 'บาท' หลังเงินเดือน
    $job_description = $job['job_description'];
    $job_logo = $job['job_logo']; // ชื่อไฟล์รูปภาพ
    $job_create_at = $job['job_create_at'];

    // ตรวจสอบสิทธิ์การเข้าถึงไฟล์รูปภาพ
    $job_image_path = $_SERVER['DOCUMENT_ROOT'] . '/webjob/assets/account_images/' . $job_logo;
    if (!is_readable($job_image_path)) {
        echo "ไม่สามารถเข้าถึงไฟล์รูปภาพได้";
        return;  // หยุดการทำงานหากไม่สามารถเข้าถึงไฟล์
    }

    // Fetch applicant information
    $sql_applicant = "
        SELECT a.applicant_id, ua.account_email
        FROM applicant a
        JOIN users_account ua ON a.account_id = ua.account_id
    ";
    $result_applicant = $connect->query($sql_applicant);

    if ($result_applicant->num_rows > 0) {
        while ($applicant = $result_applicant->fetch_assoc()) {
            $email = $applicant['account_email'];
            sendEmail($email, $job_name, $job_location, $job_salary, $job_description, $job_logo, $job_image_path, $job_create_at);
        }
    }
}

function sendEmail($to, $job_name, $job_location, $job_salary, $job_description, $job_logo, $job_image_path, $job_create_at) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jirathep28@gmail.com';
    $mail->Password = 'vjwy vtvw zker gzbk';  // ใช้รหัสผ่านแอป
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    $mail->setFrom('jirathep28@gmail.com', 'Job search');
    $mail->addAddress($to);

    // ตั้งการเข้ารหัสเป็น UTF-8
    $mail->CharSet = 'UTF-8';

    // เพิ่มเนื้อหาในอีเมล
    $mail->isHTML(true);
    $mail->Subject = "คำแนะนำงาน: $job_name";  // แก้หัวข้อเป็นภาษาไทย

    // ถ้าใช้ CID ให้เพิ่ม img tag ที่อ้างอิงกับ CID
    $mail->Body = "
    <h1>คำแนะนำงาน: $job_name</h1>
    <img src='cid:job_logo' alt='รูปงาน' width='200'>  <!-- ใช้ CID ใน src -->
    <p><strong>สถานที่:</strong> $job_location</p>
    <p><strong>เงินเดือน:</strong> $job_salary</p>
    <p><strong>รายละเอียดงาน:</strong> $job_description</p>
    <p><strong>ลงประกาศเมื่อ:</strong> " . date("d/m/Y", strtotime($job_create_at)) . "</p>
    <p><strong>เมื่อ:</strong> " . timeElapsedString($job_create_at) . "</p>
    
    <p>ขอแสดงความนับถือ,<br>Job search</p>
    ";

    // แนบไฟล์รูปภาพไปกับอีเมล โดยใช้ CID
    $mail->addEmbeddedImage($job_image_path, 'job_logo', $job_logo);  // การแนบไฟล์ภาพ

    // หากไม่สามารถส่งอีเมลได้
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Email sent successfully to $to";
    }
}

// ฟังก์ชันแสดงเวลาที่ผ่านมา
function timeElapsedString($datetime, $full = false) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = array();
    $units = array(
        'y' => 'ปี',
        'm' => 'เดือน',
        'd' => 'วัน',
        'h' => 'ชั่วโมง',
        'i' => 'นาที',
        's' => 'วินาที'
    );

    foreach ($units as $key => $value) {
        if ($diff->$key) {
            $string[] = $diff->$key . ' ' . $value;
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return implode(', ', $string) . ' ที่ผ่านมา';
}
?>
