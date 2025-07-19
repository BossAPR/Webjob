<?php
      $con= mysqli_connect("localhost","root","","webjob") or die("Error: " . mysqli_error($con));
      mysqli_query($con, "SET NAMES 'utf8' ");
      error_reporting( error_reporting() & ~E_NOTICE );
      date_default_timezone_set('Asia/Bangkok');

      
session_start(); // เริ่มต้นเซสชัน

// ตรวจสอบและล้างข้อความเซสชันหลังจากการแสดงผล
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// ล้างข้อความเซสชัน
unset($_SESSION['message']);
unset($_SESSION['message_type']);

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
    #box {

        border-radius: 5px;
        border: 1px solid black;
        color: hsl(0, 0%, 30%);
        border-color: hsl(0, 0%, 70%);


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
    <script>
    window.onload = function() {
        var message =
            "<?php echo addslashes($message); ?>"; // ใช้ addslashes เพื่อป้องกันการตีความผิดของอักขระพิเศษ
        var messageType = "<?php echo addslashes($message_type); ?>";

        if (message) {
            if (messageType === 'success') {
                alert("Success: " + message);
            } else if (messageType === 'error') {
                alert("Error: " + message);
            }
        }
    }
    </script>

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

            <div class="home__containerS">
                <div style="font-size: 30px; color: black; margin-bottom: 10px;">สร้างประกาศรับสมัครงาน</div> <br>

            </div>
            <div class="home__containerS2">
                <div style="font-size: 20px; color: black;">กรอกรายละเอียดของประกาศรับสมัครงาน</div>
            </div>
        </section>




        <div class="search__containerP" style="height: 1500px;">

            <form action="Job_post_process.php" method="POST" enctype="multipart/form-data">
                <label style="font-size :18px;">รูปภาพโลโก้บริษัท :</label>
                <input type="file" name="upload" />


                <label style="font-size :18px;">รูปแบบงาน :</label>
                <select name='job_type' id="box" style="font-size :17px;" required>
                    <option disabled selected value> -- ประเภทงาน -- </option>
                    <option value="1">งานประจำ</option>
                    <option value="2">งานพาร์ทไทม์</option>
                </select>
                <br><br>

                <label style="font-size :18px;">ชื่อบริษัท :</label>
                <input type="text" name="company_name" id="box" style="font-size :17px;" placeholder="ชื่อบริษัท"
                    required><br><br>

                <label style="font-size :18px;">ชื่อตำแหน่งงาน :</label>
                <input type="text" name="job_name" id="box" style="font-size :17px;" placeholder="ชื่อตำแหน่งงาน"
                    required><br><br>

                <label style="font-size: 18px;">หมวดหมู่ :</label>
                <select name="job_category" id="box" style="font-size: 17px;" required>
                    <option disabled selected value> -- เลือกหมวดหมู่ -- </option>
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
                <br><br>

                <label style="font-size :18px;">จำนวนผู้สมัครงานที่ต้องการ :</label>
                <input type="int" name="job_workers" id="box" style="font-size :17px;"
                    placeholder="จำนวนผู้สมัครงานที่ต้องการ" required><br><br>

                <label style="font-size :18px;">จำนวนเงินเดือน :</label>
                <input type="int" name="job_salary" id="box" style="font-size :17px;" placeholder="กรุณากรอกเป็นตัวเลข"
                    required><br><br>

                <label style="font-size :18px;">เวลาทำงาน :</label>
                <select id="job_time" name="job_time" onchange="showCustomTime()" style="font-size :17px;">
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
                </div><br><br>

                <label style="font-size :18px;">สวัสดิการ :</label></br>
                <textarea name="job_welfare" rows="4" cols="50" id="box"
                    placeholder="เช่น ค่าน้ำมันรถ, ค่าเดินทาง, ค่าเบี้ยเลี้ยง" style="font-size :17px;"
                    required></textarea><br><br>

                <label style="font-size :18px;">รายละเอียดงาน :</label></br>
                <textarea name="job_detail" rows="4" cols="50" id="box" placeholder="รายละเอียด"
                    style="font-size :17px;" required></textarea><br><br>

                <label style="font-size: 18px;">เพศ :</label>
                <select name="sex" id="box" style="font-size: 17px;" required>
                    <option value="" disabled selected> -- เลือกเพศ -- </option>
                    <option value="0">ชาย</option>
                    <option value="1">หญิง</option>
                    <option value="2">อื่นๆ</option>
                </select>
                <br><br>


                <label style="font-size :18px;">อายุขั้นต่ำที่รับ :</label>
                <input type="int" name="age_min" id="box" style="font-size :17px;" placeholder="อายุขั้นต่ำที่รับ"
                    required><br><br>

                <label style="font-size :18px;">อายุมากสุดที่รับ :</label>
                <input type="int" name="age_max" id="box" style="font-size :17px;" placeholder="อายุมากสุดที่รับ"
                    required><br><br>

                <label style="font-size :18px;">วุฒิการศึกษา :</label>
                <!--input type="text" name="job_qualification" id="box" style="font-size :17px;" required-->
                <select id="box" name="qualification" style="font-size :17px;">
                    <option disabled selected value> -- เลือกวุฒิการศึกษา -- </option>
                    <option value="0">ไม่มีวุฒิการศึกษา</option>
                    <option value="1">ประถมศึกษา</option>
                    <option value="2">มัธยมศึกษาตอนต้น</option>
                    <option value="3">มัธยมศึกษาตอนปลายหรือเทียบเท่า</option>
                    <option value="4">อนุปริญญา</option>
                    <option value="5">ปริญญาตรีขึ้นไปหรือเทียบเท่า</option>
                </select>
                <br><br>

                <label style="font-size :18px;">หลักสูตรที่จบ :</label>
                <!--input type="text" name="job_course" id="box" style="font-size :17px;" required-->
                <select name="course" id="box" style="font-size: 17px;" required>
                    <!-- ตัวเลือกหลักสูตร -->
                    <option value="0">ภาษาไทย</option>
                    <option value="1">ภาษาอังกฤษ</option>
                    <option value="2">ภาษาต่างประเทศ</option>
                    <option value="3">มนุษยศาสตร์</option>
                    <option value="4">สังคมศาสตร์</option>
                    <option value="5">จิตวิทยา</option>
                    <option value="6">คณิตศาสตร์</option>
                    <option value="7">ฟิสิกส์</option>
                    <option value="8">เคมี</option>
                    <option value="9">ชีววิทยา</option>
                    <option value="10">วิทยาศาสตร์สิ่งแวดล้อม</option>
                    <option value="11">วิศวกรรมไฟฟ้า</option>
                    <option value="12">วิศวกรรมเครื่องกล</option>
                    <option value="13">วิศวกรรมโยธา</option>
                    <option value="14">วิศวกรรมสารสนเทศ</option>
                    <option value="15">วิศวกรรมการบิน</option>
                    <option value="16">แพทยศาสตร์</option>
                    <option value="17">ทันตแพทยศาสตร์</option>
                    <option value="18">เภสัชศาสตร์</option>
                    <option value="19">สาธารณสุขศาสตร์</option>
                    <option value="20">การพยาบาล</option>
                    <option value="21">การจัดการ</option>
                    <option value="22">การตลาด</option>
                    <option value="23">การเงิน</option>
                    <option value="24">บัญชี</option>
                    <option value="25">เศรษฐศาสตร์</option>
                    <option value="26">การศึกษา</option>
                    <option value="27">จิตวิทยาการศึกษา</option>
                    <option value="28">การพัฒนาหลักสูตร</option>
                    <option value="29">ศิลปกรรม</option>
                    <option value="30">การออกแบบผลิตภัณฑ์</option>
                    <option value="31">การออกแบบกราฟิก</option>
                    <option value="32">สถาปัตยกรรม</option>
                    <option value="33">สื่อสารมวลชน</option>
                    <option value="34">การโฆษณา</option>
                    <option value="35">การประชาสัมพันธ์</option>
                    <option value="36">วิทยาการคอมพิวเตอร์</option>
                    <option value="37">เทคโนโลยีสารสนเทศ</option>
                    <option value="38">ความมั่นคงไซเบอร์</option>
                </select>



                <br><br>

                <label style="font-size :18px;">ประสบการณ์ทำงาน(ปี) :</label>
                <input type="int" name="experience_min" id="box" style="font-size :17px;"
                    placeholder="ประสบการณ์ทำงาน(ปี)" required><br><br>


                <label style="font-size :18px;">สถานที่ทำงาน :</label></br>
                <textarea name="job_location" style="font-size :17px;" rows="4" cols="50" id="box"
                    required></textarea><br><br>

                <label style="font-size :18px;">จังหวัด :</label>
                <select name="job_province" id="provinces" style="font-size :17px;" required>
                    <option value="" selected disabled>-กรุณาเลือกจังหวัด-</option>
                    <?php foreach ($query as $value) { ?>
                    <option value="<?=$value['id']?>"><?=$value['name_th']?></option>
                    <?php } ?>
                </select>
                <br><br>

                <label style="font-size :18px;">อำเภอ/เขต :</label>
                <!--select name="job_district" id="amphures" style="font-size :17px;" required-->
                <input type="text" name="job_district" id="box" style="font-size :17px;" placeholder="อำเภอ/เขต"
                    required><br><br>
                <!--/select-->

                <label for="verify_company" style="font-size :18px;">ไฟล์เลขนิติบุคคลของบริษัท :</label>
                <input type="file" name="uploadverify" /><br><br>

                <label for="job_expire_at">วันหมดอายุ:</label>
                <input type="datetime-local" id="job_expire_at" name="job_expire_at"><br><br>

                <label for="job_expire_at">หรือเลือกเป็นช่วงวันหมดอายุของประกาศงาน:</label>
                <select id="job_expire_at" name="job_expire_at">
                    <option value="1_week">1 อาทิตย์</option>
                    <option value="1_month">1 เดือน</option>
                    <option value="3_month">3 เดือน</option>
                </select><br><br>

                <center><button type="submit" class="btn btn-success" style="width:300px; font-size :23px;"
                        onclick="showSuccessAlert()">สร้างประกาศ</button></center>


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

        <!--========== SCROLL REVEAL ==========-->
        <script src="https://unpkg.com/scrollreveal"></script>

        <!--========== MAIN JS ==========-->
        <script src="assets/js/main.js"></script>

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
<?php include('script.php');?>