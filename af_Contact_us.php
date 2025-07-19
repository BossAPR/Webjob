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
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
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


    /* Form container styles */
    .form-container {
        display: grid;
        grid-template-columns: auto 1px 1fr;
        gap: 20px;
    }

    /* Left side styles */
    .left-container {
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
        /* Adjust width to fit content */
        height: 600px;
    }

    .left-container h1 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .left-container p {
        font-size: 16px;
        line-height: 1.6;
    }

    /* Vertical line styles */
    .vertical-line {
        border-left: 1px solid #640f0f;
        /* Vertical line color */
        height: auto;
        /* Adjust height */
    }

    /* Form styles */
    form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    form label {
        grid-column: span 2;
        margin-bottom: 5px;
    }

    form input[type="text"],
    form input[type="email"],
    form select,
    form textarea {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    form textarea {
        resize: vertical;
        height: 100px;
    }

    form input[type="checkbox"] {
        margin-right: 10px;
    }

    form button {
        grid-column: span 2;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    form button:hover {
        background-color: #45a049;
    }

    .half-width {
        grid-column: span 1;
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
                    <li class="nav__item"><a href="af_Contact_us.php" class="nav__link active-link">ศูนย์ช่วยเหลือ</a></li>


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
        <section class="contact section bd-container " id="contact">
            <div class="home__container bd-containerH"><br>
                <h1 class="section-title">
                    <center>ติดต่อเรา</center>
                </h1>
                <h1 class="home__title2" style="text-align: center;">รายละเอียดข้อมูลในการติดต่อเรา</h1>
                <br>
                <div class="form-container">
                    <div class="left-container">
                        <h1 class="section-title">ช่วยหางานพาร์ทไทม์ให้กับน้อง ๆ นักเรียน นักศึกษา ไปจนถึง บุคคลทั่วไป
                        </h1>
                        <p>
                            บริษัท จ๊อบเสิร์ช (ประเทศไทย) จำกัด
                            เลขที่ 1111 อาคารพุทธ ชั้น150 ห้องเลขที่ 555 ถนนสุขาภิบาล แขวงบางจาก เขตพระโขนง กรุงเทพฯ
                            10260
                            <br><br>
                            <i class='bx bxs-phone'></i> 099 - 888 - 7777 <br>
                            <i class='bx bxs-envelope'></i> @gmail.com <br>
                            <i class='bx bxl-twitter'></i> @Jobsearch <br>
                            <i class='bx bxl-facebook'></i> Jobsearch
                        </p>
                    </div>

                    <div class="vertical-line"></div>

                    <div class="home__data">
                        <form action="process_contact.php" method="POST">
                            <label for="firstName" class="half-width">ชื่อจริง</label>
                            <label for="lastName" class="half-width">นามสกุล</label>
                            <input type="text" id="firstName" name="firstName" placeholder="ชื่อจริง"
                                class="half-width">
                            <input type="text" id="lastName" name="lastName" placeholder="นามสกุล" class="half-width">

                            <label for="company" class="half-width">บริษัท</label>
                            <label for="position" class="half-width">ตำแหน่ง</label>
                            <input type="text" id="company" name="company" placeholder="บริษัท" class="half-width">
                            <input type="text" id="position" name="position" placeholder="ตำแหน่ง" class="half-width">

                            <label for="phone" class="half-width">เบอร์โทรศัพท์</label>
                            <label for="email" class="half-width">อีเมล</label>
                            <input type="text" id="phone" name="phone" placeholder="เบอร์โทรศัพท์" class="half-width">
                            <input type="email" id="email" name="email" placeholder="อีเมล" class="half-width">

                            <label for="jobType">กรุณาเลือกประเภทพนักงาน</label>
                            <select id="jobType" name="jobType">
                                <option value="fullTime">พนักงานประจำ</option>
                                <option value="partTime">พนักงานชั่วคราว</option>
                            </select>

                            <label for="details">รายละเอียด</label>
                            <textarea id="details" name="details" placeholder="รายละเอียด"></textarea>

                            <div class="form-input">
                                <label for="notify">รับการอัพเดตจากเรา</label>
                                <input type="checkbox" id="notify" name="notify" class="half-width">
                            </div>
                            <br>

                            <br>
                            <label>รู้จักเราจากช่องทางไหน</label>

                            <div class="form-input">
                                <input type="checkbox" id="google" name="source" value="google">
                                <label for="google">Google</label>
                            </div>

                            <div class="form-input">
                                <input type="checkbox" id="facebook" name="source" value="facebook">
                                <label for="facebook">Facebook</label>
                            </div>

                            <div class="form-input">
                                <input type="checkbox" id="twitter" name="source" value="twitter">
                                <label for="twitter">Twitter</label>
                            </div><br>

                            <button type="submit">ส่งข้อความ</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <div class="contact section " style="padding: 200px;" id="contact">
        </div>
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