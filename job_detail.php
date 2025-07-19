<?php
session_start();
require('connectdb.php');

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    try {
        $conn = new PDO("mysql:servername=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query เพื่อดึงรายละเอียดของงานตาม job_id
        $stmt = $conn->prepare("SELECT * FROM job_ad WHERE job_ad_id = :job_id");
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmt->execute();

        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$job) {
            // ถ้าไม่พบข้อมูลงาน ให้ redirect ไปยังหน้าอื่น หรือแสดงข้อความว่าไม่พบข้อมูลงาน
            echo "ไม่พบงานที่คุณต้องการดูรายละเอียด";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // ถ้าไม่มี job_id ให้ redirect ไปยังหน้าอื่น
    echo "ไม่มีการระบุงานที่ต้องการดูรายละเอียด";
    exit;
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


    <div class="job-detail-container">
        <div class="job-detail">
            <img src="assets/account_images/<?php echo htmlspecialchars($job['job_logo']); ?>" alt="Job Logo"
                class="job-logo">
            <div class="job-info">
                <h1 class="job-title"><?php echo htmlspecialchars($job['job_name']); ?></h1>
                <!--p><strong>ประเภทงาน:</strong> <!?php echo htmlspecialchars($job['job_type']); ?></p-->

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

                <!--p><strong>จำนวนเงิน:</strong>
                    <!?php echo isset($job['job_salary']) ? htmlspecialchars($job['job_salary']) : 'ไม่ระบุ'; ?></p>
                <p><strong>เวลาทำงาน:</strong>
                    <!?php echo isset($job['job_time']) ? htmlspecialchars($job['job_time']) : 'ไม่ระบุ'; ?></p-->

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
            </div>
        </div>

        <a href="job_search.php" class="back-link">กลับไปยังหน้าบอร์ดหางาน</a>
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
</body>

</html>