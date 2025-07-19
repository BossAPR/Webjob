<?php
session_start();
require('connectdb.php');

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query เพื่อดึงรายละเอียดของงานตาม job_id
        $stmt = $conn->prepare("SELECT * FROM job_ad WHERE job_ad_id = :job_id");
        $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmt->execute();

        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$job) {
            echo "ไม่พบงานที่คุณต้องการดูรายละเอียด";
            exit;
        }

        // Query เพื่อนับจำนวนผู้สมัครงาน
        $stmtCount = $conn->prepare("SELECT COUNT(*) AS application_count FROM job_applications WHERE job_id = :job_id");
        $stmtCount->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $stmtCount->execute();
        $applicationCount = $stmtCount->fetch(PDO::FETCH_ASSOC)['application_count'];

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ไม่มีการระบุงานที่ต้องการดูรายละเอียด";
    exit;
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
        /* ... CSS Styles ... */
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
                    <li class="nav__item"><a href="Contact_us.html" class="nav__link">ศูนย์ช่วยเหลือ</a></li>
                    <a href="job_search.php"><button type="button" class="btn info"><b>บอร์ดหางาน</b></button></a>
                    <a href="Logbef_postjob.php"><button type="button" class="btn success"><b>ประกาศหางาน</b></button></a>
                </ul>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-menu'></i>
            </div>
        </nav>
    </header>

    <div class="job-detail-container">
        <div class="job-detail">
            <img src="assets/account_images/<?php echo htmlspecialchars($job['job_logo']); ?>" alt="Job Logo" class="job-logo">
            <div class="job-info">
                <h1 class="job-title"><?php echo htmlspecialchars($job['job_name']); ?></h1>
                <p><strong>ประเภทงาน:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
                <p><strong>จำนวนเงิน:</strong> <?php echo isset($job['job_salary']) ? htmlspecialchars($job['job_salary']) : 'ไม่ระบุ'; ?></p>
                <p><strong>เวลาทำงาน:</strong> <?php echo isset($job['job_time']) ? htmlspecialchars($job['job_time']) : 'ไม่ระบุ'; ?></p>
                <p><strong>สถานที่ทำงาน:</strong> <?php echo isset($job['job_location']) ? htmlspecialchars($job['job_location']) : 'ไม่ระบุ'; ?></p>
                <p><strong>รายละเอียด:</strong> <?php echo htmlspecialchars($job['job_detail']); ?></p>
                <p><strong>จำนวนผู้สมัคร:</strong> <?php echo $applicationCount; ?> คน</p> <!-- แสดงจำนวนผู้สมัคร -->
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
