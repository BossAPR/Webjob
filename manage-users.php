<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินในฐานะผู้ดูแลหรือไม่
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');
    exit;
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$query = "SELECT account_id, account_name, account_email, account_role FROM users_account ORDER BY account_id ASC";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<style>
        /* ปรับปรุงสไตล์ของปุ่ม */
.action-btn {
    display: inline-block;
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 5px;
    text-decoration: none;
    margin-right: 5px; /* เพิ่มระยะห่างระหว่างปุ่ม */
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

/* ทำให้ td ขยายได้ตามเนื้อหาภายใน */
td {
    white-space: nowrap; /* ป้องกันการตัดบรรทัดภายในเซลล์ */
    vertical-align: middle; /* จัดกึ่งกลางในแนวตั้ง */
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

    </style>
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
                    <li><a href="logout.php">ออกจากระบบ</a></li> <!-- เปลี่ยนให้เป็นหน้า logout -->
                </ul>
            </nav>
        </header>

        <section class="main-content">
            <h2>จัดการผู้ใช้</h2>
            <button onclick="window.location.href='add-user.php'">เพิ่มผู้ใช้ใหม่</button>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ชื่อผู้ใช้</th>
                        <th>อีเมล</th>
                        <th>บทบาท</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['account_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['account_email']); ?></td>
                        <td><?php echo htmlspecialchars($row['account_role']); ?></td>
                        <td>
                            <a href="edit-user.php?id=<?php echo $row['account_id']; ?>" class="action-btn edit-btn">แก้ไข</a>| 
                            <a href="delete-user.php?id=<?php echo $row['account_id']; ?>" class="action-btn delete-btn"
                                onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')">ลบ</a>| 
                            <a href="send-email-fromadmin.php?id=<?php echo $row['account_id']; ?>" class="action-btn callback-btn">ติดต่อกลับ</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>

<?php
mysqli_close($connect);
?>
