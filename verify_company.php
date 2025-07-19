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
$Age_range = $userapplicant['Age_range'] ?? '';         // ช่วงอายุ

$gender = $userapplicant['gender'] ?? '';
$education = $userapplicant['education'] ?? '';
$major = $userapplicant['major'] ?? '';
$experience = $userapplicant['experience'] ?? '';

$applicant_id = $userapplicant['applicant_id'] ?? '';
?>



<?php
// ฟังก์ชันสำหรับเช็คว่าบริษัทถูกต้องหรือไม่
function checkCompanyValidity($juristic_id) {
    $url = "https://dataapi.moc.go.th/ditp-member?juristic_id=" . $juristic_id;

    // ใช้ cURL ในการเรียก API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // ปิดการตรวจสอบ SSL (หากต้องการ)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ปิดการตรวจสอบ SSL (หากต้องการ)
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        // แสดงข้อผิดพลาดหากมีปัญหากับ cURL
        return 'cURL error: ' . curl_error($ch);
    }
    
    curl_close($ch);

    // แปลงข้อมูล JSON เป็น array
    $data = json_decode($response, true);

    // ตรวจสอบว่ามีข้อมูล juristic_id หรือไม่
    if (!empty($data) && isset($data['juristic_id'])) {
        // ตรวจสอบว่าข้อมูล juristic_id ตรงกับที่เราส่งไปหรือไม่
        if ($data['juristic_id'] == $juristic_id) {
            return $data; // ส่งข้อมูลบริษัทกลับไป
        } else {
            return false; // เลขนิติบุคคลไม่ตรงกับข้อมูลใน API
        }
    } else {
        return false; // ไม่มีข้อมูลบริษัท
    }
}

$companyInfo = null; // ตัวแปรเก็บข้อมูลบริษัท

// ตัวอย่างการใช้งาน
if (isset($_GET['juristic_id'])) {
    $juristic_id = $_GET['juristic_id'];

    // เช็คว่าบริษัทถูกต้องหรือไม่
    $companyInfo = checkCompanyValidity($juristic_id);

    if ($companyInfo) {
        $message = "เลขนิติบุคคลถูกต้อง: " . $companyInfo['corporate_name_th'];
    } else {
        $message = "บริษัทไม่ถูกต้อง หรือไม่มีข้อมูล, ไม่สามารถเข้าถึงประกาศงานได้";
    }
}
?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== CSS ==========-->
    <link rel="stylesheet" href="assets/css/styles (1).css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">

    <title>ตรวจสอบบริษัท</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: relative;
        /* เพิ่มตำแหน่งสำหรับปิด */
    }

    h1 {
        text-align: center;
        color: #333;
    }

    form {
        margin: 20px 0;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        display: inline-block;
        padding: 10px 15px;
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
    }

    .message {
        margin-top: 20px;
        padding: 15px;
        border-radius: 5px;
        font-size: 16px;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        color: #888;
        font-size: 20px;
        cursor: pointer;
    }

    .close-btn:hover {
        color: #ff0000;
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

                    <a href="verify_company.php"><button type="button"
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


    <div class="container">
        <button class="close-btn" onclick="window.history.back();">&times;</button>
        <h1>ตรวจสอบบริษัท</h1>
        <form action="" method="get">
            <label for="juristic_id">กรอกเลขนิติบุคคล:</label>
            <input type="text" id="juristic_id" name="juristic_id" required>
            <button type="submit">ตรวจสอบ</button>
            <a href="job_announcement.php"><button type="button"
                    style="background-color: blueviolet;">เข้าสู่หน้าประกาศงาน</button></a>
        </form>

        <?php if (isset($message)): ?>
        <div class="message <?php echo $companyInfo ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
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


</body>

</html>