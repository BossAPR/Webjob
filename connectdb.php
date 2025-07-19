<?php

/* if($open_connect != 1){
    die(header('Location: form-login.php'));
} */

/* Local Database*/

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webjob";

$port = NULL;
$socket = NULL;
$lock = NULL;

/*
// Create connection
$connect = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error($connect));
}*/


// Create connection
$connect = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}



else {
    //echo "Successfully connected";
    //mysqli_set_charset($connect,'utf8');
    
    $limit_login_account = 3; //จำนวนครั้งที่กรอกรหัสผ่านผิดได้
    $time_ban_account = 1; //จำนวนนาทีที่ระงับบัญชี

    $query_reset_ban_account = "UPDATE users_account SET account_lock = 0, account_countlogin = 0 
    WHERE account_ban <= NOW() AND account_countlogin >= '$limit_login_account'"; //แก้โดนแบนตามเวลา
    
    $call_back_reset_ban_account = mysqli_query($connect, $query_reset_ban_account);
  }
?> 