<?php
session_start();
require('connectdb.php');

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];

    // Update job status to approved
    $sql = "UPDATE job_ad SET job_status = 'Pending' WHERE job_ad_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $job_id);

    if ($stmt->execute()) {
        // Redirect back to the approve-job.php page with success message
        $_SESSION['message'] = "งานได้รับการอนุมัติเรียบร้อยแล้ว!";
        header("Location: approve-job.php");
        exit();
    } else {
        // Handle error
        $_SESSION['error'] = "ไม่สามารถอนุมัติงานได้!";
        header("Location: approve-job.php");
        exit();
    }
} else {
    // Redirect back if no ID is provided
    header("Location: approve-job.php");
    exit();
}
?>
