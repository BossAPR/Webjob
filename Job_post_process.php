<?php  
session_start(); // Start the session
require('connectdb.php');

if ($_POST) {
    if (isset($_FILES['upload'])) {
        $name_file = $_FILES['upload']['name'];
        $tmp_name = $_FILES['upload']['tmp_name'];
        $locate_img = "assets/account_images/";
        move_uploaded_file($tmp_name, $locate_img . $name_file);
    }

    if (isset($_FILES['uploadverify'])) {
        $name_file2 = $_FILES['uploadverify']['name'];
        $tmp_name = $_FILES['uploadverify']['tmp_name'];
        $locate_img = "verifycompany/";
        move_uploaded_file($tmp_name, $locate_img . $name_file2);
    }
}

include 'connectdb.php';

$user_id = $_SESSION['account_id'];
$query = "SELECT * FROM users_account WHERE account_id = '$user_id'";
$result = mysqli_query($connect, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set and not empty
    $required_fields = ['job_type', 'job_name', 'job_workers', 'job_salary', 'job_time', 'job_welfare', 'job_detail', 'sex', 'age_min', 'age_max', 'qualification', 'course', 'experience_min', 'job_location', 'job_province', 'job_district', 'job_category', 'company_name'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field]) && $field != 'sex') {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        // Set error message and redirect if any fields are missing
        $_SESSION['message'] = "Error: ข้อมูลไม่ครบ - " . implode(', ', $missing_fields);
        $_SESSION['message_type'] = "error";
        header("Location: Job_post.php");
        exit();
    }

    // Collect form data
    $company_name = $connect->real_escape_string($_POST["company_name"]);
    $job_category = $connect->real_escape_string($_POST["job_category"]);
    $job_type = $connect->real_escape_string($_POST['job_type']);
    $job_name = $connect->real_escape_string($_POST['job_name']);
    $job_workers = $connect->real_escape_string($_POST['job_workers']);
    $job_salary = $connect->real_escape_string($_POST['job_salary']);
    $job_time = $connect->real_escape_string($_POST['job_time']);
    $job_welfare = $connect->real_escape_string($_POST['job_welfare']);
    $job_detail = $connect->real_escape_string($_POST['job_detail']);
    $sex = $connect->real_escape_string($_POST['sex']);
    $age_min = $connect->real_escape_string($_POST['age_min']);
    $age_max = $connect->real_escape_string($_POST['age_max']);
    $qualification = $connect->real_escape_string($_POST['qualification']);
    $course = $connect->real_escape_string($_POST['course']);
    $experience_min = $connect->real_escape_string($_POST['experience_min']);
    $job_location = $connect->real_escape_string($_POST['job_location']);
    $job_province = $connect->real_escape_string($_POST['job_province']);
    $job_district = $connect->real_escape_string($_POST['job_district']);

    // Handle expiration period
    if ($_POST['job_expire_at'] === '1_week') {
        $expire_date = date('Y-m-d', strtotime('+1 week'));
    } elseif ($_POST['job_expire_at'] === '1_month') {
        $expire_date = date('Y-m-d', strtotime('+1 month'));
    } elseif ($_POST['job_expire_at'] === '3_month') {
        $expire_date = date('Y-m-d', strtotime('+3 months'));
    } else {
        $expire_date = $_POST['job_expire_at']; // If a specific date is selected
    }

    // Insert data into database
    $sql = "INSERT INTO job_ad (job_type, job_name, job_workers, job_salary, job_time, job_welfare, job_detail, sex, age_min, age_max, qualification, course, experience_min, job_location, job_province, job_district, job_logo, job_status, job_create_at, job_mail, account_id, company_name, job_category, verify_company, job_expire_at) 
    VALUES ('$job_type', '$job_name', '$job_workers', '$job_salary', '$job_time', '$job_welfare', '$job_detail', '$sex', '$age_min', '$age_max', '$qualification', '$course', '$experience_min', '$job_location', '$job_province', '$job_district', '$name_file', 'Pending', NOW(), '{$user['account_email']}', '{$user['account_id']}', '$company_name', '$job_category', '$name_file2', '$expire_date')";

    if ($connect->query($sql) === TRUE) {
        $_SESSION['message'] = "สร้างสำเร็จ successfully";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $connect->error;
        $_SESSION['message_type'] = "error";
    }

    $connect->close();
    header("Location: Job_post.php");
    exit();
}
?>
