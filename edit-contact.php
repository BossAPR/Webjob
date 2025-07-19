<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินในฐานะผู้ดูแลหรือไม่
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// รับค่า ID จาก URL
$contact_id = $_GET['id'] ?? null;

if (!$contact_id) {
    echo "ไม่พบข้อมูลที่ต้องการแก้ไข";
    exit;
}

// ดึงข้อมูลการติดต่อจากฐานข้อมูล
$query = "SELECT * FROM contact_form WHERE id = ?";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, 'i', $contact_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $company = $row['company'];
    $position = $row['position'];
    $phone = $row['phone'];
    $email = $row['email'];
    $employee_type = $row['employee_type'];
    $details = $row['details'];
} else {
    echo "ไม่พบข้อมูลที่ต้องการแก้ไข";
    exit;
}

// ถ้ามีการส่งฟอร์มเพื่อแก้ไขข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $company = $_POST['company'];
    $position = $_POST['position'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $employee_type = $_POST['employee_type'];
    $details = $_POST['details'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_query = "UPDATE contact_form SET first_name = ?, last_name = ?, company = ?, position = ?, phone = ?, email = ?, employee_type = ?, details = ? WHERE id = ?";
    $stmt = mysqli_prepare($connect, $update_query);
    mysqli_stmt_bind_param($stmt, 'ssssssssi', $first_name, $last_name, $company, $position, $phone, $email, $employee_type, $details, $contact_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Location: manage-contacts.php');
        exit;
    } else {
        echo "Error updating contact: " . mysqli_error($connect);
    }
}
?>


<!-- ฟอร์มแก้ไขข้อมูล -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขการติดต่อ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 45px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: grid;
            grid-template-columns: 1fr 2fr;
            grid-gap: 10px;
        }
        label {
            display: flex;
            align-items: center;
            font-weight: bold;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .buttons {
            grid-column: 1 / span 2;
            display: flex;
            justify-content: space-between;
        }
        .save-btn, .back-btn {
            padding: 10px 20px;
            margin-right: 10px;
        }
        .save-btn {
            background-color: #28a745;
            color: white;
            font-size: 18px;
            border: none;
            cursor: pointer;
        }
        .save-btn:hover {
            background-color: #218838;
        }
        .back-btn {
            background-color: #6c757d;
            color: white;
            font-size: 18px;
            border: none;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #5a6268;
        }

        .admin-header {
    background-color: #343a40; /* สีพื้นหลังของ header */
    color: white; /* สีข้อความใน header */
    padding: 20px; /* ระยะห่างภายใน header */
    text-align: center; /* จัดข้อความให้อยู่กลาง */
}

.admin-header h1 {
    margin: 0; /* ไม่มีระยะห่างรอบหัวข้อ */
}

.admin-header nav {
    margin-top: 10px; /* ระยะห่างระหว่างหัวข้อและเมนู */
}

.admin-header nav ul {
    list-style: none; /* ไม่มีจุดในรายการ */
    padding: 0; /* ไม่มีการ padding */
}

.admin-header nav ul li {
    display: inline; /* แสดงรายการเป็นแนวนอน */
    margin: 0 15px; /* ระยะห่างระหว่างรายการ */
}

.admin-header nav ul li a {
    color: white; /* สีของลิงค์ */
    text-decoration: none; /* ไม่มีขีดเส้นใต้ */
    font-weight: bold; /* ทำให้ข้อความหนา */
}

.admin-header nav ul li a:hover {
    text-decoration: underline; /* ขีดเส้นใต้เมื่อเอาเมาส์ไปวาง */
}

    </style>
</head>
<body>
<header class="admin-header">
        <h1 style="color: beige;">Admin Dashboard</h1>
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
    <div class="container">
        <h1>แก้ไขการติดต่อ</h1>
        <form method="POST">
            <label for="first_name">ชื่อ:</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>

            <label for="last_name">นามสกุล:</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>

            <label for="company">บริษัท:</label>
            <input type="text" name="company" id="company" value="<?php echo htmlspecialchars($company); ?>">

            <label for="position">ตำแหน่ง:</label>
            <input type="text" name="position" id="position" value="<?php echo htmlspecialchars($position); ?>">

            <label for="phone">เบอร์โทร:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>">

            <label for="email">อีเมล:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="employee_type">ประเภทพนักงาน:</label>
            <input type="text" name="employee_type" id="employee_type" value="<?php echo htmlspecialchars($employee_type); ?>">

            <label for="details">รายละเอียด:</label>
            <textarea name="details" id="details" required><?php echo htmlspecialchars($details); ?></textarea>

            <div class="buttons">
                <button type="submit" class="save-btn">บันทึก</button>
                <button type="button" class="back-btn" onclick="window.history.back();">ย้อนกลับ</button>
            </div>
        </form>
    </div>
</body>
</html>
