<?php
session_start();
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
    <link rel="stylesheet" href="admin_styles.css">
</head>

<body>
    <style>
    body {
        display: flex;
        /* ทำให้ body เป็น flex container */
        flex-direction: column;
        /* วาง layout ในแนวตั้ง */
        /*height: 100vh;  ทำให้สูงเต็มจอ */
        margin: 0;
        /* เอา margin ออก */
    }

    .admin-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    max-width: 1400px; /* กำหนดความกว้างสูงสุด */
    margin: 0 auto;
    width: 100%; /* ให้มีความกว้างเต็มที่ */
    box-sizing: border-box; /* ใช้ border-box สำหรับการคำนวณขนาด */
}

    .main-content {
        flex: 1;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        width: 100%;
        /* ปรับให้ใช้ความกว้าง 100% */

    }
.main-content {
    flex: 1;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    width: 100%; /* ให้ main-content มีความกว้าง 100% */
    box-sizing: border-box; /* ใช้ border-box สำหรับการคำนวณขนาด */
}


    /* ปรับขนาดของตาราง */
    table {
        width: 100%;
        /* ให้ตารางเต็มความกว้าง */
        border-collapse: collapse;
        /* รวมขอบของตาราง */
    }

    /* ปรับขนาดของเซลล์ตาราง */
    th,
    td {
        padding: 10px;
        /* เพิ่มระยะห่างภายในเซลล์ */
        text-align: center;
        /* จัดข้อความไปทางซ้าย */
        border: 1px solid #ddd;
        /* ขอบของเซลล์ */
    }

    /* เพิ่มสีพื้นหลังให้กับ header ของตาราง */
    thead {
        background-color: #f2f2f2;
        /* สีพื้นหลังของหัวตาราง */
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
        /* สีพื้นหลังของแถวคู่ */
    }

    tbody tr:hover {
        background-color: #f1f1f1;
        /* สีพื้นหลังเมื่อชี้ที่แถว */
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

    /* ปรับปรุงสไตล์ของปุ่ม */
    .action-btn {
        display: inline-block;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 5px;
        text-decoration: none;
        margin-right: 5px;
        /* เพิ่มระยะห่างระหว่างปุ่ม */
    }
    </style>
    <div class="admin-container">
        <header class="admin-header">
            <h1>รายละเอียดผู้สมัคร</h1>
            <nav>
                <ul>
                    <li><a href="manage-jobs.php">ย้อนกลับไปหน้าจัดการงาน</a></li>
                </ul>
            </nav>
        </header>

        <section class="main-content">
            <h2>ชื่องาน: <?= $job_result['job_name'] ?></h2>
            <h3>บริษัท: <?= $job_result['company_name'] ?></h3>
            <h4>จำนวนผู้สมัคร: <?= $applicants_result->num_rows ?></h4>

            <?php if ($applicants_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <!--th>ID ผู้สมัคร</th-->
                        <th>ชื่อผู้ใช้</th>
                        <th>วันที่สมัคร</th>
                        <th>ความเหมาะสม</th>
                        <!--th>ประวัติย่อ</th-->
                        <th>เพศ</th>
                        <th>อายุ</th>
                        <th>วุฒิการศึกษา</th>
                        <th>สาขาวิชา</th>
                        <th>ประสบการณ์</th>
                        <th>วันที่เริ่มงาน</th>
                        <th>ประเภทการจ้างที่ต้องการ</th>
                        <th>สถานที่ที่ต้องการ</th>
                        <!--th>สิทธิการทำงาน</th-->
                        <th>เงินเดือนที่ต้องการ</th>
                        <!--th>ประเภทเงินเดือน</th-->
                        <th>งานที่สนใจ</th>
                        <th>ผ่านเกณฑ์ทหาร</th>
                        <!--th>ประเภทการทำงาน</th-->
                        <!--th>ติดต่อผู้สมัคร</th-->
                    </tr>
                </thead>

                <?php 
                // Array สำหรับการศึกษา
                $qualifications = [
                0 => 'ไม่มีการศึกษา',
                1 => 'ประถมศึกษา',
                2 => 'มัธยมศึกษาตอนต้น',
                3 => 'มัธยมศึกษาตอนปลายหรือเทียบเท่า',
                4 => 'อนุปริญญา',
                5 => 'ปริญญาตรีขึ้นไปหรือเทียบเท่า'
                ];

                // Array สำหรับสาขาวิชา
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

                $sexs= [ 
                0 => 'ชาย',
                1 => 'หญิง',
                2 => 'อื่นๆ'];

                ?>

                <tbody>
                    <?php while ($applicant = $applicants_result->fetch_assoc()): ?>
                    <tr>
                        <!--td><!?= $applicant['application_id'] ?></td-->
                        <td><?= $applicant['account_name'] ?></td>
                        <td><?= $applicant['application_date'] ?></td>
                        <td><?= $applicant['Suitability'] == 0 ? 'ไม่พบค่าความเหมาะสม' : $applicant['Suitability'] . '%'; ?>
                        </td>

                        <!--td><!?= $applicant['resume'] ?></td-->

                        <td><?= isset($sexs[$applicant['sex']]) ? $sexs[$applicant['sex']] : 'ไม่ระบุ' ?></td>
                        <td><?= $applicant['old'] ?></td>
                        <td><?= isset($qualifications[$applicant['qualification']]) ? $qualifications[$applicant['qualification']] : 'ไม่ระบุ' ?>
                        </td>
                        <td><?= isset($courses[$applicant['course']]) ? $courses[$applicant['course']] : 'ไม่ระบุ' ?>
                        </td>
                        <td><?= $applicant['experience'] ?></td>
                        <td><?= $applicant['start_date'] ?></td>
                        <td><?= ($applicant['employment_type'] == 1) ? 'งานประจำ' : 'งานพาร์ทไทม์'; ?></td>
                        <td>
                            <?php 
                            // สร้างตัวเลือกสถานที่ที่ต้องการ
                            $locations = [
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
                                77 => 'บึงกาฬ',
                            ];
                            echo isset($locations[$applicant['preferred_location']]) ? $locations[$applicant['preferred_location']] : 'ไม่ระบุ'; 
                            ?>
                        </td>
                        <!--td><!?= $applicant['work_eligibility'] ?></td-->
                        <td><?= $applicant['expected_salary'] ?></td>
                        <!--td><!?= $applicant['salary_type'] ?></td-->
                        <td><?= $applicant['interested_job_type'] ?></td>
                        <td><?= $applicant['conscription'] ?></td>
                        <!--td><!?= $applicant['work_type'] ?></td-->
                        <!--td><a href="send-email-view-applicants.php?application_id=<!?= $applicant['application_id'] ?>" class="action-btn callback-btn">ติดต่อ</a></td-->

                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>ยังไม่มีผู้สมัครในงานนี้</p>
            <?php endif; ?>
        </section>
    </div>
</body>

</html>

<?php
$stmt->close();
$connect->close();
?>