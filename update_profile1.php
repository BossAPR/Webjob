<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินหรือยัง
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php'); // เปลี่ยนเส้นทางไปหน้า login ถ้า session ไม่ถูกต้อง
    exit;
}

// ตรวจสอบว่ามีการตั้งค่า session สำหรับ user_id หรือยัง
if (!isset($_SESSION['account_id'])) {
    echo "User ID is not set in the session.";
    exit();
}

$user_id = $_SESSION['account_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $start_date = $_POST['start_date'] ?? '';
    $employment_type = $_POST['employment_type'] ?? '';
    $preferred_location = $_POST['preferred_location'] ?? '';
    $work_eligibility = $_POST['work_eligibility'] ?? '';
    $expected_salary = $_POST['expected_salary'] ?? '';
    $salary_type = $_POST['salary_type'] ?? '';
    $interested_job_type = $_POST['interested_job_type'] ?? '';
    $conscription = $_POST['conscription'] ?? ''; // New field
    $work_type = $_POST['work_type'] ?? ''; // New field
    $old = $_POST['old'] ?? ''; // New field

    // Assuming $userapplicant is fetched earlier
    $sex = $_POST['sex'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $course = $_POST['course'] ?? '';
    $experience = $_POST['experience'] ?? 0; // Assuming experience is an integer

    // เช็คว่ามีข้อมูลในตาราง applicant หรือไม่
    $query_check = "SELECT * FROM applicant WHERE account_id = '$user_id'";
    $result_check = mysqli_query($connect, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // มีข้อมูลอยู่แล้ว ให้ทำการ UPDATE
        $query_applicant = "UPDATE applicant 
                            SET start_date = ?, 
                                employment_type = ?, 
                                preferred_location = ?, 
                                work_eligibility = ?, 
                                expected_salary = ?, 
                                salary_type = ?, 
                                interested_job_type = ?, 
                                conscription = ?, 
                                work_type = ?, 
                                old = ?,
                                sex = ?,                      
                                qualification = ?, 
                                course = ?, 
                                experience = ? 
                            WHERE account_id = ?";
        $stmt = mysqli_prepare($connect, $query_applicant);
        mysqli_stmt_bind_param($stmt, 'sssssssssssssis', $start_date, $employment_type, $preferred_location, $work_eligibility, $expected_salary, $salary_type, $interested_job_type, $conscription, $work_type, $old, $sex, $qualification, $course, $experience, $user_id);
    } else {
        // ไม่มีข้อมูล ให้ทำการ INSERT
        $query_applicant = "INSERT INTO applicant (account_id, start_date, employment_type, preferred_location, work_eligibility, expected_salary, salary_type, interested_job_type, conscription, work_type, old, sex, qualification, course, experience) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $query_applicant);
        mysqli_stmt_bind_param($stmt, 'sssssssssssssis', $user_id, $start_date, $employment_type, $preferred_location, $work_eligibility, $expected_salary, $salary_type, $interested_job_type, $conscription, $work_type, $old, $sex, $qualification, $course, $experience);
    }

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Redirection after successful update or insert
        header('Location: edit_profile.php');
        exit();
    } else {
        echo "Error updating applicant profile: " . mysqli_error($connect);
        exit();
    }
}

// ดึงข้อมูลจากฐานข้อมูล
$query = "SELECT * FROM applicant WHERE account_id = '$user_id'";
$result = mysqli_query($connect, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $applicant = mysqli_fetch_assoc($result);
    $start_date = $applicant['start_date'] ?? 'ไม่ระบุ';
    $employment_type = $applicant['employment_type'] ?? 'ไม่ระบุ';
    $preferred_location = $applicant['preferred_location'] ?? 'ไม่ระบุ';
    $work_eligibility = $applicant['work_eligibility'] ?? 'ไม่ระบุ';
    $expected_salary = $applicant['expected_salary'] ?? 'ไม่ระบุ';
    $interested_job_type = $applicant['interested_job_type'] ?? 'ไม่ระบุ';
    $conscription = $applicant['conscription'] ?? 'ไม่ระบุ'; // New field
    $work_type = $applicant['work_type'] ?? 'ไม่ระบุ'; // New field
    $old = $applicant['old'] ?? 'ไม่ระบุ'; // New field

    $sex = $applicant['sex'] ?? 'ไม่ระบุ';
    $qualification = $applicant['qualification'] ?? 'ไม่ระบุ';
    $course = $applicant['course'] ?? 'ไม่ระบุ';
    $experience = $applicant['experience'] ?? 'ไม่ระบุ';
} else {
    // กรณีที่ไม่มีข้อมูลให้ใช้ค่าดีฟอลต์
    $start_date = 'ไม่ระบุ';
    $employment_type = 'ไม่ระบุ';
    $preferred_location = 'ไม่ระบุ';
    $work_eligibility = 'ไม่ระบุ';
    $expected_salary = 'ไม่ระบุ';
    $interested_job_type = 'ไม่ระบุ';
    $conscription = 'ไม่ระบุ'; // New field
    $work_type = 'ไม่ระบุ'; // New field
    $old = 'ไม่ระบุ'; // New field

    $sex = 'ไม่ระบุ';
    $qualification = 'ไม่ระบุ';
    $course = 'ไม่ระบุ';
    $experience = 'ไม่ระบุ';
}

// Close statement and connection
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
mysqli_close($connect);
?>
