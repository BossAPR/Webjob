<?php
// เชื่อมต่อกับฐานข้อมูล MySQL
$servername = "localhost"; // เปลี่ยนเป็นชื่อโฮสต์ของคุณ
$username = "root"; // เปลี่ยนเป็นชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = ""; // เปลี่ยนเป็นรหัสผ่านของคุณ
$dbname = "webjob"; // ชื่อฐานข้อมูลที่คุณสร้าง

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจากฟอร์ม
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$company = $_POST['company'];
$position = $_POST['position'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$jobType = $_POST['jobType'];
$details = $_POST['details'];
$notify = isset($_POST['notify']) ? 1 : 0;
$source = isset($_POST['source']) ? $_POST['source'] : '';

// เตรียมคำสั่ง SQL สำหรับการ Insert ข้อมูล
$sql = "INSERT INTO contact_form (first_name, last_name, company, position, phone, email, employee_type, details, subscribe_updates, found_from) 
VALUES ('$firstName', '$lastName', '$company', '$position', '$phone', '$email', '$jobType', '$details', '$notify', '$source')";

// ตรวจสอบและแสดงผลการบันทึกข้อมูล
if ($conn->query($sql) === TRUE) {
    echo "ข้อมูลถูกบันทึกเรียบร้อยแล้ว";
} else {
    echo "เกิดข้อผิดพลาด: " . $sql . "<br>" . $conn->error;
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
