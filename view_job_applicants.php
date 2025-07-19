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
//session_start();
require('connectdb.php');

if (!isset($_GET['job_id'])) {
    echo "ไม่มีข้อมูลงานที่เลือก";
    exit;
}

$job_id = $_GET['job_id'];

// ดึงข้อมูลงาน
$sql_job = "SELECT job_name, company_name FROM job_ad WHERE job_ad_id = ?";
$stmt = $connect->prepare($sql_job);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job_result = $stmt->get_result()->fetch_assoc();

if (!$job_result) {
    echo "ไม่พบข้อมูลงาน";
    exit;
}

// ดึงข้อมูลผู้สมัคร
$sql_applicants = "SELECT ja.application_id, ja.application_date, ja.Suitability, 
                          ua.account_name, a.resume, a.sex, a.old, a.qualification, a.course, 
                          a.experience, a.start_date, a.employment_type, a.preferred_location, 
                          a.work_eligibility, a.expected_salary, a.salary_type, 
                          a.interested_job_type, a.conscription, a.work_type 
                   FROM job_applications ja 
                   JOIN users_account ua ON ja.account_id = ua.account_id 
                   JOIN applicant a ON ja.account_id = a.account_id 
                   WHERE ja.job_id = ?";
$stmt = $connect->prepare($sql_applicants);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$applicants_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดผู้สมัคร</title>
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
        text-align: center; /* จัดให้อยู่กลางแนวนอน */
        vertical-align: middle; /* จัดให้อยู่กลางแนวตั้ง */
    }

    .callback-btn {
        background-color: cadetblue;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .callback-btn:hover {
        background-color: cornflowerblue;
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

        <section class="job-detail-container">
            <h2>ชื่องาน: <?= htmlspecialchars($job_result['job_name']) ?></h2>
            <h3>บริษัท: <?= htmlspecialchars($job_result['company_name']) ?></h3>
            <h4>จำนวนผู้สมัคร: <?= $applicants_result->num_rows ?></h4>

            <?php if ($applicants_result->num_rows > 0): ?>
            <form method="POST" action="contact_selected_applicants.php">
            <button type="submit" class="action-btn callback-btn">ติดต่อผู้สมัครที่เลือก</button>
            <table>
                <thead>
                    <tr>
                        <th>ชื่อผู้ใช้</th>
                        <th>วันที่สมัคร</th>
                        <th>ความเหมาะสม</th>
                        <th>เพศ</th>
                        <th>อายุ</th>
                        <th>วุฒิการศึกษา</th>
                        <th>สาขาวิชา</th>
                        <th>ประสบการณ์</th>
                        <th>วันที่เริ่มงาน</th>
                        <th>ประเภทการจ้างที่ต้องการ</th>
                        <th>สถานที่ที่ต้องการ</th>
                        <th>เงินเดือนที่ต้องการ</th>
                        <th>งานที่สนใจ</th>
                        <th>ผ่านเกณฑ์ทหาร</th>
                        <th>ติดต่อผู้สมัคร</th>
                        <th>เลือก</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // การศึกษา
$qualifications = [
    0 => 'ไม่มีการศึกษา',
    1 => 'ประถมศึกษา',
    2 => 'มัธยมศึกษาตอนต้น',
    3 => 'มัธยมศึกษาตอนปลายหรือเทียบเท่า',
    4 => 'อนุปริญญา',
    5 => 'ปริญญาตรีขึ้นไปหรือเทียบเท่า'
];

// สาขาวิชา
$courses = [
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
];

// เพศ
$sexs = [
    0 => 'ชาย',
    1 => 'หญิง',
    2 => 'อื่นๆ'
];

// ที่ตั้ง
$preferred_location = [
    1 => 'กรุงเทพมหานคร',
    2 => 'สมุทรปราการ',
    3 => 'นนทบุรี',
    4 => 'ปทุมธานี',
    5 => 'พระนครศรีอยุธยา',
    6 => 'อ่างทอง',
    7 => 'ลพบุรี',
    8 => 'สิงห์บุรี',
    9 => 'ชัยนาท',
    10 => 'สระบุรี',
    11 => 'ชลบุรี',
    12 => 'ระยอง',
    13 => 'จันทบุรี',
    14 => 'ตราด',
    15 => 'ฉะเชิงเทรา',
    16 => 'ปราจีนบุรี',
    17 => 'นครนายก',
    18 => 'สระแก้ว',
    19 => 'นครราชสีมา',
    20 => 'บุรีรัมย์',
    21 => 'สุรินทร์',
    22 => 'ศรีสะเกษ',
    23 => 'อุบลราชธานี',
    24 => 'ยโสธร',
    25 => 'ชัยภูมิ',
    26 => 'อำนาจเจริญ',
    27 => 'หนองบัวลำภู',
    28 => 'ขอนแก่น',
    29 => 'อุดรธานี',
    30 => 'เลย',
    31 => 'หนองคาย',
    32 => 'มหาสารคาม',
    33 => 'ร้อยเอ็ด',
    34 => 'กาฬสินธุ์',
    35 => 'สกลนคร',
    36 => 'นครพนม',
    37 => 'มุกดาหาร',
    38 => 'เชียงใหม่',
    39 => 'ลำพูน',
    40 => 'ลำปาง',
    41 => 'อุตรดิตถ์',
    42 => 'แพร่',
    43 => 'น่าน',
    44 => 'พะเยา',
    45 => 'เชียงราย',
    46 => 'แม่ฮ่องสอน',
    47 => 'นครสวรรค์',
    48 => 'อุทัยธานี',
    49 => 'กำแพงเพชร',
    50 => 'ตาก',
    51 => 'สุโขทัย',
    52 => 'พิษณุโลก',
    53 => 'พิจิตร',
    54 => 'เพชรบูรณ์',
    55 => 'ราชบุรี',
    56 => 'กาญจนบุรี',
    57 => 'สุพรรณบุรี',
    58 => 'นครปฐม',
    59 => 'สมุทรสาคร',
    60 => 'สมุทรสงคราม',
    61 => 'เพชรบุรี',
    62 => 'ประจวบคีรีขันธ์',
    63 => 'นครศรีธรรมราช',
    64 => 'กระบี่',
    65 => 'พังงา',
    66 => 'ภูเก็ต',
    67 => 'สุราษฎร์ธานี',
    68 => 'ระนอง',
    69 => 'ชุมพร',
    70 => 'สงขลา',
    71 => 'สตูล',
    72 => 'ตรัง',
    73 => 'พัทลุง',
    74 => 'ปัตตานี',
    75 => 'ยะลา',
    76 => 'นราธิวาส',
    77 => 'บึงกาฬ'
];

// ฟังก์ชันแสดงเงินเดือน
function displaySalary($salary) {
    return $salary == 0 ? 'ไม่ระบุ' : number_format($salary) . ' บาท';
}

                    while ($applicant = $applicants_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($applicant['account_name']) ?></td>
                        <td><?= htmlspecialchars($applicant['application_date']) ?></td>
                        <td><?= $applicant['Suitability'] == 0 ? 'ไม่พบค่าความเหมาะสม' : htmlspecialchars($applicant['Suitability']) . '%' ?>
                        </td>
                        <td><?= isset($sexs[$applicant['sex']]) ? $sexs[$applicant['sex']] : 'ไม่ระบุ' ?></td>
                        <td><?= htmlspecialchars($applicant['old']) ?></td>
                        <td><?= isset($qualifications[$applicant['qualification']]) ? $qualifications[$applicant['qualification']] : 'ไม่ระบุ' ?>
                        </td>
                        <td><?= isset($courses[$applicant['course']]) ? $courses[$applicant['course']] : 'ไม่ระบุ' ?>
                        </td>
                        <td><?= htmlspecialchars($applicant['experience']) ?></td>
                        <td><?= htmlspecialchars($applicant['start_date']) ?></td>
                        <td><?= ($applicant['employment_type'] == 1) ? 'งานประจำ' : 'งานพาร์ทไทม์' ?></td>

                        <td><?= isset($preferred_location[$applicant['preferred_location']]) ? $preferred_location[$applicant['preferred_location']] : 'ไม่ระบุ' ?>
                        </td>
                        <td><?= htmlspecialchars($applicant['expected_salary']) ?></td>
                        <td><?= htmlspecialchars($applicant['interested_job_type']) ?></td>
                        <td><?= htmlspecialchars($applicant['conscription']) ?></td>
                        

                        <!--td><button class="callback-btn">ติดต่อ</button></td-->
                        <td><a href="send-email-contact-applicant.php?application_id=<?= $applicant['application_id'] ?>" class="action-btn callback-btn">ติดต่อ</a></td>
                    
                        <td><input type="checkbox" name="selected_applicants[]" value="<?= htmlspecialchars($applicant['application_id']); ?>"></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!--button type="submit" class="contact-button">ติดต่อผู้สมัครที่เลือก</button-->
        </form>

            <?php else: ?>
            <p>ไม่มีข้อมูลผู้สมัคร</p>
            <?php endif; ?>
        </section>
    </div>

    
    <br><br><br>
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
    function contactApplicant(applicantId) {
        alert("กำลังติดต่อผู้สมัครที่มี ID: " + applicantId);
        // เพิ่มฟังก์ชันติดต่อผู้สมัครตามต้องการ
    }
    </script>
</body>

</html>