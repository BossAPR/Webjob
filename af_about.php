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
                        WHERE job_status = 'approved' 
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

    /* ปรับขนาดความกว้าง header กับ main*/
    main.l-main {
        margin-top: -150px;
        /* ลดระยะห่างระหว่าง header กับ main */
    }

    /* ลดระยะห่างระหว่าง section ต่าง ๆ */
    .contact.section.bd-container {
        padding: 0;
        margin-top: 0;
    }

    /* ลดระยะห่างระหว่างข้อความ h1, h2 และ p */
    .home__container h2,
    .home__container h1,
    .home__container p {
        margin-bottom: 5px;
        margin-top: 1px;
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
                    <li class="nav__item"><a href="af_about.php" class="nav__link active-link">เกี่ยวกับเรา</a></li>
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
        <section class="home" id="home">
            <style>
            body {
                background-image: url('assets/account_images/HP.jpg');
                background-repeat: no-repeat;
                background-size: contain;
                /* ปรับขนาดภาพให้พอดีกับขอบหน้าจอ */


            }

            .vertical-line {
                border-left: 1px solid #640f0f;
                /* เส้นแบ่งสีเทา */
                height: 100%;
                /* ความสูงเท่ากับความสูงของพื้นที่ */
                margin-left: 20px;
                /* ระยะห่างจากขอบซ้าย */
                margin-right: 20px;
                /* ระยะห่างจากขอบขวา */
            }
            </style>
            <div class="home__container bd-containerH">
                <h2 class="home__title">
                    <center>เกี่ยวกับเรา</center>
                </h2>
                <h1 class="home__title2" style="text-align: center;">โอกาสในการร่วมงานกับบริษัท หรือร้านค้าชั้นนำ
                    และมีคุณภาพ ที่กำลังมองหาพนักงานทั่วประเทศมากมายและหลายหลายธุรกิจ</h1>
            </div>

            <center>
                <div class="home__container bd-containerH bd-grid" style="grid-template-columns: 1fr 1px 1fr;">
                    <div class="home__data">
                        <h1 class="home__title2" style="color: rgb(12, 52, 65);">Job Search แฟลตฟอร์มหางานพาร์ทไทม์ /
                            ประจำ</h1>
                        <h2 class="home__title" style="color: blue;">แพลตฟอร์มที่ช่วยหางานพาร์ทไทม์
                            และบริการจัดหาคนทำงานพาร์ทไทม์ ให้แก่บริษัท</h2><br>
                        <a href="af_Job_search.php" class="button">บอร์ดงาน</a>
                        <a href="job_announcement.php" class="button">ประกาศหางาน</a>
                    </div>

                    <div class="vertical-line"></div>

                    <div class="home__data">
                        <h1 class="home__title2" style="color: rgb(12, 52, 65);">Job Search
                            เป็นบริการจัดหาคนทำงาน/พนักงาน พาร์ทไทม์ให้กับบริษัท ห้างร้าน รวมถึงออแกไนซ์ต่างๆที่ต้อง
                            การกำลังคนเข้าไปช่วยงาน โดยเน้นไปที่รูปแบบการทำ งานพาร์ทไทม์สำหรับผู้ที่ต้องการฝากหา
                            พนักงานกับ
                            Job Search เราให้บริการจัดหาคนแบบ One Stop Service ตั้งแต่จัดหาคน คัดกรองเบื้องต้น เทรนงาน
                            ตรวจสอบการทำงานไป จนถึงขั้นตอนการชำระเงินให้กับพนักงานแทนคุณลูกค้า</h1>
                    </div>

                </div>
        </section>



        <!--========== JOBS ==========-->
        <section class="menu section bd-container" id="menu">
            <span class="section-subtitle">Job Search</span>
            <h2 class="section-title" style="text-align: center;">ให้บริการครบวงจรแค่แจ้งความต้องการก็รอคนไปทำงานได้เลย
            </h2>



            <center>
                <div class="menu__container bd-grid" style="grid-template-columns: repeat(3, 210px);">



                    <div class="menu__content">
                        <img src="assets/account_images/money.png" alt="" class="menu__img">
                        <h3 class="menu__name">ลดต้นทุน</h3><br>
                        <span class="menu__detail">ช่วยให้คุณประหยัดเวลาในการประกาศ ตามหาพนักงาน</span><br>
                    </div>

                    <div class="menu__content">
                        <img src="assets/account_images/callcenter.png" alt="" class="menu__img">
                        <h3 class="menu__name">งานราบรื่น</h3><br>
                        <span class="menu__detail">ธุรกิจหรืองานของคุณดำเนิน ไปได้ด้วยดีไม่ขาดคน</span><br>
                    </div>

                    <div class="menu__content">
                        <img src="assets/account_images/time.png" alt="" class="menu__img">
                        <h3 class="menu__name">ประหยัดเวลา</h3><br>
                        <span class="menu__detail">ลดต้นทุนและรายจ่ายในการจ้างงาน พนักงานประจำ</span><br>
                    </div>

                </div>
            </center>
        </section>



        <!--========== CONTACT US ==========-->
        <section class="contact section bd-container" id="contact">
            <div class="home__container bd-containerH bd-grid" style="grid-template-columns: 1fr 1px 1fr;">
                <div class="home__data">
                    <h1 class="section-title">ช่วยหางานพาร์ทไทม์ให้กับน้อง ๆ นักเรียน นักศึกษา ไปจนถึง บุคคลทั่วไป</h1>
                </div>

                <div class="vertical-line"></div>

                <div class="home__data">
                    <h1 class="home__title2" style="color: rgb(12, 52, 65);">
                        เรายังเป็นแพลตฟอร์มที่ช่วยหางานพาร์ทไทม์ให้กับน้อง ๆ นักเรียน นักศึกษา ไปจนถึงบุคคลทั่วไป
                        ที่กำลังมองหาช่องทาง
                        ในการสร้างรายได้เสริมผ่านการทำงานพาร์ทไทม์ซึ่งสามารถเลือก ทำเฉพาะช่วงเวลาที่ว่าง
                        โดยที่ไม่กระทบต่อการ เรียนหรือ งานประจำ ทำรายการง่ายๆผ่าน Application Job Search
                        จนถึงขั้นตอนการชำระเงินให้กับพนักงานแทนคุณลูกค้า
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