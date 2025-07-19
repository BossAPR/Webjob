<!--?php
session_start();
require('connectdb.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// Get the job ID from the URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the job details
$query = "SELECT * FROM job_ad WHERE job_ad_id = $job_id";
$result = mysqli_query($connect, $query);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    echo "Job not found.";
    exit;
}

// Initialize message variable
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the job details

    $company_name = mysqli_real_escape_string($connect, $_POST['company_name']);
    $job_category = mysqli_real_escape_string($connect, $_POST['job_category']);


    $job_name = mysqli_real_escape_string($connect, $_POST['job_name']);
    $job_detail = mysqli_real_escape_string($connect, $_POST['job_detail']);
    $job_type = intval($_POST['job_type']);
    $job_workers = intval($_POST['job_workers']);
    $job_salary = floatval($_POST['job_salary']);
    $job_time = mysqli_real_escape_string($connect, $_POST['job_time']);
    $job_welfare = mysqli_real_escape_string($connect, $_POST['job_welfare']);
    $sex = mysqli_real_escape_string($connect, $_POST['sex']);
    $age_min = intval($_POST['age_min']);
    $age_max = intval($_POST['age_max']);
    $qualification = mysqli_real_escape_string($connect, $_POST['qualification']);
    $course = mysqli_real_escape_string($connect, $_POST['course']);
    $experience_min = intval($_POST['experience_min']);
    $job_location = mysqli_real_escape_string($connect, $_POST['job_location']);
    $job_province = mysqli_real_escape_string($connect, $_POST['job_province']);
    $job_district = mysqli_real_escape_string($connect, $_POST['job_district']);
    $job_logo = mysqli_real_escape_string($connect, $_POST['job_logo']);

    $job_expire_at = mysqli_real_escape_string($connect, $_POST['job_expire_at']); // Get the expiry date from the form


    $update_query = "UPDATE job_ad SET job_name='$job_name', job_detail='$job_detail', job_type=$job_type, job_workers=$job_workers, job_salary=$job_salary, 
            job_time='$job_time', job_welfare='$job_welfare', sex='$sex', age_min=$age_min, age_max=$age_max, qualification='$qualification', 
            course='$course', experience_min=$experience_min, job_location='$job_location', job_province='$job_province', 
            job_district='$job_district', job_logo='$job_logo' ,job_category='$job_category',company_name='$company_name',
            job_expire_at='$job_expire_at'
            WHERE job_ad_id=$job_id";

    if (mysqli_query($connect, $update_query)) {
    $message = "แก้ไขงานสำเร็จ"; // Set success message
    header("Location: job_announcement.php"); // เปลี่ยนไปที่หน้า job_announcement.php
    exit;
}
 else {
        echo "Error updating job: " . mysqli_error($connect);
    }
}
?-->

<?php
session_start();
require('connectdb.php');
require 'vendor/autoload.php'; // โหลด Composer autoloader สำหรับ PHPMailer

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// Get the job ID from the URL
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the job details
$query = "SELECT * FROM job_ad WHERE job_ad_id = $job_id";
$result = mysqli_query($connect, $query);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    echo "Job not found.";
    exit;
}

// Initialize message variable
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the job details
    $company_name = mysqli_real_escape_string($connect, $_POST['company_name']);
    $job_category = mysqli_real_escape_string($connect, $_POST['job_category']);
    $job_name = mysqli_real_escape_string($connect, $_POST['job_name']);
    $job_detail = mysqli_real_escape_string($connect, $_POST['job_detail']);
    $job_type = intval($_POST['job_type']);
    $job_workers = intval($_POST['job_workers']);
    $job_salary = floatval($_POST['job_salary']);
    $job_time = mysqli_real_escape_string($connect, $_POST['job_time']);
    $job_welfare = mysqli_real_escape_string($connect, $_POST['job_welfare']);
    $sex = mysqli_real_escape_string($connect, $_POST['sex']);
    $age_min = intval($_POST['age_min']);
    $age_max = intval($_POST['age_max']);
    $qualification = mysqli_real_escape_string($connect, $_POST['qualification']);
    $course = mysqli_real_escape_string($connect, $_POST['course']);
    $experience_min = intval($_POST['experience_min']);
    $job_location = mysqli_real_escape_string($connect, $_POST['job_location']);
    $job_province = mysqli_real_escape_string($connect, $_POST['job_province']);
    $job_district = mysqli_real_escape_string($connect, $_POST['job_district']);
    $job_logo = mysqli_real_escape_string($connect, $_POST['job_logo']);
    $job_expire_at = mysqli_real_escape_string($connect, $_POST['job_expire_at']); // Get the expiry date from the form

    // Handle expiration period
    if ($job_expire_at === '1_week') {
        $expire_date = date('Y-m-d', strtotime('+1 week'));
    } elseif ($job_expire_at === '1_month') {
        $expire_date = date('Y-m-d', strtotime('+1 month'));
    } elseif ($job_expire_at === '3_month') {
        $expire_date = date('Y-m-d', strtotime('+3 months'));
    } else {
        $expire_date = $job_expire_at; // If a specific date is selected
    }

    $update_query = "UPDATE job_ad SET job_name='$job_name', job_detail='$job_detail', job_type=$job_type, job_workers=$job_workers, job_salary=$job_salary, 
            job_time='$job_time', job_welfare='$job_welfare', sex='$sex', age_min=$age_min, age_max=$age_max, qualification='$qualification', 
            course='$course', experience_min=$experience_min, job_location='$job_location', job_province='$job_province', 
            job_district='$job_district', job_logo='$job_logo' ,job_category='$job_category',company_name='$company_name',
            job_expire_at='$expire_date'
            WHERE job_ad_id=$job_id";

    if (mysqli_query($connect, $update_query)) {
        $message = "แก้ไขงานสำเร็จ"; // Set success message

        // ดึงข้อมูลผู้สมัครที่สมัครงานนี้
        $applicants_query = "
            SELECT u.account_email, u.account_name 
            FROM job_applications ja
            JOIN users_account u ON ja.account_id = u.account_id
            WHERE ja.job_id = ?";
        $stmt = $connect->prepare($applicants_query);
        $stmt->bind_param('i', $job_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // ส่งอีเมลให้กับผู้สมัครทุกคน
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
            $mail->Subject = 'การอัพเดตประกาศงานจากบริษัท ' . $company_name;
            $mail->Body = 'สวัสดีครับคุณ ' . $name . ' ,<br><br><p>ประกาศงานตำแหน่ง ' . $company_name . ' ได้มีการอัพเดตข้อมูล กรุณาตรวจสอบประกาศใหม่อีกครั้ง</p>';

            // ส่งอีเมล
            if (!$mail->send()) {
                echo "ส่งอีเมลไปยัง $email ไม่สำเร็จ: " . $mail->ErrorInfo;
            }
        }

        header("Location: job_announcement.php"); // เปลี่ยนไปที่หน้า job_announcement.php
        exit;
    } else {
        echo "Error updating job: " . mysqli_error($connect);
    }
}
?>

<?php
//ของ login

require('connectdb.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php'); // เปลี่ยนเส้นทางไปหน้า login ถ้า session ไม่ถูกต้อง
    exit;
}

// สมมติว่า URL รูปโปรไฟล์ถูกเก็บใน $_SESSION['profile_image']
//$account_images = isset($_SESSION['account_images']) ? $_SESSION['account_images'] : 'assets/account_images/default_images_account.jpg';

$company_name = isset($_SESSION['company_name']) ? $_SESSION['company_name'] : '';
$job_category = isset($_SESSION['job_category']) ? $_SESSION['job_category'] : '';

$name = isset($_SESSION['uname']) ? $_SESSION['uname'] : '';

$fname = isset($_SESSION['fname']) ? $_SESSION['fname'] : '';
$lname = isset($_SESSION['lname']) ? $_SESSION['lname'] : '';
$birthday = isset($_SESSION['birthday']) ? $_SESSION['birthday'] : '';
$gender = isset($_SESSION['gender']) ? $_SESSION['gender'] : '';
$addresses = isset($_SESSION['addresses']) ? $_SESSION['addresses'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$phonenumbers = isset($_SESSION['phonenumbers']) ? $_SESSION['phonenumbers'] : '';

//$account_images = $user['account_images'] ?? 'assets/account_images/default_images_account.jpg';

//$account_images = isset($_SESSION['account_images']) ? $_SESSION['account_images'] : 'assets/account_images/default_images_account.jpg';
//$account_images = $_SESSION['account_images'] ?? 'assets/account_images/default_images_account.jpg';

//$account_images = isset($_SESSION['account_images']) ? 'assets/account_images/' . $_SESSION['account_images'] : 'assets/account_images/default_images_account.jpg';


// ดึงข้อมูลโปรไฟล์จากฐานข้อมูล
$user_id = $_SESSION['account_id'];
$query = "SELECT * FROM users_account WHERE account_id = '$user_id'";
$result = mysqli_query($connect, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    // ตรวจสอบว่าล็อกอินผ่าน Google หรือไม่
    if (isset($_SESSION['access_token'])) {
        // ถ้าล็อกอินผ่าน Google ให้ใช้ URL จาก Google
        //$account_images = $user['account_images']; 
        //$account_images = !empty($user['account_images']) ? 'assets/account_images/' . $user['account_images'] : 'assets/account_images/default_images_account.jpg';
        
        // ถ้าล็อกอินผ่าน Google ให้ใช้ URL จาก Google หรือใช้ค่า default ถ้า URL ไม่ถูกต้อง
        if(strpos($user['account_images'], 'https://') === 0 ){
            $account_images = !empty($user['account_images'])  ? $user['account_images'] : 'assets/account_images/default_images_account.jpg';
        }else{
            $account_images = !empty($user['account_images']) ? 'assets/account_images/' . $user['account_images'] . '?' . time() : 'assets/account_images/default_images_account.jpg';
        }
        
    } else {
        // ถ้าล็อกอินแบบปกติ ให้ใช้ภาพจากโฟลเดอร์ assets/account_images/
        $account_images = !empty($user['account_images']) ? 'assets/account_images/' . $user['account_images'] . '?' . time() : 'assets/account_images/default_images_account.jpg';
}
} else {
    $account_images = 'assets/account_images/default_images_account.jpg';
}


//เก็บไฟล์รูปโลโก
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_logo = $_FILES['job_logo'];
    
    // ตรวจสอบว่ามีการอัปโหลดไฟล์หรือไม่
    if ($job_logo['error'] == UPLOAD_ERR_OK) {
        // กำหนดพาธและชื่อไฟล์
        $upload_dir = 'D:/xampp/htdocs/webjob/assets/account_images/';
        $upload_file = $upload_dir . basename($job_logo['name']);
        
        // ย้ายไฟล์ไปยังที่เก็บที่ต้องการ
        if (move_uploaded_file($job_logo['tmp_name'], $upload_file)) {
            // บันทึกชื่อไฟล์ลงในฐานข้อมูล
            $job_logo_name = htmlspecialchars($job_logo['name']);
            // คำสั่ง SQL สำหรับอัปเดตข้อมูล
            $sql = "UPDATE job_ad SET job_logo = '$job_logo_name' WHERE job_id = $job_id"; // $job_id เป็น ID ของงานที่กำลังแก้ไข
            // ประมวลผลคำสั่ง SQL ที่นี่ (ใช้ mysqli หรือ PDO)
        } else {
            echo "การอัปโหลดไฟล์ไม่สำเร็จ!";
        }
    } else {
        // ถ้าไม่มีการอัปโหลดไฟล์ ใช้โลโก้เก่าจากฐานข้อมูล
        $job_logo_name = htmlspecialchars($_POST['old_job_logo']);
    }
}



//เก็บไฟล์เลขนิติบุคคลของบริษัท
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verify_company = $_FILES['verify_company'];
    
    // ตรวจสอบว่ามีการอัปโหลดไฟล์หรือไม่
    if ($verify_company['error'] == UPLOAD_ERR_OK) {
        // กำหนดพาธและชื่อไฟล์
        $upload_dir = 'D:/xampp/htdocs/webjob/verifycompany/';
        $upload_file = $upload_dir . basename($verify_company['name']);
        
        // ย้ายไฟล์ไปยังที่เก็บที่ต้องการ
        if (move_uploaded_file($verify_company['tmp_name'], $upload_file)) {
            // บันทึกชื่อไฟล์ลงในฐานข้อมูล
            $verify_company_name = htmlspecialchars($verify_company['name']);
            // คำสั่ง SQL สำหรับอัปเดตข้อมูล
            $sql = "UPDATE job_ad SET verify_company = '$verify_company_name' WHERE job_id = $job_id"; // $job_id เป็น ID ของงานที่กำลังแก้ไข
            // ประมวลผลคำสั่ง SQL ที่นี่ (ใช้ mysqli หรือ PDO)
        } else {
            echo "การอัปโหลดไฟล์ไม่สำเร็จ!";
        }
    } else {
        // ถ้าไม่มีการอัปโหลดไฟล์ ใช้โลโก้เก่าจากฐานข้อมูล
        $verify_company_name = htmlspecialchars($_POST['old_verify_company']);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Job</title>
    <link rel="stylesheet" href="assets/css/styles (1).css">
    <style>
    .job-detail-container {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    textarea {
        width: calc(100% - 20px);
        padding: 8px;
        margin-bottom: 20px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    input[type="submit"],
    .back-button {
        background-color: #5cb85c;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
        /* ลบการขีดเส้นใต้ */
        display: inline-block;
        /* ทำให้สามารถกำหนด margin ได้ */
        align-items: center;
        justify-content: space-between;
    }

    input[type="submit"]:hover,
    .back-button:hover {
        background-color: #4cae4c;
    }

    .back-button {
        display: flex;
        /* เปลี่ยนเป็น flex */
        justify-content: center;
        /* จัดตำแหน่งข้อความให้ตรงกลาง */
        align-items: center;
        /* จัดแนวแนวตั้ง */
        background-color: chocolate;
        /* สีพื้นหลัง */
        color: white;
        /* สีข้อความ */
        border: none;
        /* ไม่แสดงขอบ */
        border-radius: 4px;
        /* มุมโค้ง */
        padding: 10px 15px;
        /* Padding ข้างใน */
        cursor: pointer;
        /* แสดงเคอร์เซอร์เป็นรูปมือ */
        font-size: 16px;
        /* ขนาดฟอนต์ */
        text-decoration: none;
        /* ลบการขีดเส้นใต้ */
        margin-left: 10px;
        /* เพิ่ม margin ซ้ายเพื่อให้มีช่องว่าง */
    }

    .back-button:hover {
        background-color: coral;
        /* สีพื้นหลังเมื่อวางเมาส์ */
    }
    </style>


    <style>
    .job-logo {
        width: 100px;
        height: auto;
        margin-right: 20px;
    }

    .job-detail {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .job-info {
        font-size: 1.2em;
        line-height: 1.6;
    }

    .job-title {
        font-size: 2em;
        margin-bottom: 10px;
    }

    .back-link {
        margin-top: 20px;
    }

    nav .nav__menu {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    nav .nav__menu .nav__list {
        display: flex;
        gap: 20px;
        margin: 0;
    }

    nav .nav__menu .btn {
        margin-left: 20px;
    }

    /*========== Header Styles ==========*/
    .l-header {
        /*background-color: rgba(255, 255, 255, 0.9); /* สีขาวโปร่งใส */
        position: sticky;
        /* ติดอยู่ที่ด้านบนเมื่อเลื่อน */
        top: 0;
        /* เปลี่ยนค่าเป็น 0 เพื่อนำแถบ header ขึ้นสุด */
        z-index: 1000;
        /* ให้แสดงอยู่ข้างบนสุด */
        padding: 10px 0;
        /* ลด padding ด้านบนและล่างของ header */
    }

    .menu__content {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding: 20px;
        /* เพิ่ม padding เพื่อให้มีระยะระหว่างขอบกับเนื้อหา */
        background-color: whitesmoke;
        /* เพิ่มสีพื้นหลัง */
        border-radius: 10px;
        /* ทำให้มุมโค้งมน */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        /* เพิ่มเงารอบกรอบ */
        transition: box-shadow 0.3s ease;
        /* เพิ่มการเปลี่ยนเงาแบบ smooth เมื่อ hover */

        border-style: inset;
    }

    .menu__content:hover {
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        /* เพิ่มเงาเมื่อ hover */
    }


    .menu__img {
        width: 80px;
        /* ปรับขนาดรูปให้พอดี */
        height: auto;
        /* ทำให้ภาพมีอัตราส่วนถูกต้อง */
        margin-right: 20px;
        /* ระยะห่างระหว่างรูปภาพกับชื่อ */
    }

    .menu__name {
        font-size: 1.5em;
        /* ขนาดของชื่อตำแหน่งงาน */
    }
    </style>

    <style>
    /* Add styles for buttons */
    .edit-button,
    .delete-button {
        text-decoration: none;
        /* Remove underline */
    }

    .edit-button {
        background-color: lightseagreen;
        /* Blue for edit button */
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 3px;
    }

    .delete-button {
        background-color: lightcoral;
        /* Light red for delete button */
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 3px;
    }

    .button-container {
        display: flex;
        gap: 5px;
        /* Space between buttons */
    }

    /* Style for the add job button */
    .add-job-button {
        background-color: green;
        /* Green color for add button */
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        text-decoration: none;
        /* Remove underline */
        display: inline-block;
        margin-bottom: 20px;
    }

    .approve-job-button {
        background-color: lightskyblue;
        /* Light yellow for approve jobs button */
        color: black;
    }

    .unapproved-jobs-button {
        background-color: lightpink;
        /* Light pink for unapproved jobs button */
        color: black;
    }

    .action-btn {
        display: inline-block;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 5px;
        text-decoration: none;
        margin-right: 5px;
        /* เพิ่มระยะห่างระหว่างปุ่ม */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    </style>


</head>

<body>

    <!--========== SCROLL TOP ==========-->
    <a href="#" class="scrolltop" id="scroll-top">
        <i class='bx bx-chevron-up scrolltop__icon'></i>
    </a>

    <!--========== HEADER ==========-->
    <header class="l-header" id="header">

        <nav class="nav bd-container">
            <img href="#" class="logo" src="assets/account_images/2.png">

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li class="nav__item"><a href="indextest.php" class="nav__link">หน้าหลัก</a></li>
                    <li class="nav__item"><a href="af_about.php" class="nav__link">เกี่ยวกับเรา</a></li>
                    <!--li class="nav__item"><a href="#profile" class="nav__link">ข่าวสาร</a></li-->
                    <!--li class="nav__item"><a href="#article" class="nav__link">บทความ</a></li-->
                    <!--li class="nav__item"><a href="#profile" class="nav__link">รีวิว</a></li-->
                    <li class="nav__item"><a href="af_Contact_us.php" class="nav__link">ศูนย์ช่วยเหลือ</a></li>

                    <!--li><i class='bx bx-moon change-theme' id="theme-button"></i></li-->

                    <!--a href="job_post.php"><button type="button" class="btn success"><b>ประกาศหางาน</b></button></a-->
                    <a href="af_job_search.php"><button type="button" class="btn info"><b>บอร์ดหางาน</b></button></a>

                    <a href="job_announcement.php"><button type="button"
                            class="btn success"><b>บอร์ดประกาศหางาน</b></button></a>

                    <a href="af_job_searchbyAI.php"><button type="button" class="btn info"><b>บอร์ดหางานแบบ
                                AI</b></button></a>
                </ul>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="nav__menu" id="nav-menu">
                <a href="edit_profile.php">
                    <!--img src="<?php echo $account_images; ?>" alt="Profile Image" class="nav__profile"
            style="width: 65px; height: 65px; border-radius: 50%; object-fit: cover; gap: 2000px;" -->

                    <img src="<?php echo htmlspecialchars($account_images); ?>" alt="Profile Image" class="nav__profile"
                        style="width: 65px; height: 65px; border-radius: 50%; object-fit: cover;">
                </a>
            </div>
            <div class="nav__menu" id="nav-menu">
                <a href="logout.php" class="nav__link" style="display: flex; align-items: center;">Logout</a>
            </div> &nbsp;
            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-menu'></i>
            </div>
        </nav>
    </header>



    <div class="job-detail-container">
        <h2>Edit Job</h2>

        <!-- Display message if exists -->
        <?php if ($message): ?>
        <script>
        alert("<?= htmlspecialchars($message) ?>"); // Show alert
        location.reload(); // Refresh the page after alert
        </script>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="job_ad_id" value="<?= htmlspecialchars($job['job_ad_id']) ?>">


            <div class="form-group">
                <label for="company_name">ชื่อบริษัท:</label>
                <input type="text" id="company_name" name="company_name"
                    value="<?= htmlspecialchars($job['company_name']) ?>" placeholder="ชื่อบริษัท" required>
            </div><br>

            <div class="form-group">
                <label for="job_name">ชื่อตำแหน่งงาน:</label>
                <input type="text" id="job_name" name="job_name" value="<?= htmlspecialchars($job['job_name']) ?>"
                    placeholder="ชื่อตำแหน่งงาน" required>
            </div><br>


            <div class="form-group">
                <label for="job_category">หมวดหมู่:</label>
                <select id="job_category" name="job_category" style="font-size: 17px; width: 935px" required>
                    <!-- ตัวเลือกค่าเริ่มต้น -->
                    <!--option disabled selected value> -- เลือกหมวดหมู่ -- </option-->

                    <option value="<?= $job['job_category'] ?>"> -- เลือกหมวดหมู่ -- </option>

                    <!-- รายการหมวดหมู่พร้อมแสดงค่าที่เลือก -->
                    <option value="ภาษาและมนุษยศาสตร์"
                        <?php if ($job['job_category'] == 'ภาษาและมนุษยศาสตร์') echo 'selected'; ?>>ภาษาและมนุษยศาสตร์
                    </option>
                    <option value="สังคมศาสตร์และจิตวิทยา"
                        <?php if ($job['job_category'] == 'สังคมศาสตร์และจิตวิทยา') echo 'selected'; ?>>
                        สังคมศาสตร์และจิตวิทยา</option>
                    <option value="วิทยาศาสตร์พื้นฐานและธรรมชาติ"
                        <?php if ($job['job_category'] == 'วิทยาศาสตร์พื้นฐานและธรรมชาติ') echo 'selected'; ?>>
                        วิทยาศาสตร์พื้นฐานและธรรมชาติ</option>
                    <option value="วิศวกรรมศาสตร์"
                        <?php if ($job['job_category'] == 'วิศวกรรมศาสตร์') echo 'selected'; ?>>วิศวกรรมศาสตร์</option>
                    <option value="แพทยศาสตร์และสาธารณสุข"
                        <?php if ($job['job_category'] == 'แพทยศาสตร์และสาธารณสุข') echo 'selected'; ?>>
                        แพทยศาสตร์และสาธารณสุข</option>
                    <option value="การบริหารและการเงิน"
                        <?php if ($job['job_category'] == 'การบริหารและการเงิน') echo 'selected'; ?>>การบริหารและการเงิน
                    </option>
                    <option value="การศึกษาและพัฒนาหลักสูตร"
                        <?php if ($job['job_category'] == 'การศึกษาและพัฒนาหลักสูตร') echo 'selected'; ?>>
                        การศึกษาและพัฒนาหลักสูตร</option>
                    <option value="ศิลปกรรมและการออกแบบ"
                        <?php if ($job['job_category'] == 'ศิลปกรรมและการออกแบบ') echo 'selected'; ?>>
                        ศิลปกรรมและการออกแบบ</option>
                    <option value="สื่อสารมวลชนและประชาสัมพันธ์"
                        <?php if ($job['job_category'] == 'สื่อสารมวลชนและประชาสัมพันธ์') echo 'selected'; ?>>
                        สื่อสารมวลชนและประชาสัมพันธ์</option>
                    <option value="วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ"
                        <?php if ($job['job_category'] == 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ') echo 'selected'; ?>>
                        วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ</option>
                </select>
            </div>



            <div class="form-group">
                <label for="job_detail">รายละเอียด:</label>
                <textarea id="job_detail" name="job_detail" style="height: 150px;" placeholder="รายละเอียด"
                    required><?= htmlspecialchars($job['job_detail']) ?></textarea>
            </div><br>


            <div class="form-group">
                <label for="job_type">ประเภทงาน:</label>
                <select id="job_type" name="job_type" style="height: 30px; width: 935px">
                    <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                    <option value="<?= $job['job_type'] ?>">
                        <?php 
                       if ($job['job_type'] == 1) {
                       echo 'งานประจำ'; 
                       } elseif ($job['job_type'] == 2) {
                       echo 'งานพาร์ทไทม์'; 
                       } else {
                       echo 'เลือกประเภทการจ้างงาน'; // หากไม่มีข้อมูล
                       }
                    ?>
                    </option>

                    <!-- ตัวเลือกอื่นๆ -->
                    <option value="1" <?php if ($job['job_type'] == 1) echo 'selected'; ?>>งานประจำ</option>
                    <option value="2" <?php if ($job['job_type'] == 2) echo 'selected'; ?>>งานพาร์ทไทม์</option>
                </select>
            </div>


            <div class="form-group">
                <label for="job_workers">จำนวนผู้สมัครงานที่ต้องการ:</label>
                <input type="number" id="job_workers" name="job_workers" value="<?= $job['job_workers'] ?>"
                    placeholder="จำนวนผู้สมัครงานที่ต้องการ" required>
            </div>

            <div class="form-group">
                <label for="job_salary">จำนวนเงินเดือน:</label>
                <input type="number" id="job_salary" name="job_salary" value="<?= $job['job_salary'] ?>"
                    placeholder="กรุณากรอกเป็นตัวเลข" required>
            </div>

            <div class="form-group">
                <label for="job_time">เวลาทำงาน:</label>
                <select id="job_time" name="job_time" onchange="showCustomTime()" style="height: 30px; width: 935px">
                    <option value="<?= $job['job_time'] ?>">
                        <?php 
                            if (!empty($job['job_time'])) {
                            // แสดงค่าจากฐานข้อมูลถ้ามี
                                echo $job['job_time']; 
                            } else {
                            // แสดงข้อความ "เลือกเวลาทำงาน" ถ้าไม่มีค่าในฐานข้อมูล
                                echo 'เลือกเวลาทำงาน'; 
                            }
                        ?>
                    </option>

                    <option value="9:00-17:00">9:00-17:00</option>
                    <option value="8:30-16:00">8:30-16:00</option>
                    <option value="7:00-15:00">7:00-15:00</option>
                    <option value="other">อื่นๆ</option>
                </select>
                <!-- ฟิลด์นี้จะซ่อนจนกว่าจะเลือก "อื่นๆ" -->
                <div id="custom_time_field" style="display:none;">
                    <label for="custom_time">กรุณาระบุเวลาทำงาน:</label>
                    <input type="text" id="custom_time" name="custom_time" placeholder="เช่น 10:00-18:00">
                </div>
            </div>

            <!--div class="form-group">
                <label for="job_welfare">สวัสดิการ:</label>
                <input type="text" id="job_welfare" name="job_welfare"
                    value="<1?= htmlspecialchars($job['job_welfare']) ?>">
            </div-->

            <div class="form-group">
                <label for="job_welfare">สวัสดิการ:</label>
                <textarea id="job_welfare" name="job_welfare" style=" height: 150px;"
                    placeholder="เช่น ค่าน้ำมันรถ, ค่าเดินทาง, ค่าเบี้ยเลี้ยง"
                    required><?= htmlspecialchars($job['job_welfare']) ?></textarea>
            </div><br>

            <div class="form-group">
                <label for="sex">เพศ:</label>
                <!--input type="text" id="sex" name="job_sex" value="<!?= htmlspecialchars($job['sex']) ?>"-->
                <select id="sex" name="sex" style="height: 30px; width: 935px">
                    <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                    <option value="<?= $job['sex'] ?>">
                        <?php 
                       if ($job['sex'] == 0) {
                       echo 'ชาย'; 
                       } elseif ($job['sex'] == 1) {
                       echo 'หญิง'; 
                       }elseif ($job['sex'] == 2) {
                        echo 'อื่นๆ'; 
                        } else {
                       echo 'เลือกเพศ'; // หากไม่มีข้อมูล
                       }
                    ?>
                    </option>
                    <!-- ตัวเลือกอื่นๆ -->
                    <option value="0" <?php if ($job['sex'] == 0) echo 'selected'; ?>>ชาย</option>
                    <option value="1" <?php if ($job['sex'] == 1) echo 'selected'; ?>>หญิง</option>
                    <option value="2" <?php if ($job['sex'] == 2) echo 'selected'; ?>>อื่นๆ</option>
                </select>
            </div><br>

            <div class="form-group">
                <label for="age_min">อายุขั้นต่ำที่รับ:</label>
                <input type="number" id="age_min" name="age_min" placeholder="อายุขั้นต่ำที่รับ"
                    value="<?= $job['age_min'] ?>">
            </div>

            <div class="form-group">
                <label for="age_max">อายุมากสุดที่รับ:</label>
                <input type="number" id="age_max" name="age_max" placeholder="อายุมากสุดที่รับ"
                    value="<?= $job['age_max'] ?>">
            </div>

            <div class="form-group">
                <label for="qualification">วุฒิการศึกษา:</label>
                <select id="qualification" name="qualification" style="height: 30px; width: 935px">
                    <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                    <option value="<?= $job['qualification'] ?>">
                        <?php 
        if ($job['qualification'] == 0) {
            echo 'ไม่มีการศึกษา'; 
        } elseif ($job['qualification'] == 1) {
            echo 'ประถมศึกษา'; 
        } elseif ($job['qualification'] == 2) {
            echo 'มัธยมศึกษาตอนต้น'; 
        } elseif ($job['qualification'] == 3) {
            echo 'มัธยมศึกษาตอนปลายหรือเทียบเท่า'; 
        } elseif ($job['qualification'] == 4) {
            echo 'อนุปริญญา'; 
        } elseif ($job['qualification'] == 5) {
            echo 'ปริญญาตรีขึ้นไปหรือเทียบเท่า'; 
        } else {
            echo 'เลือกวุฒิการศึกษา'; // หากไม่มีข้อมูล
        }
        ?>
                    </option>

                    <!-- ตัวเลือกอื่นๆ -->
                    <option value="0" <?php if ($job['qualification'] == 0) echo 'selected'; ?>>ไม่มีวุฒิการศึกษา
                    </option>
                    <option value="1" <?php if ($job['qualification'] == 1) echo 'selected'; ?>>ประถมศึกษา</option>
                    <option value="2" <?php if ($job['qualification'] == 2) echo 'selected'; ?>>มัธยมศึกษาตอนต้น
                    </option>
                    <option value="3" <?php if ($job['qualification'] == 3) echo 'selected'; ?>>
                        มัธยมศึกษาตอนปลายหรือเทียบเท่า</option>
                    <option value="4" <?php if ($job['qualification'] == 4) echo 'selected'; ?>>อนุปริญญา</option>
                    <option value="5" <?php if ($job['qualification'] == 5) echo 'selected'; ?>>
                        ปริญญาตรีขึ้นไปหรือเทียบเท่า</option>
                </select>

            </div>

            <div class="form-group">
                <label for="job_course">หลักสูตรที่ต้องการ:</label>
                <select id="course" name="course" style="height: 30px; width: 935px">
                    <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                    <option value="<?= $job['course'] ?>">
                        <?php 
        if (!empty($job['course'])) {
            echo htmlspecialchars($job['course']);
        } else {
            echo 'เลือกหลักสูตร'; // หากไม่มีข้อมูล
        }
        ?>
                    </option>
                    <!-- ตัวเลือกหลักสูตร -->
                    <option value="0" <?php if ($job['course'] == 0) echo 'selected'; ?>>ภาษาไทย</option>
                    <option value="1" <?php if ($job['course'] == 1) echo 'selected'; ?>>ภาษาอังกฤษ</option>
                    <option value="2" <?php if ($job['course'] == 2) echo 'selected'; ?>>ภาษาต่างประเทศ</option>
                    <option value="3" <?php if ($job['course'] == 3) echo 'selected'; ?>>มนุษยศาสตร์</option>
                    <option value="4" <?php if ($job['course'] == 4) echo 'selected'; ?>>สังคมศาสตร์</option>
                    <option value="5" <?php if ($job['course'] == 5) echo 'selected'; ?>>จิตวิทยา</option>
                    <option value="6" <?php if ($job['course'] == 6) echo 'selected'; ?>>คณิตศาสตร์</option>
                    <option value="7" <?php if ($job['course'] == 7) echo 'selected'; ?>>ฟิสิกส์</option>
                    <option value="8" <?php if ($job['course'] == 8) echo 'selected'; ?>>เคมี</option>
                    <option value="9" <?php if ($job['course'] == 9) echo 'selected'; ?>>ชีววิทยา</option>
                    <option value="10" <?php if ($job['course'] == 10) echo 'selected'; ?>>วิทยาศาสตร์สิ่งแวดล้อม
                    </option>
                    <option value="11" <?php if ($job['course'] == 11) echo 'selected'; ?>>วิศวกรรมไฟฟ้า</option>
                    <option value="12" <?php if ($job['course'] == 12) echo 'selected'; ?>>วิศวกรรมเครื่องกล</option>
                    <option value="13" <?php if ($job['course'] == 13) echo 'selected'; ?>>วิศวกรรมโยธา</option>
                    <option value="14" <?php if ($job['course'] == 14) echo 'selected'; ?>>วิศวกรรมสารสนเทศ</option>
                    <option value="15" <?php if ($job['course'] == 15) echo 'selected'; ?>>วิศวกรรมการบิน</option>
                    <option value="16" <?php if ($job['course'] == 16) echo 'selected'; ?>>แพทยศาสตร์</option>
                    <option value="17" <?php if ($job['course'] == 17) echo 'selected'; ?>>ทันตแพทยศาสตร์</option>
                    <option value="18" <?php if ($job['course'] == 18) echo 'selected'; ?>>เภสัชศาสตร์</option>
                    <option value="19" <?php if ($job['course'] == 19) echo 'selected'; ?>>สาธารณสุขศาสตร์</option>
                    <option value="20" <?php if ($job['course'] == 20) echo 'selected'; ?>>การพยาบาล</option>
                    <option value="21" <?php if ($job['course'] == 21) echo 'selected'; ?>>การจัดการ</option>
                    <option value="22" <?php if ($job['course'] == 22) echo 'selected'; ?>>การตลาด</option>
                    <option value="23" <?php if ($job['course'] == 23) echo 'selected'; ?>>การเงิน</option>
                    <option value="24" <?php if ($job['course'] == 24) echo 'selected'; ?>>บัญชี</option>
                    <option value="25" <?php if ($job['course'] == 25) echo 'selected'; ?>>เศรษฐศาสตร์</option>
                    <option value="26" <?php if ($job['course'] == 26) echo 'selected'; ?>>การศึกษา</option>
                    <option value="27" <?php if ($job['course'] == 27) echo 'selected'; ?>>จิตวิทยาการศึกษา</option>
                    <option value="28" <?php if ($job['course'] == 28) echo 'selected'; ?>>การพัฒนาหลักสูตร</option>
                    <option value="29" <?php if ($job['course'] == 29) echo 'selected'; ?>>ศิลปกรรม</option>
                    <option value="30" <?php if ($job['course'] == 30) echo 'selected'; ?>>การออกแบบผลิตภัณฑ์</option>
                    <option value="31" <?php if ($job['course'] == 31) echo 'selected'; ?>>การออกแบบกราฟิก</option>
                    <option value="32" <?php if ($job['course'] == 32) echo 'selected'; ?>>สถาปัตยกรรม</option>
                    <option value="33" <?php if ($job['course'] == 33) echo 'selected'; ?>>สื่อสารมวลชน</option>
                    <option value="34" <?php if ($job['course'] == 34) echo 'selected'; ?>>การโฆษณา</option>
                    <option value="35" <?php if ($job['course'] == 35) echo 'selected'; ?>>การประชาสัมพันธ์</option>
                    <option value="36" <?php if ($job['course'] == 36) echo 'selected'; ?>>วิทยาการคอมพิวเตอร์</option>
                    <option value="37" <?php if ($job['course'] == 37) echo 'selected'; ?>>เทคโนโลยีสารสนเทศ</option>
                    <option value="38" <?php if ($job['course'] == 38) echo 'selected'; ?>>ความมั่นคงไซเบอร์</option>
                </select>

            </div>

            <div class="form-group">
                <label for="experience_min">ประสบการณ์ (ปี):</label>
                <input type="number" id="experience_min" name="experience_min" placeholder="ประสบการณ์ (ปี)"
                    value="<?= $job['experience_min'] ?>">
            </div>

            <div class="form-group">
                <label for="job_location">สถานที่ทำงาน:</label>
                <input type="text" id="job_location" name="job_location"
                    value="<?= htmlspecialchars($job['job_location']) ?>">
            </div>

            <div class="form-group">
                <label for="job_province">จังหวัด:</label>
                <select id="job_province" name="job_province" style="height: 30px; width: 935px">
                    <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                    <option value="<?= $job['job_province'] ?>">
                        <?php 
        if (!empty($job['job_province'])) {
            echo htmlspecialchars($job['job_province']);
        } else {
            echo 'เลือกจังหวัด'; // หากไม่มีข้อมูล
        }
        ?>
                    </option>
                    <!-- ตัวเลือกจังหวัด -->
                    <option value="1" <?php if ($job['job_province'] == 1) echo 'selected'; ?>>กรุงเทพมหานคร</option>
                    <option value="2" <?php if ($job['job_province'] == 2) echo 'selected'; ?>>สมุทรปราการ</option>
                    <option value="3" <?php if ($job['job_province'] == 3) echo 'selected'; ?>>นนทบุรี</option>
                    <option value="4" <?php if ($job['job_province'] == 4) echo 'selected'; ?>>ปทุมธานี</option>
                    <option value="5" <?php if ($job['job_province'] == 5) echo 'selected'; ?>>พระนครศรีอยุธยา</option>
                    <option value="6" <?php if ($job['job_province'] == 6) echo 'selected'; ?>>อ่างทอง</option>
                    <option value="7" <?php if ($job['job_province'] == 7) echo 'selected'; ?>>ลพบุรี</option>
                    <option value="8" <?php if ($job['job_province'] == 8) echo 'selected'; ?>>สิงห์บุรี</option>
                    <option value="9" <?php if ($job['job_province'] == 9) echo 'selected'; ?>>ชัยนาท</option>
                    <option value="10" <?php if ($job['job_province'] == 10) echo 'selected'; ?>>สระบุรี</option>
                    <option value="11" <?php if ($job['job_province'] == 11) echo 'selected'; ?>>ชลบุรี</option>
                    <option value="12" <?php if ($job['job_province'] == 12) echo 'selected'; ?>>ระยอง</option>
                    <option value="13" <?php if ($job['job_province'] == 13) echo 'selected'; ?>>จันทบุรี</option>
                    <option value="14" <?php if ($job['job_province'] == 14) echo 'selected'; ?>>ตราด</option>
                    <option value="15" <?php if ($job['job_province'] == 15) echo 'selected'; ?>>ฉะเชิงเทรา</option>
                    <option value="16" <?php if ($job['job_province'] == 16) echo 'selected'; ?>>ปราจีนบุรี</option>
                    <option value="17" <?php if ($job['job_province'] == 17) echo 'selected'; ?>>นครนายก</option>
                    <option value="18" <?php if ($job['job_province'] == 18) echo 'selected'; ?>>สระแก้ว</option>
                    <option value="19" <?php if ($job['job_province'] == 19) echo 'selected'; ?>>นครราชสีมา</option>
                    <option value="20" <?php if ($job['job_province'] == 20) echo 'selected'; ?>>บุรีรัมย์</option>
                    <option value="21" <?php if ($job['job_province'] == 21) echo 'selected'; ?>>สุรินทร์</option>
                    <option value="22" <?php if ($job['job_province'] == 22) echo 'selected'; ?>>ศรีสะเกษ</option>
                    <option value="23" <?php if ($job['job_province'] == 23) echo 'selected'; ?>>อุบลราชธานี</option>
                    <option value="24" <?php if ($job['job_province'] == 24) echo 'selected'; ?>>ยโสธร</option>
                    <option value="25" <?php if ($job['job_province'] == 25) echo 'selected'; ?>>ชัยภูมิ</option>
                    <option value="26" <?php if ($job['job_province'] == 26) echo 'selected'; ?>>อำนาจเจริญ</option>
                    <option value="27" <?php if ($job['job_province'] == 27) echo 'selected'; ?>>หนองบัวลำภู</option>
                    <option value="28" <?php if ($job['job_province'] == 28) echo 'selected'; ?>>ขอนแก่น</option>
                    <option value="29" <?php if ($job['job_province'] == 29) echo 'selected'; ?>>อุดรธานี</option>
                    <option value="30" <?php if ($job['job_province'] == 30) echo 'selected'; ?>>เลย</option>
                    <option value="31" <?php if ($job['job_province'] == 31) echo 'selected'; ?>>หนองคาย</option>
                    <option value="32" <?php if ($job['job_province'] == 32) echo 'selected'; ?>>มหาสารคาม</option>
                    <option value="33" <?php if ($job['job_province'] == 33) echo 'selected'; ?>>ร้อยเอ็ด</option>
                    <option value="34" <?php if ($job['job_province'] == 34) echo 'selected'; ?>>กาฬสินธุ์</option>
                    <option value="35" <?php if ($job['job_province'] == 35) echo 'selected'; ?>>สกลนคร</option>
                    <option value="36" <?php if ($job['job_province'] == 36) echo 'selected'; ?>>นครพนม</option>
                    <option value="37" <?php if ($job['job_province'] == 37) echo 'selected'; ?>>มุกดาหาร</option>
                    <option value="38" <?php if ($job['job_province'] == 38) echo 'selected'; ?>>เชียงใหม่</option>
                    <option value="39" <?php if ($job['job_province'] == 39) echo 'selected'; ?>>ลำพูน</option>
                    <option value="40" <?php if ($job['job_province'] == 40) echo 'selected'; ?>>ลำปาง</option>
                    <option value="41" <?php if ($job['job_province'] == 41) echo 'selected'; ?>>อุตรดิตถ์</option>
                    <option value="42" <?php if ($job['job_province'] == 42) echo 'selected'; ?>>แพร่</option>
                    <option value="43" <?php if ($job['job_province'] == 43) echo 'selected'; ?>>น่าน</option>
                    <option value="44" <?php if ($job['job_province'] == 44) echo 'selected'; ?>>พะเยา</option>
                    <option value="45" <?php if ($job['job_province'] == 45) echo 'selected'; ?>>เชียงราย</option>
                    <option value="46" <?php if ($job['job_province'] == 46) echo 'selected'; ?>>แม่ฮ่องสอน</option>
                    <option value="47" <?php if ($job['job_province'] == 47) echo 'selected'; ?>>นครสวรรค์</option>
                    <option value="48" <?php if ($job['job_province'] == 48) echo 'selected'; ?>>อุทัยธานี</option>
                    <option value="49" <?php if ($job['job_province'] == 49) echo 'selected'; ?>>กำแพงเพชร</option>
                    <option value="50" <?php if ($job['job_province'] == 50) echo 'selected'; ?>>ตาก</option>
                    <option value="51" <?php if ($job['job_province'] == 51) echo 'selected'; ?>>สุโขทัย</option>
                    <option value="52" <?php if ($job['job_province'] == 52) echo 'selected'; ?>>พิษณุโลก</option>
                    <option value="53" <?php if ($job['job_province'] == 53) echo 'selected'; ?>>พิจิตร</option>
                    <option value="54" <?php if ($job['job_province'] == 54) echo 'selected'; ?>>เพชรบูรณ์</option>
                    <option value="55" <?php if ($job['job_province'] == 55) echo 'selected'; ?>>ราชบุรี</option>
                    <option value="56" <?php if ($job['job_province'] == 56) echo 'selected'; ?>>กาญจนบุรี</option>
                    <option value="57" <?php if ($job['job_province'] == 57) echo 'selected'; ?>>สุพรรณบุรี</option>
                    <option value="58" <?php if ($job['job_province'] == 58) echo 'selected'; ?>>นครปฐม</option>
                    <option value="59" <?php if ($job['job_province'] == 59) echo 'selected'; ?>>สมุทรสาคร</option>
                    <option value="60" <?php if ($job['job_province'] == 60) echo 'selected'; ?>>สมุทรสงคราม</option>
                    <option value="61" <?php if ($job['job_province'] == 61) echo 'selected'; ?>>เพชรบุรี</option>
                    <option value="62" <?php if ($job['job_province'] == 62) echo 'selected'; ?>>ประจวบคีรีขันธ์
                    </option>
                    <option value="63" <?php if ($job['job_province'] == 63) echo 'selected'; ?>>นครศรีธรรมราช</option>
                    <option value="64" <?php if ($job['job_province'] == 64) echo 'selected'; ?>>กระบี่</option>
                    <option value="65" <?php if ($job['job_province'] == 65) echo 'selected'; ?>>พังงา</option>
                    <option value="66" <?php if ($job['job_province'] == 66) echo 'selected'; ?>>ภูเก็ต</option>
                    <option value="67" <?php if ($job['job_province'] == 67) echo 'selected'; ?>>สุราษฎร์ธานี</option>
                    <option value="68" <?php if ($job['job_province'] == 68) echo 'selected'; ?>>ระนอง</option>
                    <option value="69" <?php if ($job['job_province'] == 69) echo 'selected'; ?>>ชุมพร</option>
                    <option value="70" <?php if ($job['job_province'] == 70) echo 'selected'; ?>>สงขลา</option>
                    <option value="71" <?php if ($job['job_province'] == 71) echo 'selected'; ?>>สตูล</option>
                    <option value="72" <?php if ($job['job_province'] == 72) echo 'selected'; ?>>ตรัง</option>
                    <option value="73" <?php if ($job['job_province'] == 73) echo 'selected'; ?>>พัทลุง</option>
                    <option value="74" <?php if ($job['job_province'] == 74) echo 'selected'; ?>>ปัตตานี</option>
                    <option value="75" <?php if ($job['job_province'] == 75) echo 'selected'; ?>>ยะลา</option>
                    <option value="76" <?php if ($job['job_province'] == 76) echo 'selected'; ?>>นราธิวาส</option>
                    <option value="77" <?php if ($job['job_province'] == 77) echo 'selected'; ?>>บึงกาฬ</option>
                </select>

            </div>

            <div class="form-group">
                <label for="job_district">อำเภอ:</label>
                <input type="text" id="job_district" name="job_district"
                    value="<?= htmlspecialchars($job['job_district']) ?>">
            </div>

            <div class="form-group">
                <label for="job_logo">โลโก้บริษัท:</label>
                <input type="file" id="job_logo" name="job_logo" accept="image/*" style="height: 30px; width: 935px">
                <input type="hidden" name="old_job_logo" value="<?= htmlspecialchars($job['job_logo']) ?>">
            </div>

            <div class="form-group">
                <label for="verify_company">ไฟล์เลขนิติบุคคลของบริษัท:</label>
                <input type="file" id="verify_company" name="verify_company" accept="image/*" style="height: 30px; width: 935px">
                <input type="hidden" name="old_verify_company" value="<?= htmlspecialchars($job['verify_company']) ?>">
            </div>

            <!-- Add expiry date field -->
            <div class="form-group">
    <label for="job_expire_at">วันหมดอายุของประกาศงาน:</label>
    <input type="datetime-local" id="job_expire_at" name="job_expire_at" value="<?= htmlspecialchars($job['job_expire_at']) ?>" >

    <label for="job_expire_at">หรือเลือกเป็นช่วงวันหมดอายุของประกาศงาน:</label>
    <select id="job_expire_at" name="job_expire_at" required>
        <option value="1_week" <?= $job['job_expire_at'] == '1_week' ? 'selected' : '' ?>>1 อาทิตย์</option>    
        <option value="1_month" <?= $job['job_expire_at'] == '1_month' ? 'selected' : '' ?>>1 เดือน</option>
        <option value="3_month" <?= $job['job_expire_at'] == '3_month' ? 'selected' : '' ?>>3 เดือน</option>
    </select>
</div><br>

            <input type="submit" value="แก้ไขงาน" class="back-button" style="height: 50px; width: 935px"><br><br><br>
            <a href="job_announcement.php" class="back-button">ย้อนกลับ</a>
        </form>
    </div>


    <!--========== FOOTER ==========-->
    <footer class="footer section bd-container">
        <div class="footer__container bd-grid">
            <div class="footer__content">
                <img href="#" class="logo" src="assets/account_images/2.png">
                <span class="footer__description">JOB SEARCH</span>
                <div>
                    <a href="#" class="footer__social"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="footer__social"><i class='bx bxl-instagram'></i></a>
                    <a href="#" class="footer__social"><i class='bx bxl-twitter'></i></a>
                </div>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">บริการ</h3>
                <ul>
                    <li><a href="indextest.php" class="footer__link">หน้าแรก</a></li>
                    <li><a href="job_post.php" class="footer__link">ประกาศหาพนักงาน</a></li>
                    <li><a href="af_job_search.php" class="footer__link">หางาน</a></li>
                </ul>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">ข้อมูล</h3>
                <ul>
                    <li><a href="af_Contact_us.php" class="footer__link">ติดต่อเรา</a></li>
                    <li><a href="af_about.php" class="footer__link">เกี่ยวกับเรา</a></li>
                </ul>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">ที่อยู่</h3>
                <ul>
                    <li>กรุงเทพ ประเทศไทย</li>
                    <li>ถนนบรม 88</li>
                    <li>099 - 888 - 7777</li>
                    <li>@email.com</li>
                </ul>
            </div>
        </div>

        <p class="footer__copy">&#169; 2024 WEBJOB. All right reserved</p>
    </footer>

    <script>
    function showCustomTime() {
        var jobTimeSelect = document.getElementById('job_time');
        var customTimeField = document.getElementById('custom_time_field');

        if (jobTimeSelect.value === 'other') {
            customTimeField.style.display = 'block'; // แสดงฟิลด์ให้กรอกเวลาทำงาน
        } else {
            customTimeField.style.display = 'none'; // ซ่อนฟิลด์ถ้าเลือกช่วงเวลาที่กำหนด
        }
    }
    </script>

</body>

</html>