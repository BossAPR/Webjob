<?php
      $con= mysqli_connect("localhost","root","","webjob") or die("Error: " . mysqli_error($con));
      mysqli_query($con, "SET NAMES 'utf8' ");
      error_reporting( error_reporting() & ~E_NOTICE );
      date_default_timezone_set('Asia/Bangkok');
?>

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
                        WHERE job_status = 'approved' and job_workers > got_workers
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
                <!--select name='job_category' id="job_category"
                    style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;"-->
                <select name="job_category" id="job_category" style="font-size :17px;">
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
                        <th></th>
                        <th>ชื่อตำแหน่ง</th>
                        <th>ลักษณะงาน</th>
                        <th>เงินเดือน</th>
                        <th>เวลาทำงาน</th>
                        <th>สถานที่ทำงาน</th>
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

    // สร้าง query เบื้องต้น
    //$query = "SELECT * FROM job_ad WHERE 1=1";
    $query = "SELECT * FROM job_ad WHERE job_status = 'approved' and job_workers > got_workers ";

    // ตัวแปรสำหรับค่าที่จะใช้ใน prepared statement
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

    // ผูกตัวแปรที่เตรียมไว้กับ statement
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($items = $result->fetch_assoc()) {
            $province_name = isset($province_map[$items['job_province']]) ? $province_map[$items['job_province']] : 'ไม่ระบุจังหวัด';
            ?>
                    <tr>
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
                    if ($items['job_type'] == 1) {
                        echo 'งานประจำ';
                    } elseif ($items['job_type'] == 2) {
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
                        // ใช้ number_format เพื่อคั่นหลักพัน และเพิ่มคำว่า "บาท"
                        echo number_format($items['job_salary'], 0, '.', ',') . ' บาท';
                        } else {
                        echo 'ไม่ระบุ'; 
                        }
                        ?></td>


                        <td><?= htmlspecialchars($items['job_time']); ?></td>
                        <!--td>
                            <!?php
                            if (isset($items['job_time']) && !empty($items['job_time'])) {
                            echo htmlspecialchars($items['job_time']) . ' น.';
                            } else {
                            echo 'ไม่ระบุ';
                            }
                            ?>
                        </td-->

                        <td><?= htmlspecialchars($province_name); ?></td>

                        <td><?= htmlspecialchars($items['job_category']); ?></td>

                        <td><?php echo time_elapsed_string($items['job_create_at']); ?></td>

                        <td>
                            <a href="job_detail.php?job_id=<?= htmlspecialchars($items['job_ad_id']); ?>"
                                class="button">รายละเอียดเพิ่มเติม</a>
                        </td>

                    </tr>
                    <?php
        }
    } else {
        ?>
                    <tr>
                        <td colspan="6">ไม่พบรายการ</td>
                    </tr>
                    <?php
    }

    // ปิด statement และการเชื่อมต่อ
    $stmt->close();
}

$conD->close();
?>
                </tbody>
            </table>
        </center>
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
<?php include('script.php');?>