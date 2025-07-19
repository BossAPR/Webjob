<?php
session_start();
require('connectdb.php');

// Fetch unapproved jobs from the database
function fetchUnapprovedJobs($conn) {
    $job_ad = [];
    $sql = "SELECT * FROM job_ad WHERE job_status = 'Pending'"; // Query to select only unapproved jobs
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $job_ad[] = $row; // Add each row to the job_ad array
    }
    return $job_ad;
}

$unapproved_jobs = fetchUnapprovedJobs($connect); // Use the $connect variable from connectdb.php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อนุมัติงาน</title>
    <link rel="stylesheet" href="admin_styles.css">
    <style>
        /* Add styles for buttons */
        .approve-button {
            background-color: gold;
            /* Yellow for approve button */
            color: black;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        .not-approve-button {
            background-color: lightcoral;
            /* Light red for not approve button */
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
                    <li><a href="#">ออกจากระบบ</a></li>
                </ul>
            </nav>
        </header>

        <section class="main-content">
            <h2>อนุมัติงานที่ยังไม่ได้รับการอนุมัติ</h2>
            <a href="manage-jobs.php" class="add-job-button action-btn">กลับไปจัดการประกาศงาน</a>

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
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unapproved_jobs as $job): ?>
                    <tr>
                        <!--td><!?= $job['job_ad_id'] ?></td-->
                        <td><?= $job['company_name'] ?></td>
                        <td><?= $job['job_name'] ?></td>
                        <td><?= $job['job_detail'] ?></td>
                        <td><?= $job['job_type'] ?></td>
                        <td><?= $job['job_workers'] ?></td>
                        <td><?= $job['job_salary'] ?></td>
                        <td>
                            <div class="button-container">
                                <a class="approve-button action-btn" href="approve-job-action.php?id=<?= $job['job_ad_id'] ?>">อนุมัติ</a>
                                <a class="not-approve-button action-btn" href="not-approve-job-action.php?id=<?= $job['job_ad_id'] ?>"
                                    onclick="return confirm('คุณแน่ใจหรือว่าต้องการไม่อนุมัติงานนี้?')">ไม่อนุมัติ</a>
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
