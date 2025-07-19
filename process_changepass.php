<?php

require('connectdb.php');
session_start();


/*if (isset($_POST['account_email'])) {
    // รับค่าตัวแปรจาก URL
    $account_email = $_POST['account_email'];
    echo $account_email;
}*/
    


// รับค่าอีเมลจาก URL
//$email_forget = $_POST['account_email'];
//echo $email_forget;

//เชื่อมดาตร้าเบส
//$data = "SELECT * FROM users_account WHERE account_email='$email_forget' "  ;
//$result = mysqli_query($connect, $data);

//$num=mysqli_num_rows($result);

//แบ่งข้อมูลเป็นฟิลเพื่อเอาไปใช้งาน
//$recode=mysqli_fetch_array($result);
/*$to=$recode['account_email'];
$password=$recode['account_realpassword'];
if ($email_forget==""){
    echo "<h3>ERROR : Please Enter Username<h3>"; exit();
}*/


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าตัวแปรจากฟอร์ม
    $new_password = $_POST['new_password'];
    $account_email = $_POST['account_email'];
   
}

if(isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['confirm_new_password']) ){
    $old_password = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['old_password']));
    $new_password = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['new_password']));
    $confirm_new_password = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['confirm_new_password']));

    $newrealpass = $new_password;

//echo $_POST['old_password'] . " "  . $old_password;
//echo $_POST['new_password'] . " "  . $new_password;
//echo $_POST['confirm_new_password'] . " "  . $confirm_new_password;
//$account_id = $_POST['account_id'];
//echo $account_email;


    // ตรวจสอบรหัสผ่านเก่า
    $query_check_old_password = "SELECT account_realpassword FROM users_account WHERE account_email = '$account_email'";
    $result_check_old_password = mysqli_query($connect, $query_check_old_password);
    $row_check_old_password = mysqli_fetch_assoc($result_check_old_password);
    $old_password_hash = $row_check_old_password['account_realpassword'];

    //echo $old_password_hash . " ";
    //ลอง
    $query_check_account = "SELECT * FROM users_account WHERE account_email = '$account_email'";
    $call_back_check_account = mysqli_query($connect, $query_check_account);
    
    if(mysqli_num_rows($call_back_check_account) == 1){ //บัญชีต้องน้อยกว่า 1
        $result_check_account = mysqli_fetch_assoc($call_back_check_account); //ผลลัพท์ที่ได้ถ้ามีข้อมูล
        $hash = $result_check_account['account_password']; //รหัสที่ถูกแปลง
        $account_password = $old_password . $result_check_account['account_salt']; //รหัสที่กรอกมา รวมกับ salt จะรู้ได้ไง salt ที่เอามาถูกก็เช็คจากเมลที่กรอก
        //echo " " . $account_password . " ";

        //ARGON
        $algo =  PASSWORD_ARGON2ID;
        // เพื่อเพิมเวลา hash
        $options = [
            'cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
        ];
        //password จริง เป็นการนำ pass ต่อ salt ด้วยวิธี ARGON2ID
        $account_password = password_hash($account_password, $algo, $options);
        //echo " " . $account_password . " ";
    }


    //echo $old_password;

    /*if(!password_verify($old_password, $old_password_hash)){
        die('รหัสผ่านเก่าไม่ถูกต้อง');
    }*/

    // ตรวจสอบรหัสผ่านใหม่
    if($new_password != $confirm_new_password){
        //die('รหัสผ่านใหม่ไม่ตรงกัน');
        echo "<script>
        alert('รหัสผ่านใหม่ไม่ตรงกัน!');
        window.location.href = 'form_login.php';
        </script>";
    }

    /*if(!password_verify($old_password, $old_password_hash)){
        die('รหัสผ่านไม่ถูกต้อง');
    }*/

    // เปลี่ยนรหัสผ่าน
    $length = random_int(1, 128);
    $account_salt = bin2hex(random_bytes($length));
    $new_password = $new_password . $account_salt;
    $algo =  PASSWORD_ARGON2ID;
    $options = [
        'cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
        'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
        'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
    ];
    $new_password_hash = password_hash($new_password, $algo, $options);

    $query_update_password = "UPDATE users_account SET account_realpassword='$newrealpass',account_password = '$new_password_hash', account_salt = '$account_salt' WHERE account_email = '$account_email'";
    $result_update_password = mysqli_query($connect, $query_update_password);

    if($result_update_password){
        //echo 'เปลี่ยนรหัสผ่านสำเร็จ';
        echo "<script>
        alert('เปลี่ยนรหัสผ่านสำเร็จ!');
        window.location.href = 'form_login.php';
        </script>";
    }else{
        //echo 'เปลี่ยนรหัสผ่านล้มเหลว';
        echo "<script>
        alert('เปลี่ยนรหัสผ่านล้มเหลว!');
        window.location.href = 'form_login.php';
        </script>";
    }
}
/*else{
    echo 'กรุณาป้อนข้อมูลให้ครบถ้วน';
}*/

?>