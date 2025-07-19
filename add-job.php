<?php
session_start();
require('connectdb.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $company_name = $connect->real_escape_string($_POST['company_name']);
    $job_category = $connect->real_escape_string($_POST['job_category']);
    $verify_company = $connect->real_escape_string($_POST['verify_company']);
    
    $name = $connect->real_escape_string($_POST['job_name']);
    $detail = $connect->real_escape_string($_POST['job_detail']);
    $type = (int)$_POST['job_type'];
    $workers = (int)$_POST['job_workers'];
    $salary = (int)$_POST['job_salary'];
    $time = $connect->real_escape_string($_POST['job_time']);
    $welfare = $connect->real_escape_string($_POST['job_welfare']);

    $sex = $connect->real_escape_string($_POST['sex']);
    $age_min = (int)$_POST['age_min'];
    $age_max = $_POST['age_max'];
    $qualification = $connect->real_escape_string($_POST['qualification']);
    $course = $connect->real_escape_string($_POST['course']);
    $experience_min = (int)$_POST['experience_min'];
    $location = $connect->real_escape_string($_POST['job_location']);
    $province = $connect->real_escape_string($_POST['job_province']);
    $district = $connect->real_escape_string($_POST['job_district']);
    $logo = $connect->real_escape_string($_POST['job_logo']);

    //$job_expire_at = date('Y-m-d H:i:s', strtotime('+7 days'));
    //$job_expire_at = $connect->real_escape_string($_POST['job_expire_at']);

    // Handle expiration period
    if ($_POST['job_expire_at'] === '1_week') {
        $job_expire_at = date('Y-m-d', strtotime('+1 week'));
    } elseif ($_POST['job_expire_at'] === '1_month') {
        $job_expire_at = date('Y-m-d', strtotime('+1 month'));
    } elseif ($_POST['job_expire_at'] === '3_month') {
        $job_expire_at = date('Y-m-d', strtotime('+3 months'));
    } else {
        $job_expire_at = $_POST['job_expire_at']; // If a specific date is selected
    }
    
    $sql = "INSERT INTO job_ad (job_name, job_detail, job_type, job_workers, job_salary, job_time, job_welfare, 
    sex, age_min, age_max, qualification, course, experience_min, job_location, job_province, job_district, job_logo
    ,job_status,job_mail,account_id,job_create_at,company_name,job_category,verify_company,job_expire_at) 
            VALUES ('$name', '$detail', $type, $workers, $salary, '$time', '$welfare', 
            '$sex', $age_min, $age_max,'$qualification', '$course', $experience_min, '$location', '$province', '$district', '$logo'
            ,'Pending','pubpuang1811@gmail.com','1',now(),'$company_name','$job_category','$verify_company', '$job_expire_at')";

    if ($connect->query($sql) === TRUE) {
        header("Location: manage-jobs.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $connect->error;
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

if ($_POST) {
    if (isset($_FILES['uploadverify'])) {
        $name_file2 = $_FILES['uploadverify']['name'];
        $tmp_name = $_FILES['uploadverify']['tmp_name'];
        $locate_img = "verifycompany/";
        move_uploaded_file($tmp_name, $locate_img . $name_file2);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job</title>
    <link rel="stylesheet" href="admin_styles.css">
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .form-container {
        width: 50%;
        margin: 0 auto;
        background-color: #f9f9f9;
        padding: 20px;
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

    <?php
    $sql_provinces = "SELECT * FROM provinces";
    $query = mysqli_query($connect, $sql_provinces);
        ?>
    <!--========== SCROLL TOP ==========-->
    <a href="#" class="scrolltop" id="scroll-top">
        <i class='bx bx-chevron-up scrolltop__icon'></i>
    </a>


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
        <h2>Add Job</h2>
        <form method="post" action="add-job.php">

            <label style="font-size :18px;">ชื่อบริษัท :</label>
            <input type="text" id="company_name" name="company_name" placeholder="ชื่อบริษัท" required>

            <label style="font-size :18px;">ชื่อตำแหน่งงาน :</label>
            <input type="text" id="job_name" name="job_name" placeholder="ชื่อตำแหน่งงาน" required>

            <label for="job_detail">รายละเอียด:</label>
            <input type="text" id="job_detail" name="job_detail" placeholder="รายละเอียด" required>

            <label for="job_type">ประเภทงาน:</label>
            <select name='job_type' id="job_type"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;"
                required>
                <option disabled selected value> -- ประเภทงาน -- </option>
                <option value="1">งานประจำ</option>
                <option value="2">งานพาร์ทไทม์</option>
            </select>

            <label for="job_category">หมวดหมู่:</label>
            <select name='job_category' id="job_category"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;"
                required>
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
                <option value="วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ">วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ</option>
            </select>

            <label for="job_workers">จำนวนผู้สมัครงานที่ต้องการ:</label>
            <input type="number" id="job_workers" name="job_workers" placeholder="จำนวนผู้สมัครงานที่ต้องการ" required>

            <label for="job_salary">เงินเดือน:</label>
            <input type="number" id="job_salary" name="job_salary" placeholder="กรุณากรอกเป็นตัวเลข" required>

            <label for="job_time">เวลาทำงาน:</label>
            <select id="job_time" name="job_time" onchange="showCustomTime()"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;">
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

            <label for="job_welfare">สวัสดิการ:</label>
            <input type="text" id="job_welfare" name="job_welfare"
                placeholder="เช่น ค่าน้ำมันรถ, ค่าเดินทาง, ค่าเบี้ยเลี้ยง" required>

            <label for="job_sex">เพศ:</label>
            <select name="sex" id="box"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;"
                required>
                <option disabled selected value> -- เลือกเพศ -- </option>
                <option value="0">ชาย</option>
                <option value="1">หญิง</option>
                <option value="2">อื่นๆ</option>
            </select>

            <label style="font-size :18px;">อายุขั้นต่ำที่รับ :</label>
            <input type="int" name="age_min" id="age_min"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;"
                placeholder="อายุขั้นต่ำที่รับ" required><br><br>

            <label style="font-size :18px;">อายุมากสุดที่รับ :</label>
            <input type="int" name="age_max" id="age_max"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;"
                placeholder="อายุมากสุดที่รับ" required><br><br>

            <label for="qualification">คุณสมบัติ:</label>
            <select id="box" name="qualification"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;">
                <option disabled selected value> -- เลือกวุฒิการศึกษา -- </option>
                <option value="0">ไม่มีวุฒิการศึกษา</option>
                <option value="1">ประถมศึกษา</option>
                <option value="2">มัธยมศึกษาตอนต้น</option>
                <option value="3">มัธยมศึกษาตอนปลายหรือเทียบเท่า</option>
                <option value="4">อนุปริญญา</option>
                <option value="5">ปริญญาตรีขึ้นไปหรือเทียบเท่า</option>
            </select>

            <label for="course">หลักสูตรที่ต้องการ:</label>
            <select name="course" id="box"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;"
                required>
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

            <label for="job_exp">ประสบการณ์ (ปี):</label>
            <input type="int" name="experience_min" id="box"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;""
                    placeholder=" ประสบการณ์ทำงาน(ปี)" required><br><br>

            <label for="job_location">สถานที่ทำงาน:</label>
            <input type="text" id="job_location" name="job_location" placeholder="ที่อยู่ของสถานที่ทำงาน" required>

            <label for="job_province">จังหวัด:</label>
            <select name="job_province" id="provinces"
                style="font-size :17px; width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ccc;"
                required>
                <option value="" selected disabled>-กรุณาเลือกจังหวัด-</option>
                <?php foreach ($query as $value) { ?>
                <option value="<?=$value['id']?>"><?=$value['name_th']?></option>
                <?php } ?>
            </select>

            <label for="job_district">อำเภอ:</label>
            <input type="text" name="job_district" id="box" style="font-size :17px;" placeholder="อำเภอ/เขต"
                required><br><br>

            <label for="job_expire_at">วันหมดอายุของประกาศ:</label>
            <input type="datetime-local" name="job_expire_at" id="box" style="font-size :17px;" placeholder="วันหมดอายุของประกาศ"
                ><br><br>

                <label for="job_expire_at">หรือเลือกเป็นช่วงวันหมดอายุของประกาศงาน:</label>
                <select id="job_expire_at" name="job_expire_at">
                    <option value="1_week">1 อาทิตย์</option>
                    <option value="1_month">1 เดือน</option>
                    <option value="3_month">3 เดือน</option>
                </select><br><br>

            <label for="job_logo">โลโก้บริษัท:</label>
            <input type="file" name="upload" />
            <br><br>

            <label for="verify_company">ไฟล์เลขนิติบุคคลของบริษัท:</label>
            <input type="file" name="uploadverify" />
            
            <br><br>
            <input type="submit" value="เพิ่มงาน"><br><br>
        </form>
    </div>

</body>

</html>