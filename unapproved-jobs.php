<?php
session_start();
require('connectdb.php');

// Fetch unapproved jobs from the database
function fetchUnapprovedJobs($conn) {
    $job_ad = [];
    $sql = "SELECT * FROM job_ad WHERE job_status = 'Not approved'"; // Query to select only unapproved jobs
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
    <title>งานที่ไม่อนุมัติ</title>
    <link rel="stylesheet" href="admin_styles.css">
    <style>
        /* Add styles for buttons */
        .button-container {
            display: flex;
            gap: 5px;
        }

        .add-job-button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .approve-button {
            background-color: gold;
            color: black;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            text-decoration: none; /* Remove underline */
        }

        .delete-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            text-decoration: none; /* Remove underline */
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
            <h2>งานที่ยังไม่ได้รับการอนุมัติ</h2>
            <a href="manage-jobs.php" class="add-job-button">กลับไปจัดการประกาศงาน</a>

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
                                <a class="approve-button" href="Pending-job-action?id=<?= $job['job_ad_id'] ?>">รอดำเนินการ</a>
                                <a class="delete-button" href="delete-job.php?id=<?= $job['job_ad_id'] ?>" onclick="return confirm('คุณแน่ใจหรือว่าต้องการลบงานนี้?');">ลบ</a>
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
