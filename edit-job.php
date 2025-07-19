<?php
session_start();
require('connectdb.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $result = $connect->query("SELECT * FROM job_ad WHERE job_ad_id = $id");
    $job = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['job_ad_id'];
    $name = $connect->real_escape_string($_POST['job_name']);
    $detail = $connect->real_escape_string($_POST['job_detail']);
    $type = (int)$_POST['job_type'];
    $workers = (int)$_POST['job_workers'];
    $salary = (int)$_POST['job_salary'];
    $time = $connect->real_escape_string($_POST['job_time']);
    $welfare = $connect->real_escape_string($_POST['job_welfare']);
    $sex = $connect->real_escape_string($_POST['sex']);
    $age_min = (int)$_POST['age_min'];
    $job_oldmax = $_POST['job_oldmax'];
    $qualification = $connect->real_escape_string($_POST['qualification']);
    $course = $connect->real_escape_string($_POST['course']);
    $experience_min = (int)$_POST['experience_min'];
    $location = $connect->real_escape_string($_POST['job_location']);
    $province = $connect->real_escape_string($_POST['job_province']);
    $district = $connect->real_escape_string($_POST['job_district']);
    $logo = $connect->real_escape_string($_POST['job_logo']);

    $sql = "UPDATE job_ad SET job_name='$name', job_detail='$detail', job_type=$type, job_workers=$workers, job_salary=$salary, 
            job_time='$time', job_welfare='$welfare', sex='$sex', age_min=$age_min, qualification='$qualification', 
            course='$course', experience_min=$experience_min, job_location='$location', job_province='$province', 
            job_district='$district', job_logo='$logo' ,job_oldmax = '$job_oldmax' ,job_status = 'Pending' , job_mail = 'pubpuang1811@gmail.com', account_id = '1'  WHERE job_ad_id=$id";

    if ($connect->query($sql) === TRUE) {
        header("Location: manage-jobs.php");
        exit();
    } else {
        echo "Error: " . $connect->error;
    }
}



if ($_POST) {
    if (isset($_FILES['upload'])) {
        $name_file = $_FILES['upload']['name'];
        $tmp_name = $_FILES['upload']['tmp_name'];
        $locate_img = "assets/account_images/";
        move_uploaded_file($tmp_name, $locate_img . $name_file);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <link rel="stylesheet" href="admin_styles.css">
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .form-container {
        width: 50%;
        margin: 0 auto;
        background-color: #f9f9f9;
        padding: 50px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <header class="admin-header">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="manage-users.php">จัดการผู้ใช้</a></li>
                <li><a href="manage-jobs.php">จัดการประกาศงาน</a></li>
                <li><a href="manage-contacts.php">จัดการการติดต่อจากลูกค้า</a></li>
                <li><a href="logout.php">ออกจากระบบ</a></li>
            </ul>
        </nav>
    </header><br>
    <div class="form-container">
        <h2>Edit Job</h2>
        <form method="post" action="edit-job.php">
            <input type="hidden" name="job_ad_id" value="<?= $job['job_ad_id'] ?>">

            <label for="job_name">ชื่อตำแหน่งงาน:</label>
            <input type="text" id="job_name" name="job_name" value="<?= htmlspecialchars($job['job_name']) ?>"
                placeholder="ชื่อตำแหน่งงาน" required>

            <label for="job_detail">รายละเอียด:</label>
            <textarea id="job_detail" name="job_detail" style="height: 150px; width: 950px;" placeholder="รายละเอียด"
                required><?= htmlspecialchars($job['job_detail']) ?></textarea>

            <label for="job_type">ประเภทงาน:</label>
            <select id="job_type" name="job_type" style="height: 30px; width: 935px">
                <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                <option value="<?= $job['job_type'] ?>">
                    <?php 
                       if ($job['job_type'] == 1) {
                       echo 'งานประจำ'; 
                       } elseif ($job['job_type'] == 2) {
                       echo 'งานพาร์ทไทม์'; 
                       } else {
                       echo 'เลือกประเภทการจ้างงาน'; // หากไม่มีข้อมูล
                       }
                    ?>
                </option>

                <!-- ตัวเลือกอื่นๆ -->
                <option value="1" <?php if ($job['job_type'] == 1) echo 'selected'; ?>>งานประจำ</option>
                <option value="2" <?php if ($job['job_type'] == 2) echo 'selected'; ?>>งานพาร์ทไทม์</option>
            </select>

            <label for="job_workers">จำนวนผู้สมัครงานที่ต้องการ:</label>
            <input type="number" id="job_workers" name="job_workers" value="<?= $job['job_workers'] ?>"
                placeholder="จำนวนผู้สมัครงานที่ต้องการ" required>

            <label for="job_salary">เงินเดือน:</label>
            <input type="number" id="job_salary" name="job_salary" value="<?= $job['job_salary'] ?>"
                placeholder="กรุณากรอกเป็นตัวเลข" required>

            <label for="job_time">เวลาทำงาน:</label>
            <select id="job_time" name="job_time" onchange="showCustomTime()" style="height: 30px; width: 935px">
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
            </div>

            <label for="job_welfare">สวัสดิการ:</label>
            <textarea id="job_welfare" name="job_welfare" style=" height: 150px; width: 950px;" 
                placeholder="เช่น ค่าน้ำมันรถ, ค่าเดินทาง, ค่าเบี้ยเลี้ยง"
                required><?= htmlspecialchars($job['job_welfare']) ?></textarea>

            <label for="job_sex">เพศ:</label>
            <select id="sex" name="sex" style="height: 30px; width: 935px">
                <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                <option value="<?= $job['sex'] ?>">
                    <?php 
                       if ($job['sex'] == 0) {
                       echo 'ชาย'; 
                       } elseif ($job['sex'] == 1) {
                       echo 'หญิง'; 
                       }elseif ($job['sex'] == 2) {
                        echo 'อื่นๆ'; 
                        } else {
                       echo 'เลือกเพศ'; // หากไม่มีข้อมูล
                       }
                    ?>
                </option>
                <!-- ตัวเลือกอื่นๆ -->
                <option value="0" <?php if ($job['sex'] == 0) echo 'selected'; ?>>ชาย</option>
                <option value="1" <?php if ($job['sex'] == 1) echo 'selected'; ?>>หญิง</option>
                <option value="2" <?php if ($job['sex'] == 2) echo 'selected'; ?>>อื่นๆ</option>
            </select>

            <label for="age_min">อายุขั้นต่ำที่รับ:</label>
            <input type="number" id="age_min" name="age_min" placeholder="อายุขั้นต่ำที่รับ"
                value="<?= $job['age_min'] ?>">

            <label for="job_oldmax">อายุมากสุดที่รับ:</label>
            <input type="number" id="job_oldmax" name="job_oldmax" placeholder="อายุมากสุดที่รับ"
                value="<?= $job['job_oldmax'] ?>">

            <label for="qualification">วุฒิการศึกษา:</label>
            <select id="qualification" name="qualification" style="height: 30px; width: 935px">
                <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                <option value="<?= $job['qualification'] ?>">
                    <?php 
        if ($job['qualification'] == 0) {
            echo 'ไม่มีการศึกษา'; 
        } elseif ($job['qualification'] == 1) {
            echo 'ประถมศึกษา'; 
        } elseif ($job['qualification'] == 2) {
            echo 'มัธยมศึกษาตอนต้น'; 
        } elseif ($job['qualification'] == 3) {
            echo 'มัธยมศึกษาตอนปลายหรือเทียบเท่า'; 
        } elseif ($job['qualification'] == 4) {
            echo 'อนุปริญญา'; 
        } elseif ($job['qualification'] == 5) {
            echo 'ปริญญาตรีขึ้นไปหรือเทียบเท่า'; 
        } else {
            echo 'เลือกวุฒิการศึกษา'; // หากไม่มีข้อมูล
        }
        ?>
                </option>

                <!-- ตัวเลือกอื่นๆ -->
                <option value="0" <?php if ($job['qualification'] == 0) echo 'selected'; ?>>ไม่มีวุฒิการศึกษา
                </option>
                <option value="1" <?php if ($job['qualification'] == 1) echo 'selected'; ?>>ประถมศึกษา</option>
                <option value="2" <?php if ($job['qualification'] == 2) echo 'selected'; ?>>มัธยมศึกษาตอนต้น
                </option>
                <option value="3" <?php if ($job['qualification'] == 3) echo 'selected'; ?>>
                    มัธยมศึกษาตอนปลายหรือเทียบเท่า</option>
                <option value="4" <?php if ($job['qualification'] == 4) echo 'selected'; ?>>อนุปริญญา</option>
                <option value="5" <?php if ($job['qualification'] == 5) echo 'selected'; ?>>
                    ปริญญาตรีขึ้นไปหรือเทียบเท่า</option>
            </select>

            <label for="job_course">หลักสูตรที่ต้องการ:</label>
            <select id="course" name="course" style="height: 30px; width: 935px">
                <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                <option value="<?= $job['course'] ?>">
                    <?php 
        if (!empty($job['course'])) {
            echo htmlspecialchars($job['course']);
        } else {
            echo 'เลือกหลักสูตร'; // หากไม่มีข้อมูล
        }
        ?>
                </option>
                <!-- ตัวเลือกหลักสูตร -->
                <option value="0" <?php if ($job['course'] == 0) echo 'selected'; ?>>ภาษาไทย</option>
                <option value="1" <?php if ($job['course'] == 1) echo 'selected'; ?>>ภาษาอังกฤษ</option>
                <option value="2" <?php if ($job['course'] == 2) echo 'selected'; ?>>ภาษาต่างประเทศ</option>
                <option value="3" <?php if ($job['course'] == 3) echo 'selected'; ?>>มนุษยศาสตร์</option>
                <option value="4" <?php if ($job['course'] == 4) echo 'selected'; ?>>สังคมศาสตร์</option>
                <option value="5" <?php if ($job['course'] == 5) echo 'selected'; ?>>จิตวิทยา</option>
                <option value="6" <?php if ($job['course'] == 6) echo 'selected'; ?>>คณิตศาสตร์</option>
                <option value="7" <?php if ($job['course'] == 7) echo 'selected'; ?>>ฟิสิกส์</option>
                <option value="8" <?php if ($job['course'] == 8) echo 'selected'; ?>>เคมี</option>
                <option value="9" <?php if ($job['course'] == 9) echo 'selected'; ?>>ชีววิทยา</option>
                <option value="10" <?php if ($job['course'] == 10) echo 'selected'; ?>>วิทยาศาสตร์สิ่งแวดล้อม
                </option>
                <option value="11" <?php if ($job['course'] == 11) echo 'selected'; ?>>วิศวกรรมไฟฟ้า</option>
                <option value="12" <?php if ($job['course'] == 12) echo 'selected'; ?>>วิศวกรรมเครื่องกล</option>
                <option value="13" <?php if ($job['course'] == 13) echo 'selected'; ?>>วิศวกรรมโยธา</option>
                <option value="14" <?php if ($job['course'] == 14) echo 'selected'; ?>>วิศวกรรมสารสนเทศ</option>
                <option value="15" <?php if ($job['course'] == 15) echo 'selected'; ?>>วิศวกรรมการบิน</option>
                <option value="16" <?php if ($job['course'] == 16) echo 'selected'; ?>>แพทยศาสตร์</option>
                <option value="17" <?php if ($job['course'] == 17) echo 'selected'; ?>>ทันตแพทยศาสตร์</option>
                <option value="18" <?php if ($job['course'] == 18) echo 'selected'; ?>>เภสัชศาสตร์</option>
                <option value="19" <?php if ($job['course'] == 19) echo 'selected'; ?>>สาธารณสุขศาสตร์</option>
                <option value="20" <?php if ($job['course'] == 20) echo 'selected'; ?>>การพยาบาล</option>
                <option value="21" <?php if ($job['course'] == 21) echo 'selected'; ?>>การจัดการ</option>
                <option value="22" <?php if ($job['course'] == 22) echo 'selected'; ?>>การตลาด</option>
                <option value="23" <?php if ($job['course'] == 23) echo 'selected'; ?>>การเงิน</option>
                <option value="24" <?php if ($job['course'] == 24) echo 'selected'; ?>>บัญชี</option>
                <option value="25" <?php if ($job['course'] == 25) echo 'selected'; ?>>เศรษฐศาสตร์</option>
                <option value="26" <?php if ($job['course'] == 26) echo 'selected'; ?>>การศึกษา</option>
                <option value="27" <?php if ($job['course'] == 27) echo 'selected'; ?>>จิตวิทยาการศึกษา</option>
                <option value="28" <?php if ($job['course'] == 28) echo 'selected'; ?>>การพัฒนาหลักสูตร</option>
                <option value="29" <?php if ($job['course'] == 29) echo 'selected'; ?>>ศิลปกรรม</option>
                <option value="30" <?php if ($job['course'] == 30) echo 'selected'; ?>>การออกแบบผลิตภัณฑ์</option>
                <option value="31" <?php if ($job['course'] == 31) echo 'selected'; ?>>การออกแบบกราฟิก</option>
                <option value="32" <?php if ($job['course'] == 32) echo 'selected'; ?>>สถาปัตยกรรม</option>
                <option value="33" <?php if ($job['course'] == 33) echo 'selected'; ?>>สื่อสารมวลชน</option>
                <option value="34" <?php if ($job['course'] == 34) echo 'selected'; ?>>การโฆษณา</option>
                <option value="35" <?php if ($job['course'] == 35) echo 'selected'; ?>>การประชาสัมพันธ์</option>
                <option value="36" <?php if ($job['course'] == 36) echo 'selected'; ?>>วิทยาการคอมพิวเตอร์</option>
                <option value="37" <?php if ($job['course'] == 37) echo 'selected'; ?>>เทคโนโลยีสารสนเทศ</option>
                <option value="38" <?php if ($job['course'] == 38) echo 'selected'; ?>>ความมั่นคงไซเบอร์</option>
            </select>

            <label for="job_exp">ประสบการณ์ (ปี):</label>
            <input type="number" id="experience_min" name="experience_min" placeholder="ประสบการณ์ (ปี)"
                value="<?= $job['experience_min'] ?>">

            <label for="job_location">สถานที่ทำงาน:</label>
            <input type="text" id="job_location" name="job_location"
                value="<?= htmlspecialchars($job['job_location']) ?>">

            <label for="job_province">จังหวัด:</label>
            <select id="job_province" name="job_province" style="height: 30px; width: 935px">
                <!-- แสดงค่าเดิมที่มาจากฐานข้อมูล -->
                <option value="<?= $job['job_province'] ?>">
                    <?php 
        if (!empty($job['job_province'])) {
            echo htmlspecialchars($job['job_province']);
        } else {
            echo 'เลือกจังหวัด'; // หากไม่มีข้อมูล
        }
        ?>
                </option>
                <!-- ตัวเลือกจังหวัด -->
                <option value="1" <?php if ($job['job_province'] == 1) echo 'selected'; ?>>กรุงเทพมหานคร</option>
                <option value="2" <?php if ($job['job_province'] == 2) echo 'selected'; ?>>สมุทรปราการ</option>
                <option value="3" <?php if ($job['job_province'] == 3) echo 'selected'; ?>>นนทบุรี</option>
                <option value="4" <?php if ($job['job_province'] == 4) echo 'selected'; ?>>ปทุมธานี</option>
                <option value="5" <?php if ($job['job_province'] == 5) echo 'selected'; ?>>พระนครศรีอยุธยา</option>
                <option value="6" <?php if ($job['job_province'] == 6) echo 'selected'; ?>>อ่างทอง</option>
                <option value="7" <?php if ($job['job_province'] == 7) echo 'selected'; ?>>ลพบุรี</option>
                <option value="8" <?php if ($job['job_province'] == 8) echo 'selected'; ?>>สิงห์บุรี</option>
                <option value="9" <?php if ($job['job_province'] == 9) echo 'selected'; ?>>ชัยนาท</option>
                <option value="10" <?php if ($job['job_province'] == 10) echo 'selected'; ?>>สระบุรี</option>
                <option value="11" <?php if ($job['job_province'] == 11) echo 'selected'; ?>>ชลบุรี</option>
                <option value="12" <?php if ($job['job_province'] == 12) echo 'selected'; ?>>ระยอง</option>
                <option value="13" <?php if ($job['job_province'] == 13) echo 'selected'; ?>>จันทบุรี</option>
                <option value="14" <?php if ($job['job_province'] == 14) echo 'selected'; ?>>ตราด</option>
                <option value="15" <?php if ($job['job_province'] == 15) echo 'selected'; ?>>ฉะเชิงเทรา</option>
                <option value="16" <?php if ($job['job_province'] == 16) echo 'selected'; ?>>ปราจีนบุรี</option>
                <option value="17" <?php if ($job['job_province'] == 17) echo 'selected'; ?>>นครนายก</option>
                <option value="18" <?php if ($job['job_province'] == 18) echo 'selected'; ?>>สระแก้ว</option>
                <option value="19" <?php if ($job['job_province'] == 19) echo 'selected'; ?>>นครราชสีมา</option>
                <option value="20" <?php if ($job['job_province'] == 20) echo 'selected'; ?>>บุรีรัมย์</option>
                <option value="21" <?php if ($job['job_province'] == 21) echo 'selected'; ?>>สุรินทร์</option>
                <option value="22" <?php if ($job['job_province'] == 22) echo 'selected'; ?>>ศรีสะเกษ</option>
                <option value="23" <?php if ($job['job_province'] == 23) echo 'selected'; ?>>อุบลราชธานี</option>
                <option value="24" <?php if ($job['job_province'] == 24) echo 'selected'; ?>>ยโสธร</option>
                <option value="25" <?php if ($job['job_province'] == 25) echo 'selected'; ?>>ชัยภูมิ</option>
                <option value="26" <?php if ($job['job_province'] == 26) echo 'selected'; ?>>อำนาจเจริญ</option>
                <option value="27" <?php if ($job['job_province'] == 27) echo 'selected'; ?>>หนองบัวลำภู</option>
                <option value="28" <?php if ($job['job_province'] == 28) echo 'selected'; ?>>ขอนแก่น</option>
                <option value="29" <?php if ($job['job_province'] == 29) echo 'selected'; ?>>อุดรธานี</option>
                <option value="30" <?php if ($job['job_province'] == 30) echo 'selected'; ?>>เลย</option>
                <option value="31" <?php if ($job['job_province'] == 31) echo 'selected'; ?>>หนองคาย</option>
                <option value="32" <?php if ($job['job_province'] == 32) echo 'selected'; ?>>มหาสารคาม</option>
                <option value="33" <?php if ($job['job_province'] == 33) echo 'selected'; ?>>ร้อยเอ็ด</option>
                <option value="34" <?php if ($job['job_province'] == 34) echo 'selected'; ?>>กาฬสินธุ์</option>
                <option value="35" <?php if ($job['job_province'] == 35) echo 'selected'; ?>>สกลนคร</option>
                <option value="36" <?php if ($job['job_province'] == 36) echo 'selected'; ?>>นครพนม</option>
                <option value="37" <?php if ($job['job_province'] == 37) echo 'selected'; ?>>มุกดาหาร</option>
                <option value="38" <?php if ($job['job_province'] == 38) echo 'selected'; ?>>เชียงใหม่</option>
                <option value="39" <?php if ($job['job_province'] == 39) echo 'selected'; ?>>ลำพูน</option>
                <option value="40" <?php if ($job['job_province'] == 40) echo 'selected'; ?>>ลำปาง</option>
                <option value="41" <?php if ($job['job_province'] == 41) echo 'selected'; ?>>อุตรดิตถ์</option>
                <option value="42" <?php if ($job['job_province'] == 42) echo 'selected'; ?>>แพร่</option>
                <option value="43" <?php if ($job['job_province'] == 43) echo 'selected'; ?>>น่าน</option>
                <option value="44" <?php if ($job['job_province'] == 44) echo 'selected'; ?>>พะเยา</option>
                <option value="45" <?php if ($job['job_province'] == 45) echo 'selected'; ?>>เชียงราย</option>
                <option value="46" <?php if ($job['job_province'] == 46) echo 'selected'; ?>>แม่ฮ่องสอน</option>
                <option value="47" <?php if ($job['job_province'] == 47) echo 'selected'; ?>>นครสวรรค์</option>
                <option value="48" <?php if ($job['job_province'] == 48) echo 'selected'; ?>>อุทัยธานี</option>
                <option value="49" <?php if ($job['job_province'] == 49) echo 'selected'; ?>>กำแพงเพชร</option>
                <option value="50" <?php if ($job['job_province'] == 50) echo 'selected'; ?>>ตาก</option>
                <option value="51" <?php if ($job['job_province'] == 51) echo 'selected'; ?>>สุโขทัย</option>
                <option value="52" <?php if ($job['job_province'] == 52) echo 'selected'; ?>>พิษณุโลก</option>
                <option value="53" <?php if ($job['job_province'] == 53) echo 'selected'; ?>>พิจิตร</option>
                <option value="54" <?php if ($job['job_province'] == 54) echo 'selected'; ?>>เพชรบูรณ์</option>
                <option value="55" <?php if ($job['job_province'] == 55) echo 'selected'; ?>>ราชบุรี</option>
                <option value="56" <?php if ($job['job_province'] == 56) echo 'selected'; ?>>กาญจนบุรี</option>
                <option value="57" <?php if ($job['job_province'] == 57) echo 'selected'; ?>>สุพรรณบุรี</option>
                <option value="58" <?php if ($job['job_province'] == 58) echo 'selected'; ?>>นครปฐม</option>
                <option value="59" <?php if ($job['job_province'] == 59) echo 'selected'; ?>>สมุทรสาคร</option>
                <option value="60" <?php if ($job['job_province'] == 60) echo 'selected'; ?>>สมุทรสงคราม</option>
                <option value="61" <?php if ($job['job_province'] == 61) echo 'selected'; ?>>เพชรบุรี</option>
                <option value="62" <?php if ($job['job_province'] == 62) echo 'selected'; ?>>ประจวบคีรีขันธ์
                </option>
                <option value="63" <?php if ($job['job_province'] == 63) echo 'selected'; ?>>นครศรีธรรมราช</option>
                <option value="64" <?php if ($job['job_province'] == 64) echo 'selected'; ?>>กระบี่</option>
                <option value="65" <?php if ($job['job_province'] == 65) echo 'selected'; ?>>พังงา</option>
                <option value="66" <?php if ($job['job_province'] == 66) echo 'selected'; ?>>ภูเก็ต</option>
                <option value="67" <?php if ($job['job_province'] == 67) echo 'selected'; ?>>สุราษฎร์ธานี</option>
                <option value="68" <?php if ($job['job_province'] == 68) echo 'selected'; ?>>ระนอง</option>
                <option value="69" <?php if ($job['job_province'] == 69) echo 'selected'; ?>>ชุมพร</option>
                <option value="70" <?php if ($job['job_province'] == 70) echo 'selected'; ?>>สงขลา</option>
                <option value="71" <?php if ($job['job_province'] == 71) echo 'selected'; ?>>สตูล</option>
                <option value="72" <?php if ($job['job_province'] == 72) echo 'selected'; ?>>ตรัง</option>
                <option value="73" <?php if ($job['job_province'] == 73) echo 'selected'; ?>>พัทลุง</option>
                <option value="74" <?php if ($job['job_province'] == 74) echo 'selected'; ?>>ปัตตานี</option>
                <option value="75" <?php if ($job['job_province'] == 75) echo 'selected'; ?>>ยะลา</option>
                <option value="76" <?php if ($job['job_province'] == 76) echo 'selected'; ?>>นราธิวาส</option>
                <option value="77" <?php if ($job['job_province'] == 77) echo 'selected'; ?>>บึงกาฬ</option>
            </select>

            <label for="job_district">อำเภอ:</label>
            <input type="text" id="job_district" name="job_district"
                value="<?= htmlspecialchars($job['job_district']) ?>">

            <label for="job_logo">โลโก้บริษัท:</label>
            <input type="file" id="job_logo" name="job_logo" accept="image/*" style="height: 30px; width: 935px">
            <input type="hidden" name="old_job_logo" value="<?= htmlspecialchars($job['job_logo']) ?>">

            <input type="submit" value="แก้ไขงาน">
        </form>
    </div>
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