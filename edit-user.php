<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินในฐานะผู้ดูแลหรือไม่
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// ตรวจสอบว่ามีการส่ง account_id หรือไม่
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID ไม่ถูกต้อง");
}

$account_id = $_GET['id'];

// ดึงข้อมูลผู้ใช้และข้อมูลผู้สมัครงานจากฐานข้อมูล
$query = "
    SELECT u.*, a.* 
    FROM users_account u 
    LEFT JOIN applicant a ON u.account_id = a.account_id 
    WHERE u.account_id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $account_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die("ไม่พบผู้ใช้ที่ต้องการแก้ไข");
}

$user = mysqli_fetch_assoc($result);

// ดึงข้อมูลไฟล์ PDF ที่เกี่ยวข้อง
$query_pdf = "SELECT * FROM tbl_pdf WHERE account_id = ?";
$stmt_pdf = mysqli_prepare($connect, $query_pdf);
mysqli_stmt_bind_param($stmt_pdf, "i", $account_id);
mysqli_stmt_execute($stmt_pdf);
$result_pdf = mysqli_stmt_get_result($stmt_pdf);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_styles.css">
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

    <div class="container mt-5">
        <div class="admin-container">
            <h1 class="mb-4">แก้ไขผู้ใช้</h1>
            <form action="update-user.php" method="POST">
                <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($user['account_id']); ?>">
                <div class="mb-3">
                    <label class="form-label">ชื่อผู้ใช้:</label>
                    <input type="text" name="account_name"
                        value="<?php echo htmlspecialchars($user['account_name']); ?>" class="form-control" required>
                </div>

                <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($user['account_id']); ?>">
                <div class="mb-3">
                    <label class="form-label">รหัสผ่านใหม่:</label>
                    <input type="password" name="password" class="form-control" placeholder="กรุณากรอกรหัสผ่านใหม่">
                </div>

                <div class="mb-3">
                    <label class="form-label">อีเมล:</label>
                    <input type="email" name="account_email"
                        value="<?php echo htmlspecialchars($user['account_email']); ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ชื่อจริง:</label>
                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>"
                        class="form-control" placeholder="ชื่อจริง" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">นามสกุล:</label>
                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>"
                        class="form-control" placeholder="นามสกุล" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">เพศ:</label>
                    <select name="sex" class="form-select" required>
                        <option value="ชาย" <?php if ($user['sex'] === 'ชาย') echo 'selected'; ?>>ชาย</option>
                        <option value="หญิง" <?php if ($user['sex'] === 'หญิง') echo 'selected'; ?>>หญิง</option>
                        <option value="อื่นๆ" <?php if ($user['sex'] === 'อื่นๆ') echo 'selected'; ?>>อื่นๆ</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">วันเกิด:</label>
                    <input type="date" name="birthday"
                        value="<?php echo isset($user['birthday']) ? htmlspecialchars($user['birthday']) : ''; ?>"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ที่อยู่:</label>
                    <input type="text" name="addresses"
                        value="<?php echo isset($user['addresses']) ? htmlspecialchars($user['addresses']) : ''; ?>"
                        class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">เบอร์โทรศัพท์:</label>
                    <input type="tel" name="phone_numbers"
                        value="<?php echo isset($user['phone_numbers']) ? htmlspecialchars($user['phone_numbers']) : ''; ?>"
                        class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">บทบาท:</label>
                    <select name="account_role" class="form-select">
                        <option value="user" <?php if ($user['account_role'] === 'user') echo 'selected'; ?>>User
                        </option>
                        <option value="admin" <?php if ($user['account_role'] === 'admin') echo 'selected'; ?>>Admin
                        </option>
                    </select>
                </div>

                <!-- ฟิลด์สำหรับข้อมูลผู้สมัครงาน -->
                <h2 class="mt-4">ข้อมูลผู้สมัครงาน</h2>

                <div class="mb-3">
                    <label class="form-label">ประสบการณ์:</label>
                    <textarea name="experience" placeholder="กรุณาใส่ประสบการณ์ทำงานเป็นปี"
                        class="form-control"><?php echo htmlspecialchars($user['experience']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">วุฒิการศึกษา:</label>
                    <!--input type="text" name="education" value="<-?php echo htmlspecialchars($user['education']); ?>"
                
            class="form-control"-->
                    <select id="qualification" name="qualification"
                        value="<?php echo htmlspecialchars($user['qualification']); ?>" class="form-control">
                        <option value="">เลือกประเภทวุฒิ</option>
                        <option value="0">ไม่มีการศึกษา</option>
                        <option value="1">ประถมศึกษา</option>
                        <option value="2">มัธยมศึกษาตอนต้น</option>
                        <option value="3">มัธยมศึกษาตอนปลายหรือเทียบเท่า</option>
                        <option value="4">อนุปริญญา</option>
                        <option value="5">ปริญญาตรีขึ้นไปหรือเทียบเท่า</option>
                    </select>
                </div>


                <div class="mb-3">
                    <label for="form-label">เลือกสาขาที่จบ:</label>
                    <select id="course" name="course" value="<?php echo htmlspecialchars($user['course']); ?>"
                        class="form-control">
                        <option value="">เลือกสาขาที่จบ</option>
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
                </div>

                <!-- ฟิลด์สำหรับข้อมูลตำแหน่งงาน -->
                <h2 class="mt-4">เกี่ยวกับตำแหน่งงานของคุณ</h2>
                <div class="mb-3">
                    <label class="form-label">วันที่สามารถเริ่มงานได้:</label>
                    <select name="start_date" class="form-select">
                        <option value="ทันที" <?php if ($user['start_date'] === 'ทันที') echo 'selected'; ?>>ทันที
                        </option>
                        <option value="อีก 1 สัปดาห์"
                            <?php if ($user['start_date'] === 'อีก 1 สัปดาห์') echo 'selected'; ?>>อีก
                            1 สัปดาห์
                        </option>
                        <option value="อีก 2 สัปดาห์"
                            <?php if ($user['start_date'] === 'อีก 2 สัปดาห์') echo 'selected'; ?>>อีก
                            2 สัปดาห์
                        </option>
                        <option value="อื่นๆ" <?php if ($user['start_date'] === 'อื่นๆ') echo 'selected'; ?>>อื่นๆ
                        </option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">ประเภทการจ้างงานที่ต้องการ:</label>
                    <select name="employment_type" class="form-select">
                        <option value="งานประจำ" <?php if ($user['employment_type'] === '1') echo 'selected'; ?>>
                            งานประจำ</option>
                        <option value="งานพาร์ทไทม์" <?php if ($user['employment_type'] === '2') echo 'selected'; ?>>
                            งานพาร์ทไทม์</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">สถานที่ทำงานที่ต้องการ:</label>
                    <select id="preferred_location" name="preferred_location" placeholder="จังหวัดที่ต้องการ"
                        class="form-control"
                        value="<?php echo isset($user['preferred_location']) ? htmlspecialchars($user['preferred_location']) : ''; ?>">
                        <option value="">เลือกจังหวัด</option>
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
                </div>

                <div class="mb-3">
                    <label class="form-label">สิทธิการทำงานที่ถูกต้องตามกฎหมาย:</label>
                    <select name="work_eligibility" class="form-select">
                        <option value="มี"
                            <?php if (isset($user['work_eligibility']) && $user['work_eligibility'] === 'มี') echo 'selected'; ?>>
                            มี</option>
                        <option value="ไม่มี"
                            <?php if (isset($user['work_eligibility']) && $user['work_eligibility'] === 'ไม่มี') echo 'selected'; ?>>
                            ไม่มี</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">เงินเดือนที่คาดหวัง:</label>
                    <input type="number" name="expected_salary"
                        value="<?php echo isset($user['expected_salary']) ? htmlspecialchars($user['expected_salary']) : ''; ?>"
                        class="form-control" placeholder="เงินเดือนที่คาดหวัง">
                </div>

                <div class="mb-3">
                    <label class="form-label">ประเภทงานที่สนใจ:</label>
                    <select id="interested_job_type" name="interested_job_type" class="form-control"
                        value="<?php echo isset($user['interested_job_type']) ? htmlspecialchars($user['interested_job_type']) : ''; ?>">
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
                        <option value="วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ">วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">ผ่านเกณฑ์ทหารแล้วหรือยัง:</label>
                    <!--select name="military_status" class="form-select">
                <option value="ผ่าน"
                    <!?php if (isset($user['military_status']) && $user['military_status'] === 'ผ่าน') echo 'selected'; ?>>
                    ผ่าน</option>
                <option value="ไม่ผ่าน"
                    <!?php if (isset($user['military_status']) && $user['military_status'] === 'ไม่ผ่าน') echo 'selected'; ?>>
                    ไม่ผ่าน</option>
            </select-->
                    <select id="conscription" name="conscription" class="form-select">
                        <option value="">เลือกสถานะ</option>
                        <option value="ผ่านเกณฑ์แล้ว" <?php echo ($user === 'ผ่านเกณฑ์แล้ว') ? 'selected' : ''; ?>>
                            ผ่านเกณฑ์แล้ว</option>
                        <option value="ยังไม่ผ่าน" <?php echo ($user === 'ยังไม่ผ่าน') ? 'selected' : ''; ?>>ยังไม่ผ่าน
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">ประเภทการทำงาน:</label>
                    <select id="work_type" name="work_type" class="form-control"
                        value="<?php echo isset($user['work_type']) ? htmlspecialchars($user['work_type']) : ''; ?>">
                        <option value="">เลือกประเภทการทำงาน</option>
                        <option value="Online">Online</option>
                        <option value="Onsite">Onsite</option>
                        <option value="Onsite">Online/Onsite</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">อายุ:</label>
                    <input type="text" name="old"
                        value="<?php echo isset($user['old']) ? htmlspecialchars($user['old']) : ''; ?>"
                        class="form-control" placeholder="กรุณาใส่อายุ">
                </div>

                <button type="submit" class="btn btn-primary">อัปเดตข้อมูล</button>
            </form>

            <!-- ส่วนของการแก้ไขไฟล์ PDF -->
            <h2 class="mt-5">แก้ไขไฟล์ PDF</h2>
            <form action="manage-pdfs.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($user['account_id']); ?>">

                <div class="mb-3">
                    <label class="form-label">ไฟล์ PDF ปัจจุบัน:</label>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อเอกสาร</th>
                            <th>เปิดดู</th>
                            <th>อัปเดตชื่อ</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pdf = mysqli_fetch_assoc($result_pdf)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pdf['no']); ?></td>
                            <td>
                                <input type="text" name="doc_name[]"
                                    value="<?php echo htmlspecialchars($pdf['doc_name']); ?>" class="form-control"
                                    required>
                                <input type="hidden" name="pdf_id[]"
                                    value="<?php echo htmlspecialchars($pdf['no']); ?>">
                            </td>
                            <td><a href="docs/<?php echo htmlspecialchars($pdf['doc_file']); ?>" target="_blank"
                                    class="btn btn-info">เปิดดู</a></td>
                            <td>
                                <button type="submit" class="btn btn-success btn-sm mt-2">อัปเดต</button>
                            </td>
                            <td>
                                <form action="delete-pdf.php" method="post" style="display:inline;">
                                    <input type="hidden" name="doc_id"
                                        value="<?php echo htmlspecialchars($pdf['no']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('คุณต้องการลบเอกสารนี้จริงหรือไม่?');">ลบ</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </form>

            <h2 class="mt-5">อัปโหลดไฟล์ PDF ใหม่</h2>
            <form action="manage-pdfs.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($user['account_id']); ?>">
                <div class="mb-3">
                    <label class="form-label">ชื่อเอกสารใหม่:</label>
                    <input type="text" name="new_doc_name" placeholder="ชื่อเอกสารใหม่" class="form-control mb-2"
                        required>
                    <input type="file" name="pdf_file" accept="application/pdf" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">อัปโหลดไฟล์ PDF</button>
            </form>
        </div>
    </div>
    <br><br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>



<?php
mysqli_close($connect);
?>