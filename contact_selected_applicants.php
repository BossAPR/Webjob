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

// รับค่า application_id หลายค่าในรูปแบบของอาร์เรย์จาก POST
$application_ids = $_POST['selected_applicants'] ?? [];

if (!empty($application_ids)) {
    foreach ($application_ids as $application_id) {
        // ดึงข้อมูลจาก job_applications และ job_ad ตาม application_id
        $query = "
            SELECT ja.account_id, ja.job_id, j.company_name, j.job_name 
            FROM job_applications ja
            JOIN job_ad j ON ja.job_id = j.job_ad_id
            WHERE ja.application_id = ?
        ";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('i', $application_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $applicationRow = $result->fetch_assoc();

        if ($applicationRow) {
            $account_id = $applicationRow['account_id'];
            $job_name = $applicationRow['job_name']; // ตำแหน่งงานที่สมัคร
            $company_name = $applicationRow['company_name']; // ชื่อบริษัท

            // ดึงข้อมูลอีเมลและชื่อจาก users_account ตาม account_id
            $query = "SELECT account_email, account_name FROM users_account WHERE account_id = ?";
            $stmt = $connect->prepare($query);
            $stmt->bind_param('i', $account_id);
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
                $mail->Username = 'jirathep28@gmail.com'; // อีเมลผู้ส่ง
                $mail->Password = 'vjwy vtvw zker gzbk'; // รหัสผ่านของอีเมล
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // กำหนดชุดอักขระเป็น UTF-8
                $mail->CharSet = 'UTF-8';

                // ตั้งค่าอีเมลผู้ส่ง/ผู้รับ
                $mail->setFrom('your_email@gmail.com', '"Webjob Support"'); // อีเมลผู้ส่ง
                $mail->addAddress($email);

                // เนื้อหาอีเมล
                $mail->isHTML(true);
                $mail->Subject = 'ข้อเสนอการทำงานจากบริษัท ' . $company_name;
                $mail->Body = 'สวัสดีครับคุณ ' . $name . ' ,<br><br> <p>เรายินดีที่จะแจ้งข้อเสนอการทำงานในตำแหน่ง ' . $job_name . ' ให้คุณสนใจติดต่อกลับทางอีเมลข้างต้น</p>';

                // ส่งอีเมล
                if ($mail->send()) {
                    echo "<script>
                            alert('ส่งอีเมลไปยัง $email สำเร็จ');
                          </script>";
                } else {
                    $error = $mail->ErrorInfo;
                    echo "<script>
                            alert('เกิดข้อผิดพลาดในการส่งอีเมล: $error');
                          </script>";
                }
            } else {
                echo "<script>
                        alert('ไม่พบที่อยู่อีเมล');
                      </script>";
            }
        } else {
            echo "<script>
                    alert('ไม่พบข้อมูลการสมัครสำหรับ ID: $application_id');
                  </script>";
        }
    }
    echo "<script>history.back();</script>";
} else {
    echo "<script>
            alert('ไม่พบ ID');
            history.back();
          </script>";
}
?>
