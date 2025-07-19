<?php
      $con= mysqli_connect("localhost","root","","webjob") or die("Error: " . mysqli_error($con));
      mysqli_query($con, "SET NAMES 'utf8' ");
      error_reporting( error_reporting() & ~E_NOTICE );
      date_default_timezone_set('Asia/Bangkok');
?>

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


<!--?php
// ดึงข้อมูลผู้สมัครจากฐานข้อมูล
$applicant_id = $_SESSION['account_id']; // ใช้ account_id เป็น ID ของผู้สมัคร
$applicant_query = "SELECT * FROM applicant WHERE account_id = '$applicant_id'";
$applicant_result = mysqli_query($connect, $applicant_query);

// ตรวจสอบว่ามีข้อมูลผู้สมัครหรือไม่
if ($applicant_result && mysqli_num_rows($applicant_result) > 0) {
    $applicant_data = mysqli_fetch_assoc($applicant_result);
} else {
    // ถ้าไม่พบข้อมูลผู้สมัคร ให้แสดงข้อความว่าไม่พบข้อมูลโปรไฟล์
    $applicant_data = null;
}


// เรียก API และดึงข้อมูล JSON ด้วย file_get_contents
$api_url = "http://127.0.0.1:5000/predict/" . $user_id;
$api_data = file_get_contents($api_url); // ดึงข้อมูลจาก API

// แปลง JSON เป็น array
$api_results = json_decode($api_data, true);

// ตรวจสอบว่า API ส่งข้อมูลกลับมาหรือไม่
if ($api_results) {
    // สร้าง array เพื่อเก็บ suitability_score โดยใช้ job_ad_id เป็น key
    $suitability_map = [];
    foreach ($api_results as $api_result) {
        $suitability_map[$api_result['job_ad_id']] = $api_result['suitability_score'];
    }

    // ทดสอบการแสดงผล array $suitability_map
    //print_r($suitability_map);
} else {
    echo "ไม่สามารถดึงข้อมูลจาก API ได้";
}

?-->

<!--?php
// ฟังก์ชันในการคำนวณความเหมาะสม
function calculateSuitability($applicant, $job) {
    // ถ้าไม่มีข้อมูลผู้สมัคร ให้คืนค่า 0%
    if (!$applicant) {
        return 0;
    }

    $score = 0;

    // เปรียบเทียบช่วงอายุ
    $age_range = explode('-', $applicant['Age_range']);
    if ($applicant['Age_range'] >= $job['job_oldmin'] && $applicant['Age_range'] <= $job['job_oldmax']) {
        $score += 20; // ให้คะแนน 20 ถ้าอายุเหมาะสม
    }

    // เปรียบเทียบการศึกษา
    $qualifications = explode(',', $job['job_qualification']);
    if (in_array($applicant['education'], $qualifications)) {
        $score += 20; // ให้คะแนน 20 ถ้าเหมาะสม
    }

    // เปรียบเทียบประสบการณ์
    $experience_years = (int)$applicant['experience']; // สมมติว่าประสบการณ์เป็นปี
    if ($experience_years >= $job['job_exp']) {
        $score += 20; // ให้คะแนน 20 ถ้ามีประสบการณ์มากพอ
    }

    // เปรียบเทียบประเภทงาน
    if ($applicant['employment_type'] == $job['job_type'] || 
        $applicant['interested_job_type'] == $job['job_type'] || 
        $applicant['work_type'] == $job['job_type']) {
        $score += 20; // ให้คะแนน 20 ถ้าเหมาะสม
    }

    // เปรียบเทียบเงินเดือน
    if ($applicant['expected_salary'] <= $job['job_salary']) {
        $score += 20; // ให้คะแนน 20 ถ้าเงินเดือนที่ต้องการต่ำกว่าหรือเท่ากับที่ประกาศ
    }

    // คำนวณเปอร์เซ็นต์
    $percentage = $score; // เปอร์เซ็นต์ (เต็ม 100)

    return $percentage;
}
?-->


<?php
require('connectdb.php');
/*ของงานแนะนำล่าสุด*/
try {
    $conn = new PDO("mysql:servername=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query เพื่อดึงงานแนะนำล่าสุด 6 งาน
    //$stmt = $conn->prepare("SELECT job_name, job_type, salary, work_time, location, logo FROM job_ad ORDER BY created_at DESC LIMIT 6");

    /*$stmt = $conn->prepare("SELECT job_name, job_type, salary, work_time, location, logo 
                        FROM job_ad 
                        ORDER BY created_at DESC, RAND() 
                        LIMIT 6");*/

                        $stmt = $conn->prepare("SELECT * FROM job_ad 
                        WHERE job_status = 'approved'  and job_workers > got_workers
                        ORDER BY job_create_at DESC, RAND() ");
                                           

    $stmt->execute();

    // เก็บผลลัพธ์ใน array
    $recommended_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // ตรวจสอบว่า query มีผลลัพธ์หรือไม่
    if (!$recommended_jobs) {
        $recommended_jobs = [];
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
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
require('connectdb.php');
include('predict_suitability.php');

$suitability_scores = isset($_SESSION['suitability_scores']) ? $_SESSION['suitability_scores'] : [];

// Jobs Section 
?>

<!DOCTYPE html>
<html lang="en">

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
    <title>website job</title>
    <style>
    table {
        border-collapse: collapse;
        width: 70%;
        margin: 20px auto;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .no-results {
        font-style: italic;
        color: #888;
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
    <?php
    $sql_provinces = "SELECT * FROM provinces";
    $query = mysqli_query($con, $sql_provinces);
    ?>

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

    <main class="l-main">
        <!--========== HOME ==========-->
        <section class="home" id="home">

            <div class="home__containerS">
                <div style="font-size: 30px; color: black; margin-bottom: 10px;">ค้นหางานพาร์ทไทม์ งานประจำ
                    และงานทั้งหมด</div> <br>

            </div>
            <div class="home__containerS2">
                <div style="font-size: 20px; color: black;">เรามีงานให้คุณเลือก มากมาย</div>
            </div>
        </section>




        <div class="search__container">
            <form action="" method="GET">

                <label for="fname">คำที่ต้องการค้นหา</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="cars">ประเภทงาน</label>
                <br>
                <input type="text" id="job_name" name="job_name" size="70" style='font-size: 20px;'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                <select name="job_type" id="job_type" style='font-size: 22px;'>
                    <option value="">ทั้งหมด</option>
                    <option value="1">งานประจำ</option>
                    <option value="2">งานพาร์ทไทม์</option>
                </select>

                <br><br>
                <label for="cars">พื้นที่สะดวกทำงาน</label>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <!--label for="fname">คำที่ต้องการค้นหา</label-->

                <br>
                <label style="font-size :18px;">จังหวัด :</label>
                <select name="job_province" id="provinces" style="font-size :17px;">
                    <option value="">-ทั้งหมด-</option>
                    <?php foreach ($query as $value) { ?>
                    <option value="<?=$value['id']?>"><?=$value['name_th']?></option>
                    <?php } ?>
                </select> &nbsp;&nbsp;


                <label for="job_category" style="font-size :18px;">หมวดหมู่:</label>
                <select name='job_category' id="job_category"
                    style="font-size :17px; ">
                    <option disabled selected value> -- หมวดหมู่ -- </option>
                    <option value="ภาษาและมนุษยศาสตร์">ภาษาและมนุษยศาสตร์</option>
                    <option value="สังคมศาสตร์และจิตวิทยา">สังคมศาสตร์และจิตวิทยา</option>
                    <option value="วิทยาศาสตร์พื้นฐานและธรรมชาติ">วิทยาศาสตร์พื้นฐานและธรรมชาติ</option>
                    <option value="วิศวกรรมศาสตร์">วิศวกรรมศาสตร์</option>
                    <option value="แพทยศาสตร์และสาธารณสุข">แพทยศาสตร์และสาธารณสุข</option>
                    <option value="การบริหารและการเงิน">การบริหารและการเงิน</option>
                    <option value="การศึกษาและพัฒนาหลักสูตร">การศึกษาและพัฒนาหลักสูตร</option>
                    <option value="ศิลปกรรมและการออกแบบ">ศิลปกรรมและการออกแบบ</option>
                    <option value="สื่อสารมวลชนและประชาสัมพันธ์">สื่อสารมวลชนและประชาสัมพันธ์</option>
                    <option value="วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ">วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ
                    </option>
                </select>



                <!--label style="font-size :18px;">อำเภอ/เขต :</label>
                <select name="job_district" id="amphures" style="font-size :17px;">
                </select--> 




                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-success" style="width:20%;">ค้นหา</button>
            </form>



        </div>
        </br></br>
        <center>
            <table class="table table-bordered" style="width:70%; text-align: center;">
                <thead>
                    <tr>
                        <!--th>รหัสงาน</th-->
                        <th></th>
                        <th>ชื่อตำแหน่ง</th>
                        <th>ลักษณะงาน</th>
                        <th>เงินเดือน</th>
                        <th>เวลาทำงาน</th>
                        <th>สถานที่ทำงาน</th>
                        <th>ความเหมาะสม</th>

                        <th>หมวดหมู่งาน</th>

                        <th>โพสต์เมื่อ</th>
                        <th>รายละเอียดเพิ่มเติม</th>
                    </tr>
                </thead>
                <tbody>

<?php
// Connect to the database
$conD = new mysqli("localhost", "root", "", "webjob");

// Check connection
if ($conD->connect_error) {
    die("Connection failed: " . $conD->connect_error);
}

// Define the province map
$province_map = [
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


if (isset($_GET['job_name']) || isset($_GET['job_type']) || isset($_GET['job_province']) || isset($_GET['job_district'])) {
    // รับค่าจากฟอร์ม
    $job_name = $_GET['job_name'] ?? null;
    $job_type = $_GET['job_type'] ?? null;
    $job_province = $_GET['job_province'] ?? null;
    $job_district = $_GET['job_district'] ?? null;
    
    $job_category = $_GET['job_category'] ?? null;

    // สร้าง query สำหรับการค้นหา
    $query = "SELECT * FROM job_ad WHERE job_status = 'approved' and job_workers > got_workers";
    $params = [];
    $types = '';

    // เพิ่มเงื่อนไขใน query ตาม filter ที่มีการกรอก
    if ($job_name) {
        $query .= " AND job_name LIKE ?";
        $types .= 's';
        $params[] = '%' . $job_name . '%';
    }
    if ($job_type) {
        $query .= " AND job_type = ?";
        $types .= 'i';
        $params[] = (int)$job_type;
    }
    if ($job_province) {
        $query .= " AND job_province = ?";
        $types .= 's';
        $params[] = $job_province;
    }
    if ($job_district) {
        $query .= " AND job_district = ?";
        $types .= 's';
        $params[] = $job_district;
    }

    if ($job_category) {
        $query .= " AND job_category = ?";
        $types .= 's';
        $params[] = $job_category;
    }
    // เตรียมและดำเนินการ query
    $stmt = $conD->prepare($query);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $jobs_with_scores = []; // อาเรย์สำหรับเก็บข้อมูลงานและคะแนนความเหมาะสม

    if ($result->num_rows > 0) {
        while ($items = $result->fetch_assoc()) {
            $province_name = isset($province_map[$items['job_province']]) ? $province_map[$items['job_province']] : 'ไม่ระบุจังหวัด';
            
            // คำนวณคะแนนความเหมาะสมสำหรับแต่ละงาน
            $job_id = $items['job_ad_id'];
            $suitability_score = null; // เริ่มต้นเป็น null

            if (!empty($suitability_scores)) {
                foreach ($suitability_scores as $applicant_id => $scores) {
                    foreach ($scores as $score_data) {
                        if ($score_data['job_id'] == $job_id) {
                            $suitability_score = $score_data['suitability_score'];
                            break 2; // เลิกวนลูปเมื่อเจอคะแนนที่ตรงกัน
                        }
                    }
                }
            }

            // ถ้าคะแนนความเหมาะสมเป็น null ให้กำหนดเป็น 0
            if ($suitability_score === null) {
                $suitability_score = 0; // หรือกำหนดค่าอื่นๆ ตามต้องการ
            }

            // เก็บข้อมูลงานและคะแนนความเหมาะสมลงในอาเรย์
            $jobs_with_scores[] = [
                'job' => $items,
                'suitability_score' => $suitability_score,
                'province_name' => $province_name
            ];
        }

        // เรียงอาเรย์ตามคะแนนความเหมาะสมจากมากไปน้อย
        usort($jobs_with_scores, function($a, $b) {
            return $b['suitability_score'] <=> $a['suitability_score'];
        });

        // แสดงผลในตาราง
        foreach ($jobs_with_scores as $job_with_score) {
            $items = $job_with_score['job'];
            $suitability_score = $job_with_score['suitability_score'];
            $province_name = $job_with_score['province_name'];
            ?>
            <tr>
                <!--td><-?= htmlspecialchars($items['job_ad_id']); ?></td-->
                <td>
                    <?php if (!empty($items['job_logo'])): ?>
                    <img src="assets/account_images/<?= htmlspecialchars($items['job_logo']); ?>" alt="Job Logo"
                        style="max-width: 100px; max-height: 100px;">
                    <?php else: ?>
                    ไม่มีรูป
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($items['job_name']); ?></td>
                <td>
                    <?php
                    if ($items['job_type'] == 1 || $items['job_type'] =='งานประจำ') {
                        echo 'งานประจำ';
                    } elseif ($items['job_type'] == 2 || $items['job_type'] =='งานพาร์ทไทม์') {
                        echo 'งานพาร์ทไทม์';
                    } else {
                        echo 'ประเภทงานไม่ระบุ';
                    }
                    ?>
                </td>
                <!--td><!?= htmlspecialchars($items['job_salary']); ?></td-->

                <td><?php 
                        if (isset($items['job_salary']) && $items['job_salary'] > 0) {
                        //echo htmlspecialchars($items['job_salary']);
                        echo number_format($items['job_salary'], 0, '.', ',') . ' บาท';
                        } else {
                        echo 'ไม่ระบุ'; 
                        }
                        ?>
                </td>

                <td><?= htmlspecialchars($items['job_time']); ?></td>
                <td><?= htmlspecialchars($province_name); ?></td>
                <!--td><!?= htmlspecialchars($suitability_score) . "%"; ?></td-->
                <td><?= number_format($suitability_score, 2) . "%"; ?></td>

                <td><?= htmlspecialchars($items['job_category']); ?></td>
                <td><?php echo time_elapsed_string($items['job_create_at']); ?></td>
                <td>
                    <a href="af_job_detailbyAI.php?job_id=<?= htmlspecialchars($items['job_ad_id']); ?>"
                        class="button">รายละเอียดเพิ่มเติม</a>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='7'>ไม่พบงานที่ตรงตามเงื่อนไข</td></tr>";
    }

    // ปิด statement และการเชื่อมต่อ
    $stmt->close();
}

$conD->close();
?>
                </tbody>
            </table>
        </center>

        <!-- Jobs Section -->
        <section class="menu section bd-container" id="menu">
            <span class="section-subtitle">งานแนะนำ</span>
            <h2 class="section-title">อัพเดตงานแนะนำล่าสุด</h2>
            <div class="menu__container bd-grid">
                <?php if (!empty($recommended_jobs)): ?>
                <?php foreach ($recommended_jobs as $job): ?>
                <div class="menu__content">
                    <img src="assets/account_images/<?php echo htmlspecialchars($job['job_logo']); ?>" alt=""
                        class="menu__img">
                    <h3 class="menu__name"><?php echo htmlspecialchars($job['job_name']); ?></h3>
                    <!--span class="menu__detail"><strong>ลักษณะงาน:
                        </strong><!?php echo htmlspecialchars($job['job_type']); ?></span-->

                    <span class="menu__detail"><strong>ลักษณะงาน:
                        </strong><?php if ($job['job_type'] == 1 || $job['job_type'] == 'งานประจำ') {
                            echo 'งานประจำ';
                            } elseif ($job['job_type'] == 2 || $job['job_type'] == 'งานพาร์ทไทม์') {
                            echo 'งานพาร์ทไทม์';
                            } else {
                            echo 'ประเภทงานไม่ระบุ';
                            }
                        ?>
                    </span>


                    <span class="menu__detail"><strong>จำนวนเงิน:</strong>
                        <!--?php echo isset($job['job_salary']) ? htmlspecialchars($job['job_salary']) : 'ไม่ระบุ'; ?-->
                        <?php 
                        if (isset($job['job_salary']) && $job['job_salary'] > 0) {
                        //echo htmlspecialchars($job['job_salary']);
                        // ใช้ number_format เพื่อคั่นหลักพัน และเพิ่มคำว่า "บาท"
                        echo number_format($job['job_salary'], 0, '.', ',') . ' บาท';
                        } else {
                        echo 'ไม่ระบุ'; 
                        }
                        ?>
                    </span>
                    <!--span class="menu__detail"><strong>เวลาทำงาน:</strong>
                        <!?php echo isset($job['job_time']) ? htmlspecialchars($job['job_time']) : 'ไม่ระบุ'; ?></span-->

                    <span class="menu__detail"><strong>เวลาทำงาน:</strong>
                        <?php 
                        if (isset($job['job_time']) && !empty($job['job_time'])) {
                        echo htmlspecialchars($job['job_time']) . ' น.'; // เพิ่ม "น." ต่อท้ายเวลา
                        } else {
                        echo 'ไม่ระบุ'; 
                        }
                        ?>
                    </span>
                    
                    <span class="menu__detail"><strong>สถานที่ทำงาน:</strong>
                        <?php echo isset($job['job_location']) ? htmlspecialchars($job['job_location']) : 'ไม่ระบุ'; ?></span>
                    <?php
                // คำนวณความเหมาะสมสำหรับงานนี้
                //$suitability = calculateSuitability($applicant_data, $job);
                    ?>

                    <!--p><strong>ความเหมาะสม:</strong> <-?php echo $suitability; ?>%</p><br-->
                    <!--p><strong>ความเหมาะสม:</strong--> <!-- แสดงค่าความเหมาะสมจาก AI -->
                    <!--?php
                    $job_id = $job['job_ad_id'];
                    $suitability_score = 'ไม่สามารถคำนวณได้'; 

                    if (!empty($suitability_scores)) {
                        foreach ($suitability_scores as $applicant_id => $scores) {
                            foreach ($scores as $score_data) {
                                if ($score_data['job_id'] == $job_id) {
                                    $suitability_score = $score_data['suitability_score'] . "%";
                                    break 2; // เลิกวนลูปเมื่อเจอคะแนนที่ตรงกัน
                                }
                            }
                        }
                    }

                    echo "<li>Job ID: $job_id - Suitability Score: " . htmlspecialchars($suitability_score) . "</li>";
                    ?></p-->

                    
                    <span class="menu__detail"><strong>ความเหมาะสม:</strong> 
                    <?php
                    $job_id = $job['job_ad_id'];
                    $suitability_score = 'ไม่สามารถคำนวณได้'; 

                    if (!empty($suitability_scores)) {
                    foreach ($suitability_scores as $applicant_id => $scores) {
                    foreach ($scores as $score_data) {
                    if ($score_data['job_id'] == $job_id) {
                        //$suitability_score = $score_data['suitability_score'] . "%";
                        // ใช้ number_format เพื่อจำกัดทศนิยมให้เป็น 2 ตำแหน่ง
                        $suitability_score = number_format($score_data['suitability_score'], 2) . "%";
                        break 2; // เลิกวนลูปเมื่อเจอคะแนนที่ตรงกัน
                                }
                            }
                        }
                    }
                    // แสดงค่าความเหมาะสมที่ได้จาก AI
                    echo htmlspecialchars($suitability_score);
                    ?>
                    </span>
                    

                    <br>
                    
                    <span class="menu__detail">
                        <strong>โพสต์เมื่อ:</strong>
                        <?php echo time_elapsed_string($job['job_create_at']); ?>
                    </span>
                    
                    <a href="af_job_detailbyAI.php?job_id=<?php echo htmlspecialchars($job['job_ad_id']); ?>"
                        class="button menu__button">รายละเอียดเพิ่มเติม</a>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p>ไม่มีงานแนะนำในขณะนี้</p>
                <?php endif; ?>
            </div>
        </section>



        <!--========== CONTACT US ==========-->
        <section class="contact section bd-container" id="contact">
            <div class="contact__container bd-grid">
                <div class="contact__data">
                    <span class="section-subtitle contact__initial">ลองมาคุยกันเถอะ!!</span>
                    <h2 class="section-title contact__initial">ติดต่อเรา</h2>
                    <p class="contact__description"></p>
                </div>

                <div class="contact__button">
                    <a href="af_Contact_us.php" class="button">ติดต่อเราตอนนี้!</a>
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

    <!--========== SCROLL REVEAL ==========-->
    <script src="https://unpkg.com/scrollreveal"></script>

    <!--========== MAIN JS ==========-->
    <script src="assets/js/main.js"></script>
</body>

</html>
<?php include('script.php');?>