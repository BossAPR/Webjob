<?php
session_start(); // เริ่มต้น session ถ้ายังไม่มี

// ตรวจสอบว่ามีการตั้งค่า account_id ใน session หรือไม่
if (!isset($_SESSION['account_id'])) {
    $_SESSION['account_id'] = 123; // ตัวอย่างการตั้งค่า account_id
}

$account_id = $_SESSION['account_id']; // กำหนดค่า account_id จาก session

require_once 'connect.php'; // เชื่อมต่อฐานข้อมูล

// แสดงข้อผิดพลาดใน PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// จัดการกับการอัปโหลด PDF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doc_name'], $_FILES['doc_file'], $_POST['account_id'])) {
    handleFileUpload($conn, $_POST, $_FILES);
}

// ฟังก์ชันสำหรับจัดการการอัปโหลดไฟล์
function handleFileUpload($conn, $postData, $fileData) {
    $account_id = $postData['account_id'];
    $doc_name = $postData['doc_name'];
    $upload = $fileData['doc_file']['name'];
    $date1 = date("Ymd_His");
    $numrand = mt_rand();

    // ตรวจสอบว่า account_id มีอยู่ใน users_account หรือไม่
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users_account WHERE account_id = :account_id");
    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    $stmt->execute();
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        if ($upload != '') {
            // ตรวจสอบประเภทไฟล์
            $typefile = strrchr($fileData['doc_file']['name'], ".");
            if ($typefile === '.pdf') {
                $path = "docs/";
                $newname = 'doc_' . $numrand . $date1 . $typefile;
                $path_copy = $path . $newname;

                // ย้ายไฟล์
                if (move_uploaded_file($fileData['doc_file']['tmp_name'], $path_copy)) {
                    saveFileInfo($conn, $doc_name, $newname, $account_id);
                } else {
                    alert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์");
                }
            } else {
                alert("ไฟล์ไม่ใช่ .pdf");
            }
        }
    } else {
        alert("ไม่พบ account_id");
    }
}

// ฟังก์ชันสำหรับบันทึกข้อมูลเอกสารลงในฐานข้อมูล
function saveFileInfo($conn, $doc_name, $newname, $account_id) {
    $stmt = $conn->prepare("INSERT INTO tbl_pdf (doc_name, doc_file, account_id) VALUES (:doc_name, :doc_file, :account_id)");
    $stmt->bindParam(':doc_name', $doc_name, PDO::PARAM_STR);
    $stmt->bindParam(':doc_file', $newname, PDO::PARAM_STR);
    $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        //alert("อัพโหลดไฟล์เอกสารสำเร็จ", true);
        // แจ้งเตือนการอัปโหลดสำเร็จ โดยไม่เปลี่ยนหน้า
        alert("อัพโหลดไฟล์เอกสารสำเร็จ", false); // เปลี่ยนเป็น false
    } else {
        alert("เกิดข้อผิดพลาดในการบันทึกข้อมูล");
    }
}

// ฟังก์ชันสำหรับแสดงการแจ้งเตือน
function alert($message, $redirect = false) {
    $redirectScript = $redirect ? 'window.location = "upload_pdf.php";' : '';
    echo '<script>
        swal({
            title: "' . $message . '",
            icon: "' . ($redirect ? 'success' : 'error') . '",
            buttons: {
                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true
                }
            }
        }).then((value) => {
            ' . $redirectScript . '
        });
    </script>';
}

?>

<?php
//ของ login

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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles (1).css">
    <title>Upload PDF File</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

    <style>
        
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
        margin-bottom: -100px; /* ลดระยะ margin หรือเนื้อหากับ headder*/
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

    /* New css เอามาเพิ่มเองที่หลังเอามาจาก style.css */
    
    .upload-form {
    display: flex;
    flex-direction: column;
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
            <img href="#" class="logo"  src="assets/account_images/2.png">

                <div class="nav__menu" id="nav-menu">
                    <ul class="nav__list">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li class="nav__item"><a href="indextest.php" class="nav__link active-link">หน้าหลัก</a></li>
                    <li class="nav__item"><a href="af_about.php" class="nav__link">เกี่ยวกับเรา</a></li>
                    <!--li class="nav__item"><a href="#profile" class="nav__link">ข่าวสาร</a></li-->
                    <!--li class="nav__item"><a href="#article" class="nav__link">บทความ</a></li-->
                    <!--li class="nav__item"><a href="#profile" class="nav__link">รีวิว</a></li-->
                    <li class="nav__item"><a href="af_Contact_us.php" class="nav__link">ศูนย์ช่วยเหลือ</a></li>
                        <!--li><i class='bx bx-moon change-theme' id="theme-button"></i></li-->
                        <a href="af_job_search.php"><button type="button" class="btn info"><b>บอร์ดหางาน</b></button></a>

                    <a href="job_announcement.php"><button type="button" class="btn success"><b>บอร์ดประกาศหางาน</b></button></a>
                    <a href="af_job_searchbyAI.php"><button type="button" class="btn info"><b>บอร์ดหางานแบบ
                                AI</b></button></a>
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
                </div>    &nbsp;
                <div class="nav__toggle" id="nav-toggle">
                    <i class='bx bx-menu'></i>
                </div>
            </nav>
        </header>




    <div class="" style="margin-top: 100px;">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <br>
                <h3>Upload RESUME File</h3>

                <!-- ฟอร์มอัปโหลด -->
                <form action="" method="post" enctype="multipart/form-data" class="upload-form"><br>
                    <font color="red">*ตั้งชื่อไฟล์ว่า resume</font>
                    <input type="text" name="doc_name" required class="form-control" placeholder="ชื่อเอกสาร">
                    
                    <font color="red">*อัพโหลดได้เฉพาะ .pdf เท่านั้น</font>
                    <input type="file" name="doc_file" required class="form-control" accept="application/pdf">
                    
                    <!-- ซ่อนค่า account_id ในฟอร์ม -->
                    <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($account_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <br>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form><br>

                <h3>รายการเอกสาร</h3><br>
                <table class="table table-striped table-hover table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">ลำดับ</th>
                            <th width="70%">ชื่อเอกสาร</th>
                            <th width="10%">เปิดดู</th>
                            <th width="10%">ลบ</th> <!-- เพิ่มคอลัมน์ลบ -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // ดึงเอกสารเฉพาะที่เป็นของ account_id นั้น ๆ
                        $stmt = $conn->prepare("SELECT * FROM tbl_pdf WHERE account_id = :account_id");
                        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        foreach ($result as $row) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['no']); ?></td>
                            <td><?= htmlspecialchars($row['doc_name']); ?></td>
                            <td><a href="docs/<?php echo htmlspecialchars($row['doc_file']); ?>" target="_blank" class="btn btn-info btn-sm">เปิดดู</a></td>
                            <td>
                                <!-- ฟอร์มสำหรับลบไฟล์ -->
                                <form action="delete_pdf.php" method="post" style="display:inline;">
                                    <input type="hidden" name="doc_id" value="<?= htmlspecialchars($row['no']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการลบเอกสารนี้จริงหรือไม่?');">ลบ</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <br>
            </div>
        </div>
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
    
</body>
</html>