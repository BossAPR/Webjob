<?php
session_start();
require('connectdb.php');

// Fetch jobs from the database
function fetchJobs($conn) {
    $job_ad = [];
    $sql = "SELECT * FROM job_ad WHERE job_status = 'approved' and job_expire_at >= CURDATE()"; // Query to select only approved jobs
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $job_ad[] = $row; // Add each row to the job_ad array
    }
    return $job_ad;
}

$job_ad = fetchJobs($connect); // Use the $connect variable from connectdb.php
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
    <title>จัดการประกาศงาน</title>
    <link rel="stylesheet" href="admin_styles.css">
    <style>
    /* Add styles for buttons */
    .edit-button,
    .delete-button {
        text-decoration: none;
        /* Remove underline */
    }

    .edit-button {
        background-color: lightseagreen;
        /* Blue for edit button */
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 3px;
    }

    .delete-button {
        background-color: lightcoral;
        /* Light red for delete button */
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 3px;
    }

    .button-container {
        display: flex;
        gap: 5px;
        /* Space between buttons */
    }

    /* Style for the add job button */
    .add-job-button {
        background-color: green;
        /* Green color for add button */
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        text-decoration: none;
        /* Remove underline */
        display: inline-block;
        margin-bottom: 20px;
    }

    .approve-job-button {
        background-color: lightskyblue;
        /* Light yellow for approve jobs button */
        color: black;
    }

    .unapproved-jobs-button {
        background-color: lightpink;
        /* Light pink for unapproved jobs button */
        color: black;
    }

    .action-btn {
        display: inline-block;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 5px;
        text-decoration: none;
        margin-right: 5px;
        /* เพิ่มระยะห่างระหว่างปุ่ม */
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

    .view-btn {
        background-color: darkcyan;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .view-btn:hover {
        background-color: aquamarine;
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
                    <li><a href="logout.php">ออกจากระบบ</a></li> <!-- เปลี่ยนให้เป็นหน้า logout -->
                </ul>
            </nav>
        </header>

        <section class="main-content">
            <h2>จัดการประกาศงาน</h2>
            <a href="add-job.php" class="add-job-button action-btn">เพิ่มงานใหม่</a>
            <a href="approve-job.php" class="add-job-button action-btn approve-job-button">งานที่รอการอนุมัติ</a>
            <!-- New button -->

            <a href="unapproved-jobs.php"
                class="add-job-button action-btn unapproved-jobs-button">ดูงานที่ไม่อนุมัติ</a> <!-- Updated color -->

            <table id="jobTable">
                <thead>
                    <tr>
                        <!--th>ID</th-->
                        <th>ชื่อบริษัท</th>
                        <th>ชื่องาน</th>
                        <th>รายละเอียด</th>
                        <th>ประเภทงาน</th>
                        <th>จำนวนคนงาน</th>
                        <th>เงินเดือน</th>
                        <th>ประกาศงานเมื่อ</th>
                        <th>การจัดการ</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($job_ad as $job): ?>
                    <tr>
                        <!--td><!?= $job['job_ad_id'] ?></td-->
                        <td><?= $job['company_name'] ?></td>
                        <td><?= $job['job_name'] ?></td>
                        <td><?= $job['job_detail'] ?></td>
                        <td>
                            <?php 
                            echo ($job['job_type'] == 1) ? "งานประจำ" : (($job['job_type'] == 2) ? "งานพาร์ทไทม์" : "ไม่ระบุประเภทงาน");
                            ?>
                        </td>
                        <td><?= $job['job_workers'] ?></td>
                        <!--td>
                            <!?php 
                            echo ($job['job_salary'] == 0) ? "ไม่ระบุ" : $job['job_salary'];
                            ?>
                        </td-->

                        <td>
                            <?php 
                            if ($job['job_salary'] == 0 || empty($job['job_salary'])) {
                            echo "ไม่ระบุ";
                            } else {
                            // คั่นหลักพันและเพิ่มคำว่า "บาท" ต่อท้าย
                            echo number_format($job['job_salary']) . " บาท";
                            }
                            ?>
                        </td>


                        <td><?php echo time_elapsed_string($job['job_create_at']); ?></td>

                        <td>
                            <div class="button-container">
                                <!--a class="edit-button action-btn" href="edit-job.php?id=<!?= $job['job_ad_id'] ?>">แก้ไข</a-->

                                <a href="view-applicants.php?job_id=<?= $job['job_ad_id'] ?>" class="action-btn view-btn">ดูรายละเอียดผู้สมัคร</a>
                                <a href="send-email-fromadmin2.php?id=<?= $job['job_ad_id']; ?>" class="action-btn callback-btn">ติดต่อกลับ</a>
                                <a class="delete-button action-btn" href="delete-job.php?id=<?= $job['job_ad_id'] ?>"
                                    onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบงานนี้?')">ลบ</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>