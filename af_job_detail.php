<?php
session_start();
require('connectdb.php');

// Check if job_id is set
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to fetch job details by job_id
        $stmt = $conn->prepare("SELECT * FROM job_ad WHERE job_ad_id = :job_id");
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmt->execute();

        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$job) {
            echo "ไม่พบงานที่คุณต้องการดูรายละเอียด";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit; // Stop execution on error
    }
} else {
    echo "ไม่มีการระบุงานที่ต้องการดูรายละเอียด";
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// Fetch user profile from the database
$user_id = $_SESSION['account_id'];

// Use PDO for user data fetching
$query = "SELECT * FROM users_account WHERE account_id = :account_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':account_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch applicant data using PDO
$query = "SELECT * FROM applicant WHERE account_id = :account_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':account_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$userapplicant = $stmt->fetch(PDO::FETCH_ASSOC);
$applicant_id = $userapplicant['applicant_id'] ?? '';

?>






<?php
//ของ login
//require('connectdb.php');
require('connectdb.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php'); // เปลี่ยนเส้นทางไปหน้า login ถ้า session ไม่ถูกต้อง
    exit;
}

// สมมติว่า URL รูปโปรไฟล์ถูกเก็บใน $_SESSION['profile_image']
//$account_images = isset($_SESSION['account_images']) ? $_SESSION['account_images'] : 'assets/account_images/default_images_account.jpg';

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



// ดึงข้อมูลโปรไฟล์จากฐานข้อมูล applicant
$user_id = $_SESSION['account_id'];
$query = "SELECT * FROM applicant WHERE account_id = '$user_id'";
$result = mysqli_query($connect, $query);

$userapplicant = mysqli_fetch_assoc($result);


$start_date = $userapplicant['start_date'] ?? '';
$employment_type = $userapplicant['employment_type'] ?? '';
$preferred_location = $userapplicant['preferred_location'] ?? '';
$work_eligibility = $userapplicant['work_eligibility'] ?? '';
$expected_salary = $userapplicant['expected_salary'] ?? '';
$interested_job_type = $userapplicant['interested_job_type'] ?? '';



// ฟิลด์ใหม่ที่เพิ่ม
$conscription = $userapplicant['conscription'] ?? '';  // เกณฑ์ทหาร
$work_type = $userapplicant['work_type'] ?? '';         // ประเภทการทำงาน online/onsite

$old = $userapplicant['old'] ?? '';         // ช่วงอายุ
$sex = $userapplicant['sex'] ?? '';
$qualification = $userapplicant['qualification'] ?? '';
$course = $userapplicant['course'] ?? '';
$experience = $userapplicant['experience'] ?? '';

$applicant_id = $userapplicant['applicant_id'] ?? '';

?>

<?php
// ดึงข้อมูลโปรไฟล์จากฐานข้อมูล applicant
$user_id = $_SESSION['account_id'];
$query = "SELECT * FROM applicant WHERE account_id = '$user_id'";
$result = mysqli_query($connect, $query);

$userapplicant = mysqli_fetch_assoc($result);

// ฟิลด์ที่ใช้ในการตรวจสอบว่าโปรไฟล์สมบูรณ์หรือไม่
$isProfileComplete = isset($userapplicant['sex']) && (trim($userapplicant['sex']) !== '' || $userapplicant['sex'] === '0') &&
                     isset($userapplicant['old']) && (trim($userapplicant['old']) !== '' || $userapplicant['old'] === '0') &&
                     isset($userapplicant['qualification']) && (trim($userapplicant['qualification']) !== '' || $userapplicant['qualification'] === '0') &&
                     isset($userapplicant['course']) && (trim($userapplicant['course']) !== '' || $userapplicant['course'] === '0') &&
                     isset($userapplicant['experience']) && (trim($userapplicant['experience']) !== '' || $userapplicant['experience'] === '0');




/*
var_dump(empty(trim($userapplicant['sex'])));  // ตรวจสอบว่า empty หลังจาก trim หรือไม่
var_dump(empty(trim($userapplicant['old'])));
var_dump(empty(trim($userapplicant['qualification'])));
var_dump(empty(trim($userapplicant['course'])));
var_dump(empty(trim($userapplicant['experience'])));
*/

                     
?>

<?php
/* โค้ด PHP สำหรับคำนวณระยะเวลาที่ผ่านไป*/

function time_elapsed_string($datetime, $full = false) {
    /*$now = new DateTime;
    $ago = new DateTime($datetime);*/

    $now = new DateTime(null, new DateTimeZone('Asia/Bangkok')); // ใช้โซนเวลาในกรุงเทพ
    $ago = new DateTime($datetime, new DateTimeZone('Asia/Bangkok')); // กำหนดให้ตรงกับเวลาของ job_create_at

    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'ปี',
        'm' => 'เดือน',
        'w' => 'สัปดาห์',
        'd' => 'วัน',
        'h' => 'ชั่วโมง',
        'i' => 'นาที',
        's' => 'วินาที',
    ];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . 'ที่ผ่านมา' : 'ขณะนี้';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดงาน</title>

    <!--========== CSS ==========-->
    <link rel="stylesheet" href="assets/css/styles (1).css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">

    <style>
    .job-detail-container {
        margin: 20px;
        padding: 20px;
        background-color: whitesmoke;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

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

    /*ปุ่ม apply*/

    /* กำหนด style สำหรับปุ่ม */
    .apply-button {
        background-color: #28a745;
        /* สีพื้นหลัง */
        color: white;
        /* สีตัวอักษร */
        border: none;
        /* ไม่มีขอบ */
        padding: 10px 20px;
        /* ระยะห่างภายใน */
        font-size: 16px;
        /* ขนาดตัวอักษร */
        border-radius: 5px;
        /* มุมโค้ง */
        cursor: pointer;
        /* เปลี่ยนเคอร์เซอร์ */
        transition: background-color 0.3s ease;
        /* เอฟเฟคการเปลี่ยนสี */
    }

    /* เปลี่ยนสีเมื่อชี้ไปที่ปุ่ม */
    .apply-button:hover {
        background-color: #218838;
        /* สีเมื่อชี้ */
    }
    </style>

<!-- Styles for the popup -->
<style>
    #applyPopup {
    display: none;
}

    .popup {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
        display: flex;
        justify-content: center; /* แนวนอนกลาง */
        align-items: center; /* แนวตั้งกลาง */
    }

    .popup-content {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        width: 400px;
        text-align: center;
        position: relative; /* Add relative positioning for the close button */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between; /* จัดปุ่มให้ห่างจากกัน */
    }

    .close-btn {
        font-size: 30px;
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 10px; /* ปรับขนาดตำแหน่งให้ "X" อยู่ในมุมขวา */
        cursor: pointer;
    }

    .apply-button, .cancel-button {
        background-color: #4CAF50; /* Green */
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        width: 100%; /* ให้ปุ่มมีขนาดตามเนื้อหาภายใน */
        max-width: 90%; /* ให้ปุ่มไม่กว้างเกินกรอบ */
        border-radius: 5px;
        margin: 5px 0; /* เว้นระยะห่างระหว่างปุ่ม */
        font-family: Arial, sans-serif; /* กำหนดฟอนต์ */
        font-size: 16px; /* กำหนดขนาดฟอนต์ให้เท่ากัน */
    }

    .cancel-button {
        background-color: #f44336; /* Red */
    }

    .apply-button:hover, .cancel-button:hover {
        opacity: 0.8;
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

                    <a href="job_announcement.php" ><button type="button" 
                            class="btn success"><b>บอร์ดประกาศหางาน</b></button></a>

                    <a href="af_job_searchbyAI.php"><button type="button"
                            class="btn info"><b>บอร์ดหางานแบบ AI</b></button></a>

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
        <div class="job-detail">
            <img src="assets/account_images/<?php echo htmlspecialchars($job['job_logo']); ?>" alt="Job Logo"
                class="job-logo">
            <div class="job-info">
                <h1 class="job-title"><?php echo htmlspecialchars($job['job_name']); ?></h1>
                <!--p><strong>ประเภทงาน:</strong> <-?php echo htmlspecialchars($job['job_type']); ?></p-->

                <p><strong>ประเภทงาน:</strong> 
                <?php 
                $job_type = htmlspecialchars($job['job_type']);
    
                // แปลงประเภทงาน
                if ($job_type == 1) {
                    echo "งานประจำ";
                } elseif ($job_type == 2) {
                    echo "งานพาร์ทไทม์";
                } else {
                    echo "ประเภทงานไม่ระบุ";
                }
                ?>
                </p>

                <p><strong>จำนวนเงิน:</strong>
                    <?php
                        if (isset($job['job_salary']) && !empty($job['job_salary'])) {
                        // เพิ่มการคั่นพัน
                        $formatted_salary = number_format($job['job_salary']);
                        echo htmlspecialchars($formatted_salary) . ' บาท';
                        } else {
                        echo 'ไม่ระบุ';
                        }
                    ?>
                </p>

                <p><strong>เวลาทำงาน:</strong>
                    <?php
                        if (isset($job['job_time']) && !empty($job['job_time'])) {
                        echo htmlspecialchars($job['job_time']) . ' น.';
                        } else {
                        echo 'ไม่ระบุ';
                        }
                    ?>
                </p>
                
                <p><strong>สถานที่ทำงาน:</strong>
                    <?php echo isset($job['job_location']) ? htmlspecialchars($job['job_location']) : 'ไม่ระบุ'; ?></p>
                <p><strong>รายละเอียด:</strong> <?php echo htmlspecialchars($job['job_detail']); ?></p>

                <p><strong>โพสต์เมื่อ:</strong>
                    <?php echo isset($job['job_create_at']) ? time_elapsed_string($job['job_create_at']) : 'ไม่ระบุ'; ?>
                </p>

                <?php
                // คำนวณความเหมาะสมสำหรับงานนี้
                //$suitability = calculateSuitability($applicant_data, $job);
                ?>
                <!--p><strong>ความเหมาะสม: </strong><-?php echo $suitability; ?>%</p-->
            </div>
        </div>

        <!--form method="POST" action="">
            <input type="hidden" name="applicant_id" value="<-?php echo htmlspecialchars($applicant_id); ?>">
            <input type="hidden" name="account_id" value="<-?php echo htmlspecialchars($user_id); ?>">
            <button type="submit">ส่งข้อมูลสมัครงาน</button>
        </form-->

        <!--form method="POST">
            < ข้อมูลงานที่ต้องการสมัคร >
            <input type="hidden" name="job_id" value="<-?php echo $job['job_ad_id']; ?>">
            <button type="submit" name="apply">สมัครงาน</button>
        </form-->
        <br>
        <?php $suitability=0;?>
        <center>
            <form action="send_application.php" method="POST" id="applicationForm">
            <!--form action="send_application.php" method="POST" id="applicationForm"-->
                <input type="hidden" name="job_id" value="<?= htmlspecialchars($job['job_ad_id']) ?>">
                <input type="hidden" name="account_id" value="<?= htmlspecialchars($user_id) ?>">
                <!--input type="hidden" name="Suitability" value="<!?= htmlspecialchars($suitability) ?>"-->
                <input type="hidden" name="Suitability" value="<?= htmlspecialchars($suitability ?: 0) ?>">
                <button type="button" id="apply-button"name="apply" class="apply-button" >สมัครงาน</button>
            </form><br>
        </center>

        <a href="af_job_search.php" class="back-link">กลับไปยังหน้าบอร์ดหางาน</a>

<!-- ป็อปอัพยืนยันการสมัคร -->
<div id="applyPopup" class="popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <h2>ยืนยันการสมัครงาน</h2>
        <p>คุณแน่ใจหรือไม่ว่าจะสมัครงานนี้?</p>
        <button id="confirmApplyBtn" class="apply-button">ยืนยัน</button>
        <button class="cancel-button" onclick="closePopup()">ยกเลิก</button>
    </div>
</div>


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
// ส่งค่าจาก PHP ไปยัง JavaScript
const isProfileComplete = <?php echo json_encode($isProfileComplete); ?>;
console.log(isProfileComplete); // ตรวจสอบว่าเป็น true หรือ false

document.addEventListener("DOMContentLoaded", function() {
    const applyButton = document.querySelector('#apply-button');
    const popup = document.getElementById('applyPopup');
    const closeBtn = document.querySelector('.close-btn');
    const confirmApplyBtn = document.getElementById('confirmApplyBtn');
    const applicationForm = document.getElementById('applicationForm');

    // ตั้งค่า popup ให้ปิดเป็นค่าเริ่มต้น
    popup.style.display = 'none';

    // แสดงป็อปอัพเมื่อกดปุ่ม "สมัครงาน"
    if (applyButton) {
        applyButton.addEventListener('click', () => {
            popup.style.display = 'flex'; // แสดงป็อปอัพ
        });
    }

    // ปิดป็อปอัพเมื่อคลิกปุ่ม "x"
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            popup.style.display = 'none'; // ซ่อนป็อปอัพ
        });
    }

    // เมื่อกดปุ่ม "ยืนยัน" ในป็อปอัพ, ตรวจสอบโปรไฟล์
    if (confirmApplyBtn) {
        confirmApplyBtn.addEventListener('click', () => {
            popup.style.display = 'none'; // ปิดป็อปอัพ

            // ตรวจสอบโปรไฟล์ก่อนยืนยันการสมัคร
            if (isProfileComplete) {
                alert('สมัครงานเรียบร้อย!');
                // เมื่อโปรไฟล์สมบูรณ์, ส่งฟอร์มไปที่ send_application.php
                setTimeout(() => {
                    applicationForm.action = 'send_application.php'; // ระบุ action ของฟอร์ม
                    applicationForm.submit(); // ส่งฟอร์มไปยัง send_application.php
                }, 500); // หน่วงเวลา 500 มิลลิวินาที (0.5 วินาที)
            } 

            else {
                alert('กรุณาเติมข้อมูลโปรไฟล์ให้ครบถ้วน');
                // ถ้าโปรไฟล์ไม่สมบูรณ์, ไปที่หน้า edit_profile.php
                setTimeout(() => {
                    window.location.href = 'edit_profile.php'; // เปลี่ยนไปที่หน้า edit_profile.php
                }, 500); // หน่วงเวลา 500 มิลลิวินาที (0.5 วินาที)
            }
        });
    }

    // ปิดป็อปอัพเมื่อคลิกนอกป็อปอัพ
    window.addEventListener('click', (event) => {
        if (event.target === popup) {
            popup.style.display = 'none'; // ปิดป็อปอัพ
        }
    });
});

// Function to close the popup
function closePopup() {
    document.getElementById('applyPopup').style.display = 'none';
}
</script>
</body>
</html>