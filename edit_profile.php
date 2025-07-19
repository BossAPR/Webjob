<?php
session_start();
// เชื่อมต่อกับฐานข้อมูล
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินหรือยัง
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php'); // เปลี่ยนเส้นทางไปหน้า login ถ้า session ไม่ถูกต้อง
    exit;
}

// ตรวจสอบว่ามีการตั้งค่า session สำหรับ user_id หรือยัง
if (!isset($_SESSION['account_id'])) {
    echo "User ID is not set in the session.";
    exit();
}


// ดึงข้อมูลโปรไฟล์จากฐานข้อมูล users_account
$user_id = $_SESSION['account_id'];
$query = "SELECT * FROM users_account WHERE account_id = '$user_id'";
$result = mysqli_query($connect, $query);

//check อีกที
$user_id = $_SESSION['account_id'];
$query = "SELECT * FROM users_account WHERE account_id = '$user_id'";
$result = mysqli_query($connect, $query);

if (!$result) {
    echo "Error executing query: " . mysqli_error($connect);
    exit();
}

$user = mysqli_fetch_assoc($result);
if (!$user) {
    echo "Error fetching user data.";
    exit();
}

$name = $user['account_name'] ?? '';

$fname = $user['first_name'] ?? '';
$lname = $user['last_name'] ?? '';
$birthday = $user['birthday'] ?? '';
$gender = $user['gender'] ?? '';
$addresses = $user['addresses'] ?? '';
$email = $user['account_email'] ?? '';
$phonenumbers = $user['phone_numbers'] ?? '';

//รูปภาพ
//$account_images = isset($_SESSION['account_images']) ? $_SESSION['account_images'] : 'assets/account_images/default_images_account.jpg';
//$account_images = !empty($user['account_images']) ? 'assets/account_images/' . $user['account_images'] : 'assets/account_images/default_images_account.jpg';
//$account_images = isset($_SESSION['account_images']) ? $_SESSION['account_images'] : 'assets/account_images/default_images_account.jpg';
//$account_images = !empty($user['account_images']) ? 'assets/account_images/' . $user['account_images'] : 'assets/account_images/default_images_account.jpg';

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
        if (strpos($user['account_images'], 'https://') === 0) {
            $account_images = !empty($user['account_images']) ? $user['account_images'] : 'assets/account_images/default_images_account.jpg';
        } else {
            $account_images = !empty($user['account_images']) ? 'assets/account_images/' . $user['account_images'] . '?' . time() : 'assets/account_images/default_images_account.jpg';
        }
    } else {
        // ถ้าล็อกอินแบบปกติ ให้ใช้ภาพจากโฟลเดอร์ assets/account_images/
        $account_images = !empty($user['account_images']) ? 'assets/account_images/' . $user['account_images'] . '?' . time() : 'assets/account_images/default_images_account.jpg';
    }
} else {
    $account_images = isset($_SESSION['account_images']) ? $_SESSION['account_images'] : 'assets/account_images/default_images_account.jpg';
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

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== BOX ICONS ==========-->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <!--========== CSS ==========-->
    <link rel="stylesheet" href="assets/css/styles (1).css">



    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">


    <title>แก้ไขโปรไฟล์</title>
    <style>
    /* Styles here... */

    .form-container {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 500px;
    }

    .form-container label {
        margin-top: 10px;
        font-weight: bold;
    }

    .form-container input,
    .form-container select {
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .open-button {
        background-color: #4CAF50;
        /* Background color */
        color: white;
        /* Text color */
        padding: 10px 20px;
        /* Button size */
        border: none;
        /* No border */
        border-radius: 5px;
        /* Rounded corners */
        cursor: pointer;
        /* Pointer cursor */
        transition: background-color 0.3s;
        /* Smooth transition */
    }

    .open-button:hover {
        background-color: #45a049;
        /* Darker shade on hover */
    }

    /*กรอกตำแหน่ง*/

    .container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 400px;

        display: auto;
        grid-template-rows: auto;
        grid-template-columns: auto;
    }

    .menu__container {
        display: flex;
        justify-content: center;
        /* จัดให้อยู่กลางในแนวนอน */
        align-items: center;
        /* จัดให้อยู่กลางในแนวตั้ง */
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    .form-group .no-info {
        color: #999;
        font-size: 14px;
    }

    .form-group .expandable {
        cursor: pointer;
        color: #007bff;
        text-decoration: none;
    }

    .form-group .expandable:after {
        content: " +";
    }

    .form-group.expanded .expandable:after {
        content: " -";
    }

    .form-group .options {
        display: none;
        margin-top: 10px;
    }

    .form-group.expanded .options {
        display: block;
    }

    .options input[type="radio"] {
        margin-right: 10px;
    }

    .submit-button {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
    }

    .submit-button:hover {
        background-color: #0056b3;
    }

    .container {
        border: 2px solid red;
        /* สีแดงเป็นเพียงตัวอย่าง */
    }

    .l-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background-color: white;
        width: 100%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    body {
        margin: 0;
        font-family: 'Prompt', sans-serif;
    }

    .nav__menu {
        display: flex;
        align-items: center;
    }

    .nav__list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
    }

    .nav__item {
        margin: 0 15px;
    }

    .nav__link {
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }

    .nav__link:hover {
        color: #007bff;
    }

    .nav__profile {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .nav__toggle {
        display: none;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .nav__toggle {
            display: block;
        }

        .nav__menu {
            display: none;
        }

        .nav__menu.show {
            display: block;
            position: absolute;
            top: 60px;
            right: 0;
            background-color: white;
            width: 100%;
            text-align: right;
        }

        .nav__item {
            margin: 15px 0;
        }
    }

    /* เพิ่มใหม่หลังจากทำแล้ว */
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
        margin-bottom: -100px;
        /* ลดระยะ margin หรือเนื้อหากับ headder*/
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
        width: 300%;
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

    .menu__contentb {
        display: flex;
        align-items: center;
        justify-content: center;
        /* จัดให้อยู่ตรงกลางแนวนอน */
        margin-left: 0;
        /* ลบค่า margin-left ที่ทำให้เบี่ยงซ้าย */
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
        width: 100%;

    }

    .menu__contentb:hover {
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        /* เพิ่มเงาเมื่อ hover */
    }

    .profile-card {
        width: 300px;
        /* ความกว้างของกรอบ */
        /*max-width: 200%;
     ขนาดสูงสุด */

        margin-top: 20px;
        /* ระยะห่างทางบน */
        padding: 20px;
        /* ช่องว่างภายใน */
        /*border: 1px solid #ccc;  กรอบ */
        border-radius: 10px;
        /* มุมโค้ง */
        background-color: #f9f9f9;
        /* สีพื้นหลัง */

        text-align: center;
        /* จัดข้อความและองค์ประกอบภายในให้อยู่ตรงกลาง */
        border-radius: 10px;
        justify-content: center;
        /* จัดกรอบให้อยู่ตรงกลางในแนวนอน */
    }
    </style>

    <!-- CSS for Popup -->
    <style>
    .form-popup {
        /*display: none;*/
        position: fixed;
        left: 50%;
        /* ปรับให้อยู่ตรงกลางหน้าจอ */
        top: 55%;
        transform: translate(-50%, -50%);
        z-index: 9;
        background-color: rgba(255, 255, 255, 0.001);
        /* ทำให้โปร่งใสมากขึ้น */
        padding: 20px;

        /*box-shadow: none;  ทำให้กรอบเงาหายไป */
        border-radius: 10px;
        width: 50%;
        /* ปรับขนาดความกว้างของ popup */
        max-width: 600px;
        /* กำหนดความกว้างสูงสุด */

        display: flex;
        flex-direction: column;
        /* ปรับให้เนื้อหาเรียงในแนวตั้ง */
        justify-content: center;
        /* จัดให้อยู่ตรงกลางในแนวนอน */
        align-items: center;
        /* จัดให้อยู่ตรงกลางในแนวตั้ง */
    }

    .form-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        /* เพิ่มช่องว่างระหว่างฟอร์ม */
        justify-content: center;
        /* จัดกรอบฟอร์มให้อยู่ตรงกลาง */
    }

    .form-container h3 {
        width: 100%;
        /* ให้หัวข้ออยู่เต็มบรรทัด */
        text-align: center;
        /* จัดให้อยู่ตรงกลาง */
        margin-bottom: 15px;
    }

    .form-container .input-group {
        display: flex;
        flex-direction: column;
        width: calc(50% - 20px);
        /* ให้แต่ละกล่องครึ่งหนึ่งของพื้นที่ */
    }

    .form-container .input-group.full-width {
        width: 100%;
        /* ให้กล่องนี้เต็มความกว้าง */
    }

    .form-container input,
    .form-container select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: none;
        background: #f1f1f1;
    }

    .form-container .btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom: 10px;
        opacity: 0.8;
        transition: opacity 0.3s;
    }

    .form-container .btn:hover {
        opacity: 1;
    }

    .form-container .cancel {
        background-color: red;
    }

    .open-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .open-button:hover {
        background-color: #45a049;
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


    <main class="l-main">
        <!--========== HOME ==========-->

        <section class="home" id="home">
            <style>
            body {
                background-image: url('assets/account_images/HP.jpg');
                background-repeat: no-repeat;
                background-size: contain;
                /* ปรับขนาดภาพให้พอดีกับขอบหน้าจอ */
            }
            </style>
            <div class="home__container bd-container bd-grid">
                <div class="contact__data">
                    <span class="section-subtitle contact__initial">กรอกตำแหน่งงานกันเถอะ!!</span>
                    <h2 class="section-title contact__initial" style="color: whitesmoke;">เกี่ยวกับตำแหน่งงานต่อไปของคุณ
                    </h2>

                </div>


            </div>
        </section>



        <!--========== JOBS ==========-->
        <section class="menu section bd-container" id="menu">
            <span class="section-subtitle">กรอกตำแหน่งงานกันเถอะ!!</span>
            <h2 class="section-title">อัพเดตข้อมูลเกี่ยวกับตำแหน่งงานของคุณ</h2>

            <div class=" ">

                <form action="update_profile1.php" method="post" class="menu__container" enctype="multipart/form-data">
                    <div class="menu__content" style="">
                        <h2>เกี่ยวกับตำแหน่งงานของคุณ</h2>
                        <div class="form-group">
                            <label for="start_date">วันที่สามารถเริ่มงานได้</label>
                            <span id="start_date_info"
                                style="color: #666;"><?php echo htmlspecialchars($start_date ?? 'ไม่ระบุ'); ?></span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select id="start_date" name="start_date">
                                    <option value="">เลือกวันที่เริ่มงาน</option>
                                    <option value="ทันที">ทันที</option>
                                    <option value="2 สัปดาห์">2 สัปดาห์</option>
                                    <option value="4 สัปดาห์">4 สัปดาห์</option>
                                    <option value="8 สัปดาห์">8 สัปดาห์</option>
                                    <option value="12+ สัปดาห์">12+ สัปดาห์</option>
                                </select>
                            </div>
                        </div>




                        <div class="form-group">
                            <label for="employment_type">ประเภทการจ้างงานที่ต้องการ</label>
                            <span id="employment_type_info" style="color: #666;">
                                <?php 
        // แสดงประเภทการจ้างงานที่เลือก ถ้าไม่ได้เลือกให้แสดง 'ไม่ระบุ'
        if (isset($employment_type)) {
            echo $employment_type == 1 ? 'งานประจำ' : ($employment_type == 2 ? 'งานพาร์ทไทม์' : 'ไม่ระบุ');
        } else {
            echo 'ไม่ระบุ';
        }
        ?>
                            </span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select id="employment_type" name="employment_type">
                                    <option value="">เลือกประเภทการจ้างงาน</option>
                                    <option value="1">งานประจำ</option>
                                    <option value="2">งานพาร์ทไทม์</option>
                                </select>
                            </div>
                        </div>






                        <div class="form-group">
                            <label for="preferred_location">จังหวัดที่ต้องการทำงาน</label>
                            <span id="preferred_location_info" style="color: #666;">
                                <?php 
        // แสดงชื่อจังหวัดที่ถูกเลือก
        switch ($preferred_location ?? '') {
            case '1':
                echo 'กรุงเทพมหานคร';
                break;
            case '2':
                echo 'สมุทรปราการ';
                break;
            case '3':
                echo 'นนทบุรี';
                break;
            case '4':
                echo 'ปทุมธานี';
                break;
            case '5':
                echo 'พระนครศรีอยุธยา';
                break;
            case '6':
                echo 'อ่างทอง';
                break;
            case '7':
                echo 'ลพบุรี';
                break;
            case '8':
                echo 'สิงห์บุรี';
                break;
            case '9':
                echo 'ชัยนาท';
                break;
            case '10':
                echo 'สระบุรี';
                break;
            case '11':
                echo 'ชลบุรี';
                break;
            case '12':
                echo 'ระยอง';
                break;
            case '13':
                echo 'จันทบุรี';
                break;
            case '14':
                echo 'ตราด';
                break;
            case '15':
                echo 'ฉะเชิงเทรา';
                break;
            case '16':
                echo 'ปราจีนบุรี';
                break;
            case '17':
                echo 'นครนายก';
                break;
            case '18':
                echo 'สระแก้ว';
                break;
            case '19':
                echo 'นครราชสีมา';
                break;
            case '20':
                echo 'บุรีรัมย์';
                break;
            case '21':
                echo 'สุรินทร์';
                break;
            case '22':
                echo 'ศรีสะเกษ';
                break;
            case '23':
                echo 'อุบลราชธานี';
                break;
            case '24':
                echo 'ยโสธร';
                break;
            case '25':
                echo 'ชัยภูมิ';
                break;
            case '26':
                echo 'อำนาจเจริญ';
                break;
            case '27':
                echo 'หนองบัวลำภู';
                break;
            case '28':
                echo 'ขอนแก่น';
                break;
            case '29':
                echo 'อุดรธานี';
                break;
            case '30':
                echo 'เลย';
                break;
            case '31':
                echo 'หนองคาย';
                break;
            case '32':
                echo 'มหาสารคาม';
                break;
            case '33':
                echo 'ร้อยเอ็ด';
                break;
            case '34':
                echo 'กาฬสินธุ์';
                break;
            case '35':
                echo 'สกลนคร';
                break;
            case '36':
                echo 'นครพนม';
                break;
            case '37':
                echo 'มุกดาหาร';
                break;
            case '38':
                echo 'เชียงใหม่';
                break;
            case '39':
                echo 'ลำพูน';
                break;
            case '40':
                echo 'ลำปาง';
                break;
            case '41':
                echo 'อุตรดิตถ์';
                break;
            case '42':
                echo 'แพร่';
                break;
            case '43':
                echo 'น่าน';
                break;
            case '44':
                echo 'พะเยา';
                break;
            case '45':
                echo 'เชียงราย';
                break;
            case '46':
                echo 'แม่ฮ่องสอน';
                break;
            case '47':
                echo 'นครสวรรค์';
                break;
            case '48':
                echo 'อุทัยธานี';
                break;
            case '49':
                echo 'กำแพงเพชร';
                break;
            case '50':
                echo 'ตาก';
                break;
            case '51':
                echo 'สุโขทัย';
                break;
            case '52':
                echo 'พิษณุโลก';
                break;
            case '53':
                echo 'พิจิตร';
                break;
            case '54':
                echo 'เพชรบูรณ์';
                break;
            case '55':
                echo 'ราชบุรี';
                break;
            case '56':
                echo 'กาญจนบุรี';
                break;
            case '57':
                echo 'สุพรรณบุรี';
                break;
            case '58':
                echo 'นครปฐม';
                break;
            case '59':
                echo 'สมุทรสาคร';
                break;
            case '60':
                echo 'สมุทรสงคราม';
                break;
            case '61':
                echo 'เพชรบุรี';
                break;
            case '62':
                echo 'ประจวบคีรีขันธ์';
                break;
            case '63':
                echo 'นครศรีธรรมราช';
                break;
            case '64':
                echo 'กระบี่';
                break;
            case '65':
                echo 'พังงา';
                break;
            case '66':
                echo 'ภูเก็ต';
                break;
            case '67':
                echo 'สุราษฎร์ธานี';
                break;
            case '68':
                echo 'ระนอง';
                break;
            case '69':
                echo 'ชุมพร';
                break;
            case '70':
                echo 'สงขลา';
                break;
            case '71':
                echo 'สตูล';
                break;
            case '72':
                echo 'ตรัง';
                break;
            case '73':
                echo 'พัทลุง';
                break;
            case '74':
                echo 'ปัตตานี';
                break;
            case '75':
                echo 'ยะลา';
                break;
            case '76':
                echo 'นราธิวาส';
                break;
            case '77':
                echo 'บึงกาฬ';
                break;
            default:
                echo 'ไม่ระบุ';
        }
        ?>
                            </span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select id="preferred_location" name="preferred_location">
                                    <option value="">เลือกจังหวัด</option>
                                    <option value="1">กรุงเทพมหานคร</option>
                                    <option value="2">สมุทรปราการ</option>
                                    <option value="3">นนทบุรี</option>
                                    <option value="4">ปทุมธานี</option>
                                    <option value="5">พระนครศรีอยุธยา</option>
                                    <option value="6">อ่างทอง</option>
                                    <option value="7">ลพบุรี</option>
                                    <option value="8">สิงห์บุรี</option>
                                    <option value="9">ชัยนาท</option>
                                    <option value="10">สระบุรี</option>
                                    <option value="11">ชลบุรี</option>
                                    <option value="12">ระยอง</option>
                                    <option value="13">จันทบุรี</option>
                                    <option value="14">ตราด</option>
                                    <option value="15">ฉะเชิงเทรา</option>
                                    <option value="16">ปราจีนบุรี</option>
                                    <option value="17">นครนายก</option>
                                    <option value="18">สระแก้ว</option>
                                    <option value="19">นครราชสีมา</option>
                                    <option value="20">บุรีรัมย์</option>
                                    <option value="21">สุรินทร์</option>
                                    <option value="22">ศรีสะเกษ</option>
                                    <option value="23">อุบลราชธานี</option>
                                    <option value="24">ยโสธร</option>
                                    <option value="25">ชัยภูมิ</option>
                                    <option value="26">อำนาจเจริญ</option>
                                    <option value="27">หนองบัวลำภู</option>
                                    <option value="28">ขอนแก่น</option>
                                    <option value="29">อุดรธานี</option>
                                    <option value="30">เลย</option>
                                    <option value="31">หนองคาย</option>
                                    <option value="32">มหาสารคาม</option>
                                    <option value="33">ร้อยเอ็ด</option>
                                    <option value="34">กาฬสินธุ์</option>
                                    <option value="35">สกลนคร</option>
                                    <option value="36">นครพนม</option>
                                    <option value="37">มุกดาหาร</option>
                                    <option value="38">เชียงใหม่</option>
                                    <option value="39">ลำพูน</option>
                                    <option value="40">ลำปาง</option>
                                    <option value="41">อุตรดิตถ์</option>
                                    <option value="42">แพร่</option>
                                    <option value="43">น่าน</option>
                                    <option value="44">พะเยา</option>
                                    <option value="45">เชียงราย</option>
                                    <option value="46">แม่ฮ่องสอน</option>
                                    <option value="47">นครสวรรค์</option>
                                    <option value="48">อุทัยธานี</option>
                                    <option value="49">กำแพงเพชร</option>
                                    <option value="50">ตาก</option>
                                    <option value="51">สุโขทัย</option>
                                    <option value="52">พิษณุโลก</option>
                                    <option value="53">พิจิตร</option>
                                    <option value="54">เพชรบูรณ์</option>
                                    <option value="55">ราชบุรี</option>
                                    <option value="56">กาญจนบุรี</option>
                                    <option value="57">สุพรรณบุรี</option>
                                    <option value="58">นครปฐม</option>
                                    <option value="59">สมุทรสาคร</option>
                                    <option value="60">สมุทรสงคราม</option>
                                    <option value="61">เพชรบุรี</option>
                                    <option value="62">ประจวบคีรีขันธ์</option>
                                    <option value="63">นครศรีธรรมราช</option>
                                    <option value="64">กระบี่</option>
                                    <option value="65">พังงา</option>
                                    <option value="66">ภูเก็ต</option>
                                    <option value="67">สุราษฎร์ธานี</option>
                                    <option value="68">ระนอง</option>
                                    <option value="69">ชุมพร</option>
                                    <option value="70">สงขลา</option>
                                    <option value="71">สตูล</option>
                                    <option value="72">ตรัง</option>
                                    <option value="73">พัทลุง</option>
                                    <option value="74">ปัตตานี</option>
                                    <option value="75">ยะลา</option>
                                    <option value="76">นราธิวาส</option>
                                    <option value="77">บึงกาฬ</option>
                                </select>
                            </div>
                        </div>






                        <div class="form-group">
                            <label for="work_eligibility">สิทธิการทำงานที่ถูกต้องตามกฎหมาย</label>
                            <span class="no-info">ไทย : </span>
                            <span id="work_eligibility_display" style="color: #666;">
                                <?php
        echo htmlspecialchars($work_eligibility ?? 'ไม่ระบุ');
        if ($work_eligibility === 'other') {
            echo htmlspecialchars($other_work_eligibility ?? '');
        }
        ?>
                            </span>

                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select id="work_eligibility" name="work_eligibility" onchange="toggleOtherInput(this)">
                                    <option value="">เลือกสิทธิการทำงาน</option>
                                    <option value="พลเมืองไทย/ผู้พำนักถาวร"
                                        <?php echo (isset($work_eligibility) && $work_eligibility == 'พลเมืองไทย/ผู้พำนักถาวร') ? 'selected' : ''; ?>>
                                        พลเมืองไทย/ผู้พำนักถาวร</option>
                                    <option value="วีซ่าชั่วคราวที่มีข้อจำกัดในอุตสาหกรรม (เช่น สมาร์ทวีซ่า)"
                                        <?php echo (isset($work_eligibility) && $work_eligibility == 'วีซ่าชั่วคราวที่มีข้อจำกัดในอุตสาหกรรม (เช่น สมาร์ทวีซ่า)') ? 'selected' : ''; ?>>
                                        วีซ่าชั่วคราวที่มีข้อจำกัดในอุตสาหกรรม (เช่น สมาร์ทวีซ่า)</option>
                                    <option
                                        value="วีซ่าราชการ หรือวีซ่านักการฑูต (เช่น ข้าราชการ วีซ่าประเภทคนอยู่ชั่วคราว F (Non-Immigrant F ))"
                                        <?php echo (isset($work_eligibility) && $work_eligibility == 'วีซ่าราชการ หรือวีซ่านักการฑูต (เช่น ข้าราชการ วีซ่าประเภทคนอยู่ชั่วคราว F (Non-Immigrant F ))') ? 'selected' : ''; ?>>
                                        วีซ่าราชการ หรือวีซ่านักการฑูต (เช่น ข้าราชการ วีซ่าประเภทคนอยู่ชั่วคราว F
                                        (Non-Immigrant F ))</option>
                                    <option
                                        value="ต้องการการสนับสนุนในการทำงานให้กับผู้ประกอบการใหม่ (เช่น Long stay or Tourist Visa)"
                                        <?php echo (isset($work_eligibility) && $work_eligibility == 'ต้องการการสนับสนุนในการทำงานให้กับผู้ประกอบการใหม่ (เช่น Long stay or Tourist Visa)') ? 'selected' : ''; ?>>
                                        ต้องการการสนับสนุนในการทำงานให้กับผู้ประกอบการใหม่ (เช่น Long stay or Tourist
                                        Visa)</option>
                                    <option
                                        value="วีซ่าชั่วคราวที่มีข้อจำกัดตามระยะเวลาพำนัก (เช่น วีซ่านักลงทุน หรือวีซ่าประเภทคนอยู่ชั่วคราว O (Non-Immigrant O))"
                                        <?php echo (isset($work_eligibility) && $work_eligibility == 'วีซ่าชั่วคราวที่มีข้อจำกัดตามระยะเวลาพำนัก (เช่น วีซ่านักลงทุน หรือวีซ่าประเภทคนอยู่ชั่วคราว O (Non-Immigrant O))') ? 'selected' : ''; ?>>
                                        วีซ่าชั่วคราวที่มีข้อจำกัดตามระยะเวลาพำนัก (เช่น วีซ่านักลงทุน
                                        หรือวีซ่าประเภทคนอยู่ชั่วคราว O (Non-Immigrant O))</option>
                                    <option value="other"
                                        <?php echo (isset($work_eligibility) && $work_eligibility == 'other') ? 'selected' : ''; ?>>
                                        อื่นๆ</option>
                                </select>
                                <input type="text" id="other_work_eligibility" name="other_work_eligibility"
                                    placeholder="กรุณาระบุ"
                                    style="display: <?php echo (isset($work_eligibility) && $work_eligibility == 'other') ? 'block' : 'none'; ?>;"
                                    value="<?php echo htmlspecialchars($other_work_eligibility ?? ''); ?>">
                            </div>
                        </div>

                        <script>
                        function toggleOtherInput(selectElement) {
                            const otherInput = document.getElementById('other_work_eligibility');
                            if (selectElement.value === 'other') {
                                otherInput.style.display = 'block';
                            } else {
                                otherInput.style.display = 'none';
                                otherInput.value = ''; // Clear the input if not selected
                            }
                        }
                        </script>






                        <div class="form-group">
                            <label for="expected_salary">เงินเดือนที่คาดหวัง</label>
                            <span id="expected_salary"
                                style="color: #666;"><?php echo htmlspecialchars($expected_salary ?? 'ไม่ระบุ'); ?></span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <input type="text" id="expected_salary" name="expected_salary"
                                    placeholder="กรุณาใส่เงินเดือนที่คาดหวัง">
                                <select id="salary_type" name="salary_type">
                                    <option value="รายเดือน">รายเดือน</option>
                                    <option value="รายชั่วโมง">รายชั่วโมง</option>
                                    <option value="รายปี">รายปี</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="interested_job_type">ประเภทงานที่สนใจ</label>
                            <span id="interested_job_type"
                                style="color: #666;"><?php echo htmlspecialchars($interested_job_type ?? 'ไม่ระบุ'); ?></span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <label for="interested_job_type">ประเภทงาน</label>
                                <select id="interested_job_type" name="interested_job_type">
                                    <option value="ภาษาและมนุษยศาสตร์">ภาษาและมนุษยศาสตร์</option>
                                    <option value="สังคมศาสตร์และจิตวิทยา">สังคมศาสตร์และจิตวิทยา</option>
                                    <option value="วิทยาศาสตร์พื้นฐานและธรรมชาติ">วิทยาศาสตร์พื้นฐานและธรรมชาติ</option>
                                    <option value="วิศวกรรมศาสตร์">วิศวกรรมศาสตร์</option>
                                    <option value="แพทยศาสตร์และสาธารณสุข">แพทยศาสตร์และสาธารณสุข</option>
                                    <option value="การบริหารและการเงิน">การบริหารและการเงิน</option>
                                    <option value="การศึกษาและพัฒนาหลักสูตร">การศึกษาและพัฒนาหลักสูตร</option>
                                    <option value="ศิลปกรรมและการออกแบบ">ศิลปกรรมและการออกแบบ</option>
                                    <option value="สื่อสารมวลชนและประชาสัมพันธ์">สื่อสารมวลชนและประชาสัมพันธ์</option>
                                    <option value="วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ">
                                        วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ</option>
                                </select>

                            </div>
                        </div>


                        <div class="form-group">
                            <label for="conscription_status">ผ่านเกณฑ์ทหารแล้วหรือยัง</label>
                            <span id="conscription_status"
                                style="color: #666;"><?php echo htmlspecialchars($conscription ?? 'ไม่ระบุ'); ?></span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select id="conscription_status" name="conscription">
                                    <option value="">เลือกสถานะ</option>
                                    <option value="ผ่านเกณฑ์แล้ว"
                                        <?php echo ($conscription === 'ผ่านเกณฑ์แล้ว') ? 'selected' : ''; ?>>
                                        ผ่านเกณฑ์แล้ว</option>
                                    <option value="ยังไม่ผ่าน"
                                        <?php echo ($conscription === 'ยังไม่ผ่าน') ? 'selected' : ''; ?>>ยังไม่ผ่าน
                                    </option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="work_type">ประเภทการทำงานที่สนใจ</label>
                            <span id="work_type"
                                style="color: #666;"><?php echo htmlspecialchars($work_type ?? 'ไม่ระบุ'); ?></span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select id="work_type" name="work_type">
                                    <option value="">เลือกประเภทการทำงาน</option>
                                    <option value="Online">Online</option>
                                    <option value="Onsite">Onsite</option>
                                    <option value="Onsite">Online/Onsite</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="old">อายุ</label>
                            <span id="old" style="color: #666;"><?php echo htmlspecialchars($old ?? 'ไม่ระบุ'); ?>
                                ปี</span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">

                                <input type="text" id="old" name="old" placeholder="กรุณาใส่อายุ">

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sex">เพศ</label>
                            <span id="sex_display" style="color: #666;">
                                <?php 
        // แสดงเพศตามค่าที่ได้มา
        switch ($sex) {
            case 0:
                echo "ชาย";
                break;
            case 1:
                echo "หญิง";
                break;
            case 2:
                echo "อื่น ๆ";
                break;
            default:
                echo "ไม่ระบุ";
                break;
        }
        ?>
                            </span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select id="sex" name="sex">
                                    <option value="">เลือกเพศ</option>
                                    <option value="0" <?php echo (isset($sex) && $sex == 0) ? 'selected' : ''; ?>>ชาย
                                    </option>
                                    <option value="1" <?php echo (isset($sex) && $sex == 1) ? 'selected' : ''; ?>>หญิง
                                    </option>
                                    <option value="2" <?php echo (isset($sex) && $sex == 2) ? 'selected' : ''; ?>>อื่น ๆ
                                    </option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="qualification">วุฒิการศึกษา</label>
                            <span id="qualification_display" style="color: #666;">
                                <?php 
        // แสดงวุฒิการศึกษาตามค่าที่ได้มา
        switch ($qualification) {
            case 0:
                echo "ไม่มีการศึกษา";
                break;
            case 1:
                echo "ประถมศึกษา";
                break;
            case 2:
                echo "มัธยมศึกษาตอนต้น";
                break;
            case 3:
                echo "มัธยมศึกษาตอนปลายหรือเทียบเท่า";
                break;
            case 4:
                echo "อนุปริญญา";
                break;
            case 5:
                echo "ปริญญาตรีขึ้นไปหรือเทียบเท่า";
                break;
            default:
                echo "ไม่ระบุ";
                break;
        }
        ?>
                            </span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select id="qualification" name="qualification">
                                    <option value="">เลือกประเภทวุฒิ</option>
                                    <option value="0"
                                        <?php echo (isset($qualification) && $qualification == 0) ? 'selected' : ''; ?>>
                                        ไม่มีการศึกษา</option>
                                    <option value="1"
                                        <?php echo (isset($qualification) && $qualification == 1) ? 'selected' : ''; ?>>
                                        ประถมศึกษา</option>
                                    <option value="2"
                                        <?php echo (isset($qualification) && $qualification == 2) ? 'selected' : ''; ?>>
                                        มัธยมศึกษาตอนต้น</option>
                                    <option value="3"
                                        <?php echo (isset($qualification) && $qualification == 3) ? 'selected' : ''; ?>>
                                        มัธยมศึกษาตอนปลายหรือเทียบเท่า</option>
                                    <option value="4"
                                        <?php echo (isset($qualification) && $qualification == 4) ? 'selected' : ''; ?>>
                                        อนุปริญญา</option>
                                    <option value="5"
                                        <?php echo (isset($qualification) && $qualification == 5) ? 'selected' : ''; ?>>
                                        ปริญญาตรีขึ้นไปหรือเทียบเท่า</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="course">สาขาจบ</label>
                            <span id="course_display" style="color: #666;">
                                <?php 
        // แสดงสาขาตามค่าที่ได้มา
        switch ($course) {
            case 0:
                echo "ภาษาไทย";
                break;
            case 1:
                echo "ภาษาอังกฤษ";
                break;
            case 2:
                echo "ภาษาต่างประเทศ";
                break;
            case 3:
                echo "มนุษยศาสตร์";
                break;
            case 4:
                echo "สังคมศาสตร์";
                break;
            case 5:
                echo "จิตวิทยา";
                break;
            case 6:
                echo "คณิตศาสตร์";
                break;
            case 7:
                echo "ฟิสิกส์";
                break;
            case 8:
                echo "เคมี";
                break;
            case 9:
                echo "ชีววิทยา";
                break;
            case 10:
                echo "วิทยาศาสตร์สิ่งแวดล้อม";
                break;
            case 11:
                echo "วิศวกรรมไฟฟ้า";
                break;
            case 12:
                echo "วิศวกรรมเครื่องกล";
                break;
            case 13:
                echo "วิศวกรรมโยธา";
                break;
            case 14:
                echo "วิศวกรรมสารสนเทศ";
                break;
            case 15:
                echo "วิศวกรรมการบิน";
                break;
            case 16:
                echo "แพทยศาสตร์";
                break;
            case 17:
                echo "ทันตแพทยศาสตร์";
                break;
            case 18:
                echo "เภสัชศาสตร์";
                break;
            case 19:
                echo "สาธารณสุขศาสตร์";
                break;
            case 20:
                echo "การพยาบาล";
                break;
            case 21:
                echo "การจัดการ";
                break;
            case 22:
                echo "การตลาด";
                break;
            case 23:
                echo "การเงิน";
                break;
            case 24:
                echo "บัญชี";
                break;
            case 25:
                echo "เศรษฐศาสตร์";
                break;
            case 26:
                echo "การศึกษา";
                break;
            case 27:
                echo "จิตวิทยาการศึกษา";
                break;
            case 28:
                echo "การพัฒนาหลักสูตร";
                break;
            case 29:
                echo "ศิลปกรรม";
                break;
            case 30:
                echo "การออกแบบผลิตภัณฑ์";
                break;
            case 31:
                echo "การออกแบบกราฟิก";
                break;
            case 32:
                echo "สถาปัตยกรรม";
                break;
            case 33:
                echo "สื่อสารมวลชน";
                break;
            case 34:
                echo "การโฆษณา";
                break;
            case 35:
                echo "การประชาสัมพันธ์";
                break;
            case 36:
                echo "วิทยาการคอมพิวเตอร์";
                break;
            case 37:
                echo "เทคโนโลยีสารสนเทศ";
                break;
            case 38:
                echo "ความมั่นคงไซเบอร์";
                break;
            default:
                echo "ไม่ระบุ";
                break;
        }
        ?>
                            </span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">
                                <select name="course" id="box" style="font-size: 17px;" required>
                                    <!-- ตัวเลือกสาขา -->
                                    <option value="0" <?php echo (isset($course) && $course == 0) ? 'selected' : ''; ?>>
                                        ภาษาไทย</option>
                                    <option value="1" <?php echo (isset($course) && $course == 1) ? 'selected' : ''; ?>>
                                        ภาษาอังกฤษ</option>
                                    <option value="2" <?php echo (isset($course) && $course == 2) ? 'selected' : ''; ?>>
                                        ภาษาต่างประเทศ</option>
                                    <option value="3" <?php echo (isset($course) && $course == 3) ? 'selected' : ''; ?>>
                                        มนุษยศาสตร์</option>
                                    <option value="4" <?php echo (isset($course) && $course == 4) ? 'selected' : ''; ?>>
                                        สังคมศาสตร์</option>
                                    <option value="5" <?php echo (isset($course) && $course == 5) ? 'selected' : ''; ?>>
                                        จิตวิทยา</option>
                                    <option value="6" <?php echo (isset($course) && $course == 6) ? 'selected' : ''; ?>>
                                        คณิตศาสตร์</option>
                                    <option value="7" <?php echo (isset($course) && $course == 7) ? 'selected' : ''; ?>>
                                        ฟิสิกส์</option>
                                    <option value="8" <?php echo (isset($course) && $course == 8) ? 'selected' : ''; ?>>
                                        เคมี</option>
                                    <option value="9" <?php echo (isset($course) && $course == 9) ? 'selected' : ''; ?>>
                                        ชีววิทยา</option>
                                    <option value="10"
                                        <?php echo (isset($course) && $course == 10) ? 'selected' : ''; ?>>
                                        วิทยาศาสตร์สิ่งแวดล้อม</option>
                                    <option value="11"
                                        <?php echo (isset($course) && $course == 11) ? 'selected' : ''; ?>>วิศวกรรมไฟฟ้า
                                    </option>
                                    <option value="12"
                                        <?php echo (isset($course) && $course == 12) ? 'selected' : ''; ?>>
                                        วิศวกรรมเครื่องกล</option>
                                    <option value="13"
                                        <?php echo (isset($course) && $course == 13) ? 'selected' : ''; ?>>วิศวกรรมโยธา
                                    </option>
                                    <option value="14"
                                        <?php echo (isset($course) && $course == 14) ? 'selected' : ''; ?>>
                                        วิศวกรรมสารสนเทศ</option>
                                    <option value="15"
                                        <?php echo (isset($course) && $course == 15) ? 'selected' : ''; ?>>
                                        วิศวกรรมการบิน</option>
                                    <option value="16"
                                        <?php echo (isset($course) && $course == 16) ? 'selected' : ''; ?>>แพทยศาสตร์
                                    </option>
                                    <option value="17"
                                        <?php echo (isset($course) && $course == 17) ? 'selected' : ''; ?>>
                                        ทันตแพทยศาสตร์</option>
                                    <option value="18"
                                        <?php echo (isset($course) && $course == 18) ? 'selected' : ''; ?>>เภสัชศาสตร์
                                    </option>
                                    <option value="19"
                                        <?php echo (isset($course) && $course == 19) ? 'selected' : ''; ?>>
                                        สาธารณสุขศาสตร์</option>
                                    <option value="20"
                                        <?php echo (isset($course) && $course == 20) ? 'selected' : ''; ?>>การพยาบาล
                                    </option>
                                    <option value="21"
                                        <?php echo (isset($course) && $course == 21) ? 'selected' : ''; ?>>การจัดการ
                                    </option>
                                    <option value="22"
                                        <?php echo (isset($course) && $course == 22) ? 'selected' : ''; ?>>การตลาด
                                    </option>
                                    <option value="23"
                                        <?php echo (isset($course) && $course == 23) ? 'selected' : ''; ?>>การเงิน
                                    </option>
                                    <option value="24"
                                        <?php echo (isset($course) && $course == 24) ? 'selected' : ''; ?>>บัญชี
                                    </option>
                                    <option value="25"
                                        <?php echo (isset($course) && $course == 25) ? 'selected' : ''; ?>>เศรษฐศาสตร์
                                    </option>
                                    <option value="26"
                                        <?php echo (isset($course) && $course == 26) ? 'selected' : ''; ?>>การศึกษา
                                    </option>
                                    <option value="27"
                                        <?php echo (isset($course) && $course == 27) ? 'selected' : ''; ?>>
                                        จิตวิทยาการศึกษา</option>
                                    <option value="28"
                                        <?php echo (isset($course) && $course == 28) ? 'selected' : ''; ?>>
                                        การพัฒนาหลักสูตร</option>
                                    <option value="29"
                                        <?php echo (isset($course) && $course == 29) ? 'selected' : ''; ?>>ศิลปกรรม
                                    </option>
                                    <option value="30"
                                        <?php echo (isset($course) && $course == 30) ? 'selected' : ''; ?>>
                                        การออกแบบผลิตภัณฑ์</option>
                                    <option value="31"
                                        <?php echo (isset($course) && $course == 31) ? 'selected' : ''; ?>>
                                        การออกแบบกราฟิก</option>
                                    <option value="32"
                                        <?php echo (isset($course) && $course == 32) ? 'selected' : ''; ?>>สถาปัตยกรรม
                                    </option>
                                    <option value="33"
                                        <?php echo (isset($course) && $course == 33) ? 'selected' : ''; ?>>สื่อสารมวลชน
                                    </option>
                                    <option value="34"
                                        <?php echo (isset($course) && $course == 34) ? 'selected' : ''; ?>>การโฆษณา
                                    </option>
                                    <option value="35"
                                        <?php echo (isset($course) && $course == 35) ? 'selected' : ''; ?>>
                                        การประชาสัมพันธ์</option>
                                    <option value="36"
                                        <?php echo (isset($course) && $course == 36) ? 'selected' : ''; ?>>
                                        วิทยาการคอมพิวเตอร์</option>
                                    <option value="37"
                                        <?php echo (isset($course) && $course == 37) ? 'selected' : ''; ?>>
                                        เทคโนโลยีสารสนเทศ</option>
                                    <option value="38"
                                        <?php echo (isset($course) && $course == 38) ? 'selected' : ''; ?>>
                                        ความมั่นคงไซเบอร์</option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group">
                            <label for="experience">ประสบการณ์ทำงาน</label>
                            <span id="experience"
                                style="color: #666;"><?php echo htmlspecialchars($experience ?? 'ไม่ระบุ'); ?> ปี</span>
                            <a class="expandable" href="javascript:void(0);"
                                onclick="toggleExpand(this)">คลิกเพื่อกรอกข้อมูล</a>
                            <div class="input-container" style="display: none;">

                                <input type="text" id="experience" name="experience"
                                    placeholder="กรุณาใส่ประสบการณ์ทำงาน">

                            </div>
                        </div>


                        <button type="submit" class="btn">บันทึกข้อมูล</button>
                        <!--button type="button" class="btn cancel" onclick="closeForm()">ยกเลิก</button-->
                </form>


            </div>
        </section>



        <!--========== CONTACT US ==========-->
        <section class="contact section bd-container" id="contact">
            <div class="contact__container bd-grid">
                <div class="contact__data">
                    <span class="section-subtitle contact__initial">อัพเดทโปรไฟล์กันเถอะ!!</span>
                    <h2 class="section-title contact__initial">โปรไฟล์</h2>
                </div>

                <div class="menu__contentb" style="margin-left: -100px; ">
                    <center>
                        <div class="profile-card">
                            <img src="<?php echo htmlspecialchars($account_images); ?>" alt="Profile Image" width="100"
                                height="100"
                                style="width: 65px; height: 65px; border-radius: 50%; object-fit: cover;"><br><br>

                            <label for="full-name">ชื่อ ID:</label>
                            <span id="full-name"><?php echo htmlspecialchars($fname); ?></span><br>

                            <label for="full-name">ชื่อ:</label>
                            <span id="full-name"><?php echo htmlspecialchars($fname); ?></span><br>

                            <label for="last-name">นามสกุล:</label>
                            <span id="last-name"><?php echo htmlspecialchars($lname); ?></span><br>

                            <label for="birthday">วันเกิด:</label>
                            <span id="birthday"><?php echo htmlspecialchars($birthday); ?></span><br>

                            <label for="gender">เพศ:</label>
                            <span id="gender"><?php echo htmlspecialchars($gender); ?></span><br>

                            <label for="addresses">ที่อยู่:</label>
                            <span id="addresses"><?php echo htmlspecialchars($addresses); ?></span><br>

                            <label for="account_email">อีเมล:</label>
                            <span id="account_email"><?php echo htmlspecialchars($email); ?></span><br>

                            <label for="phone">หมายเลขโทรศัพท์:</label>
                            <span id="phone"><?php echo htmlspecialchars($phonenumbers); ?></span><br>
                        </div>
                        <button class="open-button" onclick="openForm()">แก้ไขข้อมูล</button>
                    </center>
                </div>

                <!-- Popup Form -->
                <div class="form-popup" id="myForm" style="display: none; ">
                    <form action="update_profile.php" method="post" class="form-container" enctype="multipart/form-data"
                        style="justify-content: center;">
                        <h3>แก้ไขโปรไฟล์</h3>

                        <!-- ชื่อและนามสกุลอยู่บรรทัดเดียวกัน -->
                        <div class="input-group">
                            <label for="first-name">ชื่อ:</label>
                            <input type="text" id="first-name" name="first-name"
                                value="<?php echo htmlspecialchars(explode(' ', $fname)[0]); ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="last-name">นามสกุล:</label>
                            <input type="text" id="last-name" name="last-name"
                                value="<?php echo htmlspecialchars(explode(' ', $lname)[0] ?? ''); ?>" required>
                        </div>

                        <!-- วันเกิดและเพศอยู่บรรทัดเดียวกัน -->
                        <div class="input-group">
                            <label for="birthday">วันเกิด:</label>
                            <input type="date" id="birthday" name="birthday"
                                value="<?php echo htmlspecialchars($birthday); ?>">
                        </div>
                        <div class="input-group">
                            <label for="gender">เพศ:</label>
                            <select id="gender" name="gender" required>
                                <option value="ชาย" <?php echo ($gender == 'ชาย') ? 'selected' : ''; ?>>ชาย</option>
                                <option value="หญิง" <?php echo ($gender == 'หญิง') ? 'selected' : ''; ?>>หญิง</option>
                                <option value="อื่นๆ" <?php echo ($gender == 'อื่นๆ') ? 'selected' : ''; ?>>อื่นๆ
                                </option>
                            </select>
                        </div>

                        <!-- ที่อยู่เต็มความกว้าง -->
                        <div class="input-group full-width">
                            <label for="addresses">ที่อยู่:</label>
                            <input type="text" id="addresses" name="addresses"
                                value="<?php echo htmlspecialchars($addresses); ?>">
                        </div>

                        <!-- อีเมลและหมายเลขโทรศัพท์อยู่บรรทัดเดียวกัน -->
                        <div class="input-group">
                            <label for="account_email">อีเมล:</label>
                            <input type="email" id="account_email" name="account_email"
                                value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="phone">หมายเลขโทรศัพท์:</label>
                            <input type="text" id="phone" name="phone"
                                value="<?php echo htmlspecialchars($phonenumbers); ?>">
                        </div>

                        <!-- อัปโหลดรูปโปรไฟล์เต็มความกว้าง -->
                        <div class="input-group full-width">
                            <label for="account_images">อัปโหลดรูปโปรไฟล์:</label>
                            <input type="file" id="account_images" name="account_images" accept="image/*">
                        </div>

                        <!-- ปุ่มบันทึกและยกเลิก -->
                        <button type="submit" class="btn">บันทึกข้อมูล</button>
                        <button type="button" class="btn cancel" onclick="closeForm()">ยกเลิก</button>
                    </form>
                </div>

            </div>
        </section>



        <!--========== CONTACT US ==========-->
        <section class="contact section bd-container" id="contact">
            <div class="contact__container bd-grid">
                <div class="contact__data">
                    <span class="section-subtitle contact__initial">อัพโหลด RESUME รียัง!!</span>
                    <h2 class="section-title contact__initial">RESUME</h2>
                    <p class="contact__description">หากคุณต้องการหางานอย่าลืมอัพเดท RESUME ด้วยนะ</p>
                </div>

                <!-- เพิ่มในที่ที่ต้องการในหน้า edit_profile.php -->
                <div class="form-container" style="justify-content: center; /* จัดกรอบให้อยู่ตรงกลางในแนวนอน */">
                    <center>
                        <h2>อัพโหลดไฟล์ PDF</h2>
                        <!-- ปุ่มสำหรับไปยังหน้า upload_pdf.php -->
                        <a href="upload_pdf.php?account_id=<?php echo htmlspecialchars($user_id); ?>"
                            class="btn upload-btn">อัปโหลด PDF</a>
                </div>
            </div>
        </section>




    </main>

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
    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }

    function toggleExpand(element) {
        const inputContainer = element.nextElementSibling;
        if (inputContainer.style.display === "none") {
            inputContainer.style.display = "block";
        } else {
            inputContainer.style.display = "none";
        }
    }


    /* ฝาก
    <label for="first-name">ชื่อ:</label>
        <input type="text" id="first-name" name="first-name" value="<!?php echo htmlspecialchars(explode(' ', $name)[0]); ?>" required>
        <label for="last-name">นามสกุล:</label>
        <input type="text" id="last-name" name="last-name" value="<!?php echo htmlspecialchars(explode(' ', $name)[1] ?? ''); ?>" required>
     */
    </script>

    <!--========== SCROLL REVEAL ==========-->
    <script src="https://unpkg.com/scrollreveal"></script>

    <!--========== MAIN JS ==========-->
    <script src="assets/js/main.js"></script>
</body>

</html>