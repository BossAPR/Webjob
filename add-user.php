<?php
session_start();
require('connectdb.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $account_realpassword = $_POST['password'];

    $length = random_int(1, 128);
    $account_salt = bin2hex(random_bytes($length));
    $account_password1 = $account_realpassword . $account_salt;

    $algo = PASSWORD_ARGON2ID;
    $options = [
        'cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
        'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
        'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
    ];

    $account_password = password_hash($account_password1, $algo, $options);
    $account_images = 'default_images_account.jpg';

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    //$gender = $_POST['gender'];
    $addresses = $_POST['addresses'];
    $phone_numbers = $_POST['phone_numbers'];

    //$resume = $_POST['resume'];
    //$skills = $_POST['skills'];
    
    
    $start_date = $_POST['start_date'];
    $employment_type = $_POST['employment_type'];
    $preferred_location = $_POST['preferred_location'];
    $work_eligibility = $_POST['work_eligibility'];
    $expected_salary = $_POST['expected_salary'];
    $salary_type = $_POST['salary_type'];
    $interested_job_type = $_POST['interested_job_type'];
    $conscription = $_POST['conscription'];
    $work_type = $_POST['work_type'];
    


    $sex = $_POST['sex'];
    $qualification = $_POST['qualification'];

    
    $experience = $_POST['experience'];
    
    $old = $_POST['old'];
    $course = $_POST['course'];

    $query = "INSERT INTO users_account (account_name, account_email, account_password, account_realpassword, account_role, first_name, last_name, birthday, addresses, phone_numbers, account_images, account_salt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?, ?)";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssssssss', $name, $email, $account_password, $account_realpassword, $role, $first_name, $last_name, $birthday, $addresses, $phone_numbers, $account_images, $account_salt);
    mysqli_stmt_execute($stmt);

    $account_id = mysqli_insert_id($connect);

    $query_applicant = "INSERT INTO applicant (account_id, experience, qualification, start_date, employment_type, preferred_location, work_eligibility, 
    expected_salary, salary_type, interested_job_type, conscription, work_type, old , sex, course) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_applicant = mysqli_prepare($connect, $query_applicant);
    mysqli_stmt_bind_param($stmt_applicant, 'isssssiisssssss', $account_id,  $experience, $qualification, $start_date,
     $employment_type, $preferred_location, $work_eligibility, $expected_salary, $salary_type, $interested_job_type, $conscription, $work_type, $old, $sex, $course);
    mysqli_stmt_execute($stmt_applicant);

    header('Location: manage-users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้ใช้ใหม่</title>
    <link rel="stylesheet" href="admin_styles.css">
    <style>
    .form-container {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 20px auto;
    }

    h1 {
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select,
    textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    button {
        background-color: #28a745;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    button:hover {
        background-color: #218838;
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
    </header>

    <div class="form-container">
        <h1>เพิ่มผู้ใช้ใหม่</h1>
        <form action="add-user.php" method="POST">
            <label for="name">ชื่อผู้ใช้ (Users_ID):</label>
            <input type="text" id="name" name="name" placeholder="Users_ID" required>

            <label for="first_name">ชื่อจริง:</label>
            <input type="text" id="first_name" name="first_name" placeholder="first_name" required>

            <label for="last_name">นามสกุล:</label>
            <input type="text" id="last_name" name="last_name" placeholder="last_name" required>

            <label for="email">อีเมล:</label>
            <input type="email" id="email" name="email" placeholder="Example@gmail.com" required>

            <label for="role">บทบาท:</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" placeholder="******" required>

            <label for="birthday">วันเกิด:</label>
            <input type="date" id="birthday" name="birthday" style="width: 600px; height: 30px;" required>

            <label for="sex">เพศ:</label>
            <select id="sex" name="sex" required>
                <option value="0">ชาย</option>
                <option value="1">หญิง</option>
                <option value="2">อื่น ๆ</option>
            </select>

            <label for="addresses">ที่อยู่:</label>
            <input type="text" id="addresses" name="addresses" placeholder="กรุณาใส่ที่อยู่" required>

            <label for="phone_numbers">เบอร์โทรศัพท์:</label>
            <input type="text" id="phone_numbers" name="phone_numbers" placeholder="กรุณาใส่ที่เบอร์โทรศัพท์" required>

            <label for="experience">ประสบการณ์:</label>
            <textarea id="experience" name="experience" placeholder="กรุณาใส่ประสบการณ์ทำงานเป็นปี" required></textarea>

            <label for="qualification">วุฒิการศึกษา:</label>
            <select id="qualification" name="qualification">
                <option value="">เลือกประเภทวุฒิ</option>
                <option value="0">ไม่มีการศึกษา</option>
                <option value="1">ประถมศึกษา</option>
                <option value="2">มัธยมศึกษาตอนต้น</option>
                <option value="3">มัธยมศึกษาตอนปลายหรือเทียบเท่า
                </option>
                <option value="4">อนุปริญญา</option>
                <option value="5">ปริญญาตรีขึ้นไปหรือเทียบเท่า</option>
            </select>

            <label for="course">สาขาจบ</label>
            <select id="course" name="course">
            <option value="" selected>เลือกสาขาจบ</option>
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

            <label for="start_date">วันที่เริ่มทำงาน:</label>
            <select id="start_date" name="start_date">
                <option value="">เลือกวันที่เริ่มงาน</option>
                <option value="ทันที">ทันที</option>
                <option value="2 สัปดาห์">2 สัปดาห์</option>
                <option value="4 สัปดาห์">4 สัปดาห์</option>
                <option value="8 สัปดาห์">8 สัปดาห์</option>
                <option value="12+ สัปดาห์">12+ สัปดาห์</option>
            </select>

            <label for="employment_type">ประเภทการจ้างงาน:</label>
            <select id="employment_type" name="employment_type">
                <option value="">เลือกประเภทการจ้างงาน</option>
                <option value="1">งานประจำ</option>
                <option value="2">งานพาร์ทไทม์</option>
            </select>

            <label for="preferred_location">สถานที่ทำงานที่ต้องการ:</label>
            <select id="preferred_location" name="preferred_location">
                <option value="">เลือกจังหวัด</option>
                <option value="1">กรุงเทพมหานคร</option>
                <option value="2">กระบี่</option>
                <option value="3">กาญจนบุรี</option>
                <option value="4">กาฬสินธุ์</option>
                <option value="5">กำแพงเพชร</option>
                <option value="6">ขอนแก่น</option>
                <option value="7">จันทบุรี</option>
                <option value="8">ฉะเชิงเทรา</option>
                <option value="9">ชัยนาท</option>
                <option value="10">ชัยภูมิ</option>
                <option value="11">ชุมพร</option>
                <option value="12">เชียงราย</option>
                <option value="13">เชียงใหม่</option>
                <option value="14">ตรัง</option>
                <option value="15">ตราด</option>
                <option value="16">ตาก</option>
                <option value="17">นครนายก</option>
                <option value="18">นครปฐม</option>
                <option value="19">นครพนม</option>
                <option value="20">นครราชสีมา</option>
                <option value="21">นครศรีธรรมราช</option>
                <option value="22">นราธิวาส</option>
                <option value="23">น่าน</option>
                <option value="24">บึงกาฬ</option>
                <option value="25">บุรีรัมย์</option>
                <option value="26">ปทุมธานี</option>
                <option value="27">ประจวบคีรีขันธ์</option>
                <option value="28">ปราจีนบุรี</option>
                <option value="29">ปัตตานี</option>
                <option value="30">พะเยา</option>
                <option value="31">พระนครศรีอยุธยา</option>
                <option value="32">พังงา</option>
                <option value="33">พัทลุง</option>
                <option value="34">เพชรบุรี</option>
                <option value="35">เพชรบูรณ์</option>
                <option value="36">ศรีสะเกษ</option>
                <option value="37">สกลนคร</option>
                <option value="38">สงขลา</option>
                <option value="39">สมุทรปราการ</option>
                <option value="40">สมุทรสาคร</option>
                <option value="41">สระบุรี</option>
                <option value="42">สระแก้ว</option>
                <option value="43">สุพรรณบุรี</option>
                <option value="44">สุราษฎร์ธานี</option>
                <option value="45">สุรินทร์</option>
                <option value="46">อ่างทอง</option>
                <option value="47">อุดรธานี</option>
                <option value="48">อุตรดิตถ์</option>
                <option value="49">อุทัยธานี</option>
                <option value="50">ยะลา</option>
                <option value="51">ลำปาง</option>
                <option value="52">ลำพูน</option>
                <option value="53">เลย</option>
                <option value="54">หนองคาย</option>
                <option value="55">หนองบัวลำภู</option>
                <option value="56">อำนาจเจริญ</option>
                <option value="57">บึงกาฬ</option>
                <option value="58">เชียงใหม่</option>

            </select>

            <label for="work_eligibility">คุณสมบัติการทำงาน:</label>
            <select id="work_eligibility" name="work_eligibility">
                <option value="">เลือกสิทธิการทำงาน</option>
                <option value="พลเมืองไทย/ผู้พำนักถาวร">พลเมืองไทย/ผู้พำนักถาวร</option>
                <option value="วีซ่าชั่วคราวที่มีข้อจำกัดในอุตสาหกรรม (เช่น สมาร์ทวีซ่า)">
                    วีซ่าชั่วคราวที่มีข้อจำกัดในอุตสาหกรรม (เช่น สมาร์ทวีซ่า)</option>
                <option
                    value="วีซ่าราชการ หรือวีซ่านักการฑูต (เช่น ข้าราชการ วีซ่าประเภทคนอยู่ชั่วคราว F (Non-Immigrant F )">
                    วีซ่าราชการ หรือวีซ่านักการฑูต (เช่น ข้าราชการ วีซ่าประเภทคนอยู่ชั่วคราว F
                    (Non-Immigrant F )</option>
                <option value="ต้องการการสนับสนุนในการทำงานให้กับผู้ประกอบการใหม่ (เช่น Long stay or Tourist Visa)">
                    ต้องการการสนับสนุนในการทำงานให้กับผู้ประกอบการใหม่ (เช่น Long stay or Tourist
                    Visa)</option>
                <option
                    value="วีซ่าชั่วคราวที่มีข้อจำกัดตามระยะเวลาพำนัก (เช่น วีซ่านักลงทุน หรือวีซ่าประเภทคนอยู่ชั่วคราว O (Non-Immigrant O)">
                    วีซ่าชั่วคราวที่มีข้อจำกัดตามระยะเวลาพำนัก (เช่น วีซ่านักลงทุน
                    หรือวีซ่าประเภทคนอยู่ชั่วคราว O (Non-Immigrant O)</option>
            </select>

            <label for="expected_salary">เงินเดือนที่คาดหวัง:</label>
            <input type="text" id="expected_salary" name="expected_salary" placeholder="เงินเดือนที่ค้องการ" required>

            <label for="salary_type">ประเภทเงินเดือน:</label>
            <select id="salary_type" name="salary_type">
                <option value="รายเดือน">รายเดือน</option>
                <option value="รายชั่วโมง">รายชั่วโมง</option>
                <option value="รายปี">รายปี</option>
            </select>

            <label for="interested_job_type">ประเภทงานที่สนใจ:</label>
            <select id="interested_job_type" name="interested_job_type">
            <option value="">เลือกประเภทงานที่สนใจ</option>
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


            <label for="conscription">การเกณฑ์ทหาร:</label>
            <select id="conscription_status" name="conscription">
                <option value="">เลือกสถานะ</option>
                <option value="ผ่านเกณฑ์แล้ว">
                    ผ่านเกณฑ์แล้ว</option>
                <option value="ยังไม่ผ่าน">ยังไม่ผ่าน
                </option>
            </select>

            <label for="work_type">ประเภทงาน:</label>
            <select id="work_type" name="work_type">
                <option value="">เลือกประเภทการทำงาน</option>
                <option value="Online">Online</option>
                <option value="Onsite">Onsite</option>
                <option value="Onsite">Online/Onsite</option>
            </select>

            <label for="old">อายุ:</label>
            <input type="text" id="old" name="old" placeholder="กรุณาใส่อายุ">
            <button type="submit">เพิ่มผู้ใช้</button>
        </form>
    </div>
</body>

</html>