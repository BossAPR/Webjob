<?php
session_start();
require('connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_POST);  // ดูค่าที่ถูกส่งมาจากฟอร์ม
    exit;
}*/


/*if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply'])) {*/
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $job_id = $_POST['job_id'];
    $user_id = $_POST['account_id'];
    //$suitability = $_POST['Suitability'];
    $suitability = !empty($_POST['Suitability']) ? $_POST['Suitability'] : 0; // กำหนดค่าเป็น 0 หากว่าง


    // ตรวจสอบว่า job_id และ user_id ไม่ว่างเปล่า
    if (empty($user_id) || empty($job_id)) {
        echo "<script>alert('ข้อมูลไม่ถูกต้อง');</script>";
        exit;
    }

    try {
        
        // เพิ่มข้อมูลการสมัครงานลงในตาราง job_applications
        $insertQuery = "INSERT INTO job_applications (account_id, job_id, application_date, Suitability) 
        VALUES (:account_id, :job_id, NOW(), :suitability)";
        //$insertQuery = "INSERT INTO job_applications (account_id, job_id, application_date) VALUES (:account_id, :job_id, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(':account_id', $user_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':suitability', $suitability, PDO::PARAM_INT);
        $insertStmt->execute();

        $updateQuery = "UPDATE job_ad SET got_workers = got_workers + 1 WHERE job_ad_id = :job_id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $updateStmt->execute();


        if ($insertStmt->rowCount() > 0) {
            // ดึงข้อมูลผู้สมัครและข้อมูลบัญชีผู้ใช้งาน
            $query = "SELECT a.*, u.account_name, u.account_email 
                      FROM applicant a 
                      JOIN users_account u ON a.account_id = u.account_id 
                      WHERE a.account_id = :account_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':account_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $applicantDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$applicantDetails) {
                echo "<script>alert('ไม่พบข้อมูลผู้สมัคร');</script>";
                exit;
            }

            // ดึงข้อมูลอีเมลของบริษัทและชื่อตำแหน่งงาน
            $query = "SELECT job_mail, job_name FROM job_ad WHERE job_ad_id = :job_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
            $stmt->execute();
            $companyDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$companyDetails || empty($companyDetails['job_mail'])) {
                echo "<script>alert('ไม่พบอีเมลของบริษัท');</script>";
                exit;
            }

            $companyEmail = $companyDetails['job_mail'];
            $jobName = $companyDetails['job_name'];

            // ดึงไฟล์ resume จาก tbl_pdf
            $query = "SELECT doc_file FROM tbl_pdf WHERE account_id = :account_id AND doc_name = 'resume'";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':account_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $resumeDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            $resumePath = '';
            if ($resumeDetails && !empty($resumeDetails['doc_file'])) {
                $resumePath = 'D:/xampp/htdocs/webjob/docs/' . $resumeDetails['doc_file'];
            }

            // เตรียมข้อมูลสำหรับ HTML ในอีเมล
            $sexText = ($applicantDetails['sex'] === 0) ? 'ชาย' : (($applicantDetails['sex'] === 1) ? 'หญิง' : 'อื่น ๆ');
            $qualificationText = [
                0 => 'ไม่มีการศึกษา',
                1 => 'ประถมศึกษา',
                2 => 'มัธยมศึกษาตอนต้น',
                3 => 'มัธยมศึกษาตอนปลายหรือเทียบเท่า',
                4 => 'อนุปริญญา',
                5 => 'ปริญญาตรีขึ้นไปหรือเทียบเท่า'
            ][$applicantDetails['qualification']] ?? 'ไม่ระบุ';
            $courseText = [
            0 => 'ภาษาไทย',
            1 => 'ภาษาอังกฤษ',
            2 => 'ภาษาต่างประเทศ',
            3 => 'มนุษยศาสตร์',
            4 => 'สังคมศาสตร์',
            5 => 'จิตวิทยา',
            6 => 'คณิตศาสตร์',
            7 => 'ฟิสิกส์',
            8 => 'เคมี',
            9 => 'ชีววิทยา',
            10 => 'วิทยาศาสตร์สิ่งแวดล้อม',
            11 => 'วิศวกรรมไฟฟ้า',
            12 => 'วิศวกรรมเครื่องกล',
            13 => 'วิศวกรรมโยธา',
            14 => 'วิศวกรรมสารสนเทศ',
            15 => 'วิศวกรรมการบิน',
            16 => 'แพทยศาสตร์',
            17 => 'ทันตแพทยศาสตร์',
            18 => 'เภสัชศาสตร์',
            19 => 'สาธารณสุขศาสตร์',
            20 => 'การพยาบาล',
            21 => 'การจัดการ',
            22 => 'การตลาด',
            23 => 'การเงิน',
            24 => 'บัญชี',
            25 => 'เศรษฐศาสตร์',
            26 => 'การศึกษา',
            27 => 'จิตวิทยาการศึกษา',
            28 => 'การพัฒนาหลักสูตร',
            29 => 'ศิลปกรรม',
            30 => 'การออกแบบผลิตภัณฑ์',
            31 => 'การออกแบบกราฟิก',
            32 => 'สถาปัตยกรรม',
            33 => 'สื่อสารมวลชน',
            34 => 'การโฆษณา',
            35 => 'การประชาสัมพันธ์',
            36 => 'วิทยาการคอมพิวเตอร์',
            37 => 'เทคโนโลยีสารสนเทศ',
            38 => 'ความมั่นคงไซเบอร์'
            ][$applicantDetails['course']] ?? 'ไม่ระบุ';
            $employmentTypeText = ($applicantDetails['employment_type'] == 1) ? 'งานประจำ' : 'งานพาร์ทไทม์';


            // ดึงข้อมูล Suitability จากฐานข้อมูล
            $query = "SELECT Suitability FROM job_applications WHERE account_id = :account_id AND job_id = :job_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':account_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
            $stmt->execute();
            $suitabilityRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            // กำหนดค่าของ suitability
            $suitability = $suitabilityRecord['Suitability'] ?? 0;

            // ตั้งค่าข้อความ suitability
            $suitabilityText = ($suitability == 0) ? 'ไม่พบค่าความเหมาะสม' : "เหมาะสมที่ {$suitability}%";


            // เนื้อหาอีเมล
            $mailContent = "
                <h2>สนใจสมัครงานในตำแหน่ง: {$jobName}</h2>
                <p><strong>ข้อมูลผู้สมัคร:</strong></p>
                <ul>
                    <li><strong>ชื่อบัญชี:</strong> {$applicantDetails['account_name']}</li>
                    <li><strong>อีเมล:</strong> {$applicantDetails['account_email']}</li>
                    <li><strong>เพศ:</strong> {$sexText}</li>
                    <li><strong>อายุ:</strong> {$applicantDetails['old']} ปี</li>
                    <li><strong>วุฒิการศึกษา:</strong> {$qualificationText}</li>
                    <li><strong>สาขาที่จบ:</strong> {$courseText}</li>
                    <li><strong>ประสบการณ์ทำงาน:</strong> {$applicantDetails['experience']} ปี</li>
                    <li><strong>เงินเดือนที่คาดหวัง:</strong> {$applicantDetails['expected_salary']} บาท</li>
                    <li><strong>วันที่สามารถเริ่มงานได้:</strong> {$applicantDetails['start_date']}</li>
                    <li><strong>การเกณฑ์ทหาร:</strong> {$applicantDetails['conscription']}</li>
                    <li><strong>ประเภทการจ้างงานที่ต้องการ:</strong> {$employmentTypeText}</li>
                    <li><strong>ความเหมาะสม:</strong> {$suitabilityText}</li>
                </ul>
            ";

            // ส่งอีเมลโดยใช้ PHPMailer
            $mail = new PHPMailer();
            $mail->isSMTP();

            $mail->SMTPDebug = 2; 
            $mail->Debugoutput = 'html';

            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jirathep28@gmail.com';
            $mail->Password = 'vjwy vtvw zker gzbk';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('jirathep28@gmail.com', 'Webjob Support');
            $mail->addAddress($companyEmail);

            // ตรวจสอบว่า $companyEmail ไม่ว่างเปล่า
            if (empty($companyEmail)) {
                echo "<script>alert('ไม่สามารถส่งอีเมลได้: ไม่พบอีเมลของบริษัท');</script>";
                exit;
            }

            $mail->isHTML(true);
            $mail->Subject = 'ใบสมัครงาน';
            $mail->Body = $mailContent;

            // แนบ resume ถ้ามี
            if (!empty($resumePath) && file_exists($resumePath)) {
                $mail->addAttachment($resumePath, 'resume.pdf');
            }

            // ตรวจสอบสถานะการส่ง
            if ($mail->send()) {
                echo "<script>alert('ส่งอีเมลเรียบร้อยแล้ว'); window.location.href='af_job_search.php';</script>";
                exit();
            } else {
                echo "<script>alert('ไม่สามารถส่งอีเมลได้: {$mail->ErrorInfo}'); window.location.href='af_job_search.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('การสมัครงานไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('เกิดข้อผิดพลาด: {$e->getMessage()}');</script>";
        exit;
    }
} else {
    echo "<script>alert('ไม่สามารถดำเนินการได้');</script>";
    exit;
}
?>