<?php
//ของ login
//require('connectdb.php');
session_start();
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
?>



<?php
require('connectdb.php');

// Fetch jobs from the database
$user_account_id = $_SESSION['account_id']; // Replace with the method you use to get the account ID

function fetchJobs($conn,$user_account_id) {
    $job_ad = [];
    $sql = "SELECT * FROM job_ad WHERE job_status = 'approved' AND account_id = '$user_account_id'" ; // Query to select only approved jobs
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $job_ad[] = $row; // Add each row to the job_ad array
    }
    return $job_ad;
}

$job_ad = fetchJobs($connect, $user_account_id);
 // Use the $connect variable from connectdb.php
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

<?php
/* ฟังก์ชันสำหรับคำนวณระยะเวลาที่ผ่านไป หรือแสดงสถานะหมดอายุ */

function time_until_expiry($expiry_date, $full = false) {
    $now = new DateTime(null, new DateTimeZone('Asia/Bangkok'));
    $expiry = new DateTime($expiry_date, new DateTimeZone('Asia/Bangkok'));

    // ตรวจสอบว่าประกาศหมดอายุแล้วหรือไม่
    if ($expiry < $now) {
        return "ประกาศหมดอายุแล้ว";
    }

    // คำนวณเวลาที่เหลือจนถึงวันหมดอายุ
    $diff = $now->diff($expiry);

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
    return $string ? 'เหลือเวลา ' . implode(', ', $string) : 'ขณะนี้';
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


    /* ปรับแต่งปุ่มดูผู้สมัคร */
.view-applicant-button {
    background-color: #4CAF50; /* สีเขียว */
    color: white; /* ข้อความสีขาว */
    border: none; /* ไม่มีขอบ */
    padding: 10px 15px; /* ระยะห่างภายใน */
    border-radius: 5px; /* มุมโค้งมน */
    text-align: center; /* จัดข้อความให้อยู่กลาง */
    text-decoration: none; /* ไม่มีขีดเส้นใต้ */
    display: inline-block; /* ให้แสดงในแถว */
    font-size: 16px; /* ขนาดตัวอักษร */
    transition: background-color 0.3s, transform 0.3s; /* เพิ่มเอฟเฟกต์เปลี่ยนสีและขยายเมื่อวางเมาส์ */
}

.view-applicant-button:hover {
    background-color: #45a049; /* เปลี่ยนสีเมื่อวางเมาส์ */
    transform: scale(1.05); /* ขยายเล็กน้อยเมื่อวางเมาส์ */
}

.button-container {
    margin-top: 20px; /* ระยะห่างด้านบน */
    display: flex; /* จัดเรียงปุ่มในแถว */
    justify-content: flex-start; /* จัดให้เรียงจากซ้าย */
    gap: 10px; /* ระยะห่างระหว่างปุ่ม */
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
        <section class="main-content">
            <h2>จัดการประกาศงาน</h2>
            <!-- ดึงชื่อบริษัทจากงานรายการแรกในกรณีที่มีข้อมูล -->
        <h2>บริษัท : 
            <?php echo !empty($job_ad) && isset($job_ad[0]['company_name']) ? $job_ad[0]['company_name'] : 'ชื่อบริษัท'; ?>
        </h2>


            <a href="job_post.php" class="add-job-button action-btn">เพิ่มงานใหม่</a>


            <table id="jobTable">
                <thead>
                    <tr>
                        <!--th>ID</th-->
                        <!--th>ชื่อบริษัท</th-->
                        <th>ชื่อตำแหน่งงาน</th>
                        <th>รายละเอียด</th>
                        <th>ประเภทงาน</th>
                        <th>จำนวนคนที่ต้องการ</th>
                        <th>เงินเดือน</th>
                        <th>ประกาศงานเมื่อ</th>
                        <th>ประกาศจะหมดอายุเมื่อ</th>
                        <!--th>จัดการคนสมัครงาน</th-->
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($job_ad as $job): ?>
                    <tr>
                        <!--td><!?= $job['job_ad_id'] ?></td-->
                        <!--td><!?= $job['company_name'] ?></td-->
                        <td><?= $job['job_name'] ?></td>
                        <td><?= $job['job_detail'] ?></td>

                        <td><?= ($job['job_type'] == 1) ? 'งานประจำ' : 'งานพาร์ทไทม์'; ?></td>
                        <td><?= $job['job_workers'] ?></td>

                        <!--td><-?= ($job['job_salary'] == 0) ? "ไม่ระบุ" : $job['job_salary'];?></td-->
                        <td><?php 
                        if (isset($job['job_salary']) && $job['job_salary'] > 0) {
                        //echo htmlspecialchars($items['job_salary']);
                        echo number_format($job['job_salary'], 0, '.', ',') . ' บาท';
                        } else {
                        echo 'ไม่ระบุ'; 
                        }
                        ?>
                        </td>

                        <!--td>
                            <div class="button-container">
                                <a class="edit-button action-btn"
                                    href="edit_job_announcement.php?id=<!?= $job['job_ad_id'] ?>">จัดการคนสมัคร</a>
                            </div>
                        </td-->

                        <td><?php echo time_elapsed_string($job['job_create_at']); ?></td>

                        <td><!-- การใช้งานฟังก์ชัน -->
                        <?php
                        $expiry_date = $job['job_expire_at']; // ตัวอย่างวันที่หมดอายุ
                        echo time_until_expiry($expiry_date);
                        ?>
                        </td>

                        <td>
                            <div class="button-container">
                                <a class="view-applicant-button action-btn"
                                    href="view_job_applicants.php?job_id=<?= $job['job_ad_id'] ?>">ดูผู้สมัคร</a>
                                <a class="edit-button action-btn"
                                    href="edit_job_announcement.php?id=<?= $job['job_ad_id'] ?>">แก้ไข</a>
                                <a class="delete-button action-btn" href="delete_job.php?id=<?= $job['job_ad_id'] ?>"
                                    onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบงานนี้?')">ลบ</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
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
</body>

</html>