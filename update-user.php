<?php
session_start();
require('connectdb.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_id = $_POST['account_id'];
    $name = $_POST['account_name'];
    $email = $_POST['account_email'];
    $role = $_POST['account_role'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // รับข้อมูลเพิ่มเติม
    $birthday = $_POST['birthday']; // วันเกิด
    //$gender = $_POST['gender']; // เพศ
    $addresses = $_POST['addresses']; // ที่อยู่
    $phone_numbers = $_POST['phone_numbers']; // เบอร์โทรศัพท์


    $experience = $_POST['experience'];
    $qualification = $_POST['qualification'];
    $course = $_POST['course'];
    $start_date = $_POST['start_date'];
    $employment_type = $_POST['employment_type'];
    $preferred_location = $_POST['preferred_location'];
    $work_eligibility = $_POST['work_eligibility'];
    $expected_salary = $_POST['expected_salary'];
    
    //$salary_type = $_POST['salary_type'];
    $interested_job_type = $_POST['interested_job_type'];
    $conscription = $_POST['conscription'];
    $work_type = $_POST['work_type'];
    $old = $_POST['old'];
    $sex = $_POST['sex'];
    
    
    
    



    // รับรหัสผ่านใหม่จากฟอร์ม
    $account_realpassword = $_POST['password']; // รหัสผ่านใหม่ที่กรอก

    // ถ้ามีการกรอกรหัสผ่านใหม่ ให้ทำการแฮชและอัปเดต
    if (!empty($account_realpassword)) {
        // สร้าง salt และ hash รหัสผ่าน
        $length = random_int(1, 128);
        $account_salt = bin2hex(random_bytes($length)); // สร้าง salt
        $account_password1 = $account_realpassword . $account_salt; // เอารหัสผ่านต่อ salt

        // ใช้ ARGON2ID เพื่อ hash รหัสผ่าน
        $algo = PASSWORD_ARGON2ID;
        $options = [
            'cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
        ];

        // Hash รหัสผ่าน
        $account_password = password_hash($account_password1, $algo, $options);

        // อัปเดตรหัสผ่านและ salt ในฐานข้อมูล
        $query = "UPDATE users_account SET account_password = ?, account_realpassword = ?, account_salt = ? WHERE account_id = ?";
        $stmt = mysqli_prepare($connect, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', $account_password, $account_realpassword, $account_salt, $account_id);
        mysqli_stmt_execute($stmt);
    }

    // อัปเดตข้อมูลอื่นๆ
    $query = "UPDATE users_account SET account_name = ?, account_email = ?, account_role = ?, first_name = ?, last_name = ?, birthday = ?, addresses = ?, phone_numbers = ? WHERE account_id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'sssssssis', $name, $email, $role, $first_name, $last_name, $birthday, $addresses, $phone_numbers, $account_id);
    mysqli_stmt_execute($stmt);

     // อัปเดตข้อมูลในตาราง applicant
     $query_applicant = "
     UPDATE applicant 
     SET experience = ?, qualification = ?, course = ?, start_date = ?, employment_type = ?, preferred_location = ?, 
     work_eligibility = ?, expected_salary = ?, interested_job_type = ?, conscription = ?, work_type = ?, old = ?, sex = ? 
     WHERE account_id = ?";
 $stmt_applicant = mysqli_prepare($connect, $query_applicant);
 mysqli_stmt_bind_param($stmt_applicant, 'sssssssiissssi', $experience, $qualification, $course, $start_date, $employment_type, 
     $preferred_location, $work_eligibility, $expected_salary, $interested_job_type, $conscription, $work_type, $old, $sex, $account_id);
 
 if (mysqli_stmt_execute($stmt_applicant)) {
     echo "อัปเดตข้อมูลผู้สมัครงานสำเร็จ<br>";
 } else {
     echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูลผู้สมัครงาน: " . mysqli_error($connect);
 }


    // Redirect ไปยังหน้า manage-users.php
    header('Location: manage-users.php');
    exit;
}



?>