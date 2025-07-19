<?php
session_start();
require('connectdb.php');

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
                        WHERE job_status = 'approved' and job_workers >got_workers
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
        background-color: aliceblue;
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
                    <li class="nav__item"><a href="HOME.php" class="nav__link active-link">หน้าหลัก</a></li>
                    <li class="nav__item"><a href="about.html" class="nav__link">เกี่ยวกับเรา</a></li>
                    <!--li class="nav__item"><a href="#profile" class="nav__link">ข่าวสาร</a></li-->
                    <!--li class="nav__item"><a href="#article" class="nav__link">บทความ</a></li-->
                    <!--li class="nav__item"><a href="#profile" class="nav__link">รีวิว</a></li-->
                    <li class="nav__item"><a href="Contact_us.html" class="nav__link">ศูนย์ช่วยเหลือ</a></li>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="job_search.php"><button type="button" class="btn info"><b>บอร์ดหางาน</b></button></a>
                    <a href="Logbef_postjob.php"><button type="button"
                            class="btn success"><b>ประกาศหางาน</b></button></a>&nbsp;&nbsp;
                </ul>
            </div>

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
                <div class="home__data">
                    <h1 class="home__title2">JOB SEARCH แพลตฟอร์มหางานพาร์ทไทม์ งานประจำ</h1>
                    <h2 class="home__title">หางานพาร์ทไทม์ สร้างรายได้เสริม หางานประจำ</h2><br>
                    <a href="Job_search.php" class="button">บอร์ดงาน</a>
                </div>
            </div>
        </section>



        <!--========== JOBS ==========-->
        <section class="menu section bd-container" id="menu">
            <span class="section-subtitle">งานแนะนำ</span>
            <h2 class="section-title">อัพเดตงานแนะนำล่าสุด</h2>

            <div class="menu__container bd-grid">
                <?php if (!empty($recommended_jobs)): ?>
                <?php foreach ($recommended_jobs as $job): ?>
                <div class="menu__content">
                    <img src="assets/account_images/<?php echo htmlspecialchars($job['job_logo']); ?>" alt=""
                        class="menu__img">

                    <h3 class="menu__name"><?php echo htmlspecialchars($job['job_name']); ?></h3><br>
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
                        <!--?php echo isset($job['job_salary']) ? htmlspecialchars($job['job_salary']) : 'ไม่ระบุ'; ?></span-->
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


                    <span class="menu__detail">
                        <strong>โพสต์เมื่อ:</strong>
                        <?php echo time_elapsed_string($job['job_create_at']); ?>
                    </span>

                    <a href="job_detail.php?job_id=<?php echo htmlspecialchars($job['job_ad_id']); ?>"
                        class="button menu__button"><strong>รายละเอียดเพิ่มเติม</strong></a>

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
                    <a href="Contact_us.html" class="button">ติดต่อเราตอนนี้!</a>
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
                    <li><a href="HOME.php" class="footer__link">หน้าแรก</a></li>
                    <li><a href="Logbef_postjob.php" class="footer__link">ประกาศหาพนักงาน</a></li>
                    <li><a href="job_search.php" class="footer__link">หางาน</a></li>
                </ul>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">ข้อมูล</h3>
                <ul>
                    <li><a href="Contact_us.html" class="footer__link">ติดต่อเรา</a></li>
                    <li><a href="about.html" class="footer__link">เกี่ยวกับเรา</a></li>
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