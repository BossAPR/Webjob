<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินในฐานะผู้ดูแลหรือไม่
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// ดึงข้อมูลการติดต่อจากตาราง contact_form
$query = "SELECT * FROM contact_form ORDER BY submission_date DESC";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการการติดต่อจากลูกค้า</title>
    <link rel="stylesheet" href="admin_styles.css">
    <style>
    /* ปรับปรุงสไตล์ของปุ่ม */
    .action-btn {
        display: inline-block;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 5px;
        text-decoration: none;
        margin-right: 5px;
    }

    .edit-btn {
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .edit-btn:hover {
        background-color: #218838;
    }

    .delete-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
        background-color: #c82333;
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

    /* ทำให้ td ขยายได้ตามเนื้อหาภายใน */
    td {
        white-space: nowrap;
        vertical-align: middle;
    }

    /* ปรับปรุงสไตล์ของตาราง */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    /* ปรับปรุงสไตล์ของกรอบ */
    .admin-container {
        max-width: 1500px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* ปรับปรุงสไตล์ของส่วนที่ครอบตาราง */
    .main-content {
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow-x: auto; /* เพิ่ม scroll ถ้าตารางใหญ่เกินไป */
    }

    /* ปรับปรุงสไตล์ของหัวข้อ */
    .main-content h2 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    </style>
</head>

<body>
    <div class="admin-container">
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

        <section class="main-content">
            <h2>จัดการการติดต่อจากลูกค้า</h2>

            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>บริษัท</th>
                        <th>ตำแหน่ง</th>
                        <th>เบอร์โทร</th>
                        <th>อีเมล</th>
                        <th>ประเภทพนักงาน</th>
                        <th>รายละเอียด</th>
                        <th>วันที่ส่ง</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['company']); ?></td>
                        <td><?php echo htmlspecialchars($row['position']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['employee_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['details']); ?></td>
                        <td><?php echo $row['submission_date']; ?></td>
                        <td>
                            <a href="edit-contact.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">แก้ไข</a> |
                            <a href="delete-contact.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')">ลบ</a> |
                            <a href="send-email.php?id=<?php echo $row['id']; ?>" class="action-btn callback-btn">ติดต่อกลับ</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>
