<?php
session_start();
require('connectdb.php');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "DELETE FROM job_ad WHERE job_ad_id=$id";

    if ($connect->query($sql) === TRUE) {
        header("Location: manage-jobs.php");
        exit();
    } else {
        echo "Error: " . $connect->error;
    }
}
?>
