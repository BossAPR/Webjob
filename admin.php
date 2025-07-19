<?php
session_start();
require('connectdb.php');
//var_dump($_SESSION);


// ตรวจสอบว่าได้ทำการล็อกอินในฐานะ admin แล้วหรือยัง
if (!isset($_SESSION['logged_in']) || $_SESSION['account_role'] !== 'admin') {
    // ถ้าไม่ใช่ผู้ดูแลระบบ ให้กลับไปที่หน้า login
    header('Location: form_login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Admin Dashboard</title>
    <link rel="stylesheet" href="admin_styles.css">
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
            <h2>ยินดีต้อนรับเข้าสู่ระบบจัดการ</h2>
            <p>เลือกเมนูเพื่อจัดการข้อมูลผู้ใช้หรือประกาศงาน</p>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jobTable = document.getElementById('jobTable').getElementsByTagName('tbody')[0];
            const addJobBtn = document.getElementById('addJobBtn');

            // ตัวอย่างข้อมูลงาน
            const jobs = [
                { id: 1, title: 'นักพัฒนาเว็บ', description: 'พัฒนาเว็บไซต์ด้วย PHP และ JavaScript', date: '2023-09-01' },
                { id: 2, title: 'นักออกแบบ UX/UI', description: 'ออกแบบหน้าตาเว็บไซต์', date: '2023-09-10' }
            ];

            function renderJobTable() {
                jobTable.innerHTML = '';
                jobs.forEach(job => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${job.id}</td>
                        <td>${job.title}</td>
                        <td>${job.description}</td>
                        <td>${job.date}</td>
                        <td>
                            <button onclick="editJob(${job.id})">แก้ไข</button>
                            <button onclick="deleteJob(${job.id})">ลบ</button>
                        </td>
                    `;
                    jobTable.appendChild(row);
                });
            }

            addJobBtn.addEventListener('click', function() {
                const newJob = {
                    id: jobs.length + 1,
                    title: prompt('ชื่องาน:'),
                    description: prompt('รายละเอียด:'),
                    date: new Date().toISOString().split('T')[0]
                };
                jobs.push(newJob);
                renderJobTable();
            });

            window.editJob = function(id) {
                const job = jobs.find(job => job.id === id);
                job.title = prompt('ชื่องาน:', job.title);
                job.description = prompt('รายละเอียด:', job.description);
                renderJobTable();
            };

            window.deleteJob = function(id) {
                const index = jobs.findIndex(job => job.id === id);
                jobs.splice(index, 1);
                renderJobTable();
            };

            renderJobTable();
        });
    </script>
</body>
</html>
