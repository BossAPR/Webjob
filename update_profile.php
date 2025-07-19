<?php
session_start();
require('connectdb.php');

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินหรือยัง
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: form_login.php');// เปลี่ยนเส้นทางไปหน้า login ถ้า session ไม่ถูกต้อง
    exit;
}

// ตรวจสอบว่ามีการตั้งค่า session สำหรับ user_id หรือยัง
if (!isset($_SESSION['account_id'])) {
    echo "User ID is not set in the session.";
    exit();
}
$user_id = $_SESSION['account_id'];

/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['first-name']));
    $last_name = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['last-name']));
    $birthday = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['birthday']));
    $gender = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['gender']));
    $addresses = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['addresses']));
    $account_email = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_email']));
    $phone_country = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['phone-country']));
    $phone = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['phone']));

    $full_name = $first_name . ' ' . $last_name;
    $phonenumbers = $phone_country . $phone;
    
    if (isset($_FILES['account_images']) && $_FILES['account_images']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['account_images']['tmp_name'];
        $file_name = basename($_FILES['account_images']['name']);
        $target_dir = 'assets/account_images/';
        $target_file = $target_dir . $user_id . '_' . $file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            // อัปเดตเซสชันสำหรับรูปโปรไฟล์ใหม่
            $_SESSION['account_images'] = $target_file;
            
            $query = "UPDATE users_account SET account_name = '$full_name', birthday = '$birthday', gender = '$gender', addresses = '$addresses', account_email = '$account_email', phone_numbers = '$phonenumbers', account_images = '$target_file' WHERE account_id = '$user_id'";
        } else {
            echo "Error uploading file.";
            exit;
        }
        
    } else {
        $query = "UPDATE users_account SET account_name = '$full_name', birthday = '$birthday', gender = '$gender', addresses = '$addresses', account_email = '$account_email', phone_numbers = '$phonenumbers' WHERE account_id = '$user_id'";
    }

    $result = mysqli_query($connect, $query);

    if ($result) {
        $_SESSION['uname'] = $full_name;
        header('Location: indextest.php');
    } else {
        echo "Error updating profile: " . mysqli_error($connect);
    }

}*/


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['account_id'];
    $name = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['first-name'] . ' ' . $_POST['last-name']));

    $fname = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['first-name']));
    $lname = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['last-name']));

    $birthday = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['birthday']));
    $gender = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['gender']));
    $addresses = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['addresses']));
    $email = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_email']));
    $phonenumbers = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['phone-country'] . $_POST['phone']));

    // จัดการการอัปโหลดรูปภาพ
if (isset($_FILES['account_images']) && $_FILES['account_images']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['account_images']['tmp_name'];
    $file_ext = pathinfo($_FILES['account_images']['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid() . '.' . $file_ext;  // ชื่อไฟล์ใหม่
    $target_dir = 'assets/account_images/';
    $target_file = $target_dir . $new_filename;

    if (move_uploaded_file($file_tmp, $target_file)) {
        // อัปเดตข้อมูลในฐานข้อมูลรวมถึงรูปภาพใหม่
        $query = "UPDATE users_account SET account_name='$name', first_name='$fname', last_name='$lname', birthday='$birthday', gender='$gender', addresses='$addresses', account_email='$email', phone_numbers='$phonenumbers', account_images='$new_filename' WHERE account_id='$user_id'";
        $_SESSION['account_images'] = $target_file;

        $_SESSION['account_images'] = $new_filename;
        session_write_close();
    } else {
        echo "Error uploading file.";
        exit;
    }
} else {
    // กรณีที่ไม่ได้อัปโหลดรูปภาพใหม่
    $query = "UPDATE users_account SET account_name='$name', first_name='$fname', last_name='$lname', birthday='$birthday', gender='$gender', addresses='$addresses', account_email='$email', phone_numbers='$phonenumbers' WHERE account_id='$user_id'";
}

$result = mysqli_query($connect, $query);

if ($result) {
    $_SESSION['uname'] = $name;
    $_SESSION['account_images'] = $new_filename ?? $_SESSION['account_images'];
    header('Location: edit_profile.php');
    exit();
} else {
    echo "Error updating profile: " . mysqli_error($connect);
}

}

    header('Location: edit_profile.php');
    exit();

?>