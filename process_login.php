<?php
session_start(); //เรียกตัวแปร
require('connectdb.php');

//เช็ค login เข้าได้ไหมเข้าได้เป็นไง
if(isset($_POST['account_email']) && isset($_POST['account_password'])){
    

    //เก็บตัวแปร
    $account_email = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_email']));
    $account_password = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_password']));
    //สร้างใบ check
    
    $query_check_account = "SELECT * FROM users_account WHERE account_email = '$account_email'";
    $call_back_check_account = mysqli_query($connect, $query_check_account);
    if(mysqli_num_rows($call_back_check_account) == 1){ //บัญชีต้องน้อยกว่า 1
        $result_check_account = mysqli_fetch_assoc($call_back_check_account); //ผลลัพท์ที่ได้ถ้ามีข้อมูล
        $hash = $result_check_account['account_password']; //รหัสที่ถูกแปลง
        $account_password = $account_password . $result_check_account['account_salt']; //รหัสที่กรอกมา รวมกับ salt จะรู้ได้ไง salt ที่เอามาถูกก็เช็คจากเมลที่กรอก ในบันทัด 12
        $count = $result_check_account['account_countlogin'];
        $ban = $result_check_account['account_ban'];

        $currtime = date('Y-m-d H:i:s');

        echo "Account ID: " . $_SESSION['account_id'];
        echo "Account Role: " . $_SESSION['account_role'];


        if($result_check_account['account_lock'] == 1){ //ทำการ lock account ถ้าใส่ผิด
            echo '<h1>บัญชีนี้ถูกระงับชั่วคราว</h1>';
            echo "<h2>ระงับบัญชีนี้เป็นเวลา $time_ban_account นาที เพราะผู้ใช้กรอกรหัสผ่านผิดจำนวน $count ครั้ง</h2>";
            echo "<h2>บัญชีนี้จะถูกปลดจากการระงับเมื่อถึงเวลา $ban</h2>";
            echo '<a href="form_login.php">กลับไปยังหน้าเข้าสู่ระบบ</a>';

        }elseif(password_verify($account_password, $hash)){ // รหัสจริงหรือเท็จ ถ้าจริงทำเช็คสถานะต่อ
            $query_reset_login_count_account = "UPDATE users_account SET account_countlogin = 0, last_login = '$currtime' WHERE account_email = '$account_email'"; // อัพเดท lock กับ ban ให้กับมาเหมือนเดิมถ้ากรอกถูก
            $call_back_reset = mysqli_query($connect, $query_reset_login_count_account);
            
            
            //check บทบาท ต่อ
            if($result_check_account['account_role'] == 'admin'){
                $_SESSION['account_id'] = $result_check_account['account_id'];
                $_SESSION['account_role'] = $result_check_account['account_role'];
                $_SESSION['logged_in'] = true; // เพิ่ม session logged_in
                echo "Redirecting to admin.php...";
                die(header('Location: admin.php'));
            }
            if($result_check_account['account_role'] == 'user' ){ //บทบาท user หรือ admin
                $_SESSION['account_id'] = $result_check_account['account_id'];
                $_SESSION['account_role'] = $result_check_account['account_role'];
                $_SESSION['logged_in'] = true; // เพิ่ม session logged_in
                die(header('Location: indextest.php'));
            }          
            
        }else{ //lock auto
            $query_login_count_account = "UPDATE users_account SET account_countlogin = account_countlogin + 1 WHERE account_email = '$account_email'";
            $call_back_login_count_account = mysqli_query($connect, $query_login_count_account);
            if($result_check_account['account_countlogin'] + 1 >= $limit_login_account){
                
                $query_lock_account = "UPDATE users_account SET account_lock = 1, account_ban = DATE_ADD(NOW(), INTERVAL $time_ban_account MINUTE) WHERE account_email = '$account_email'";
                $call_back_lock_account = mysqli_query($connect, $query_lock_account); // lock สำเร็จไหม
                
            }
            die(header('Location: form_login.php')); //รหัสผ่านไม่ถูกต้อง
        }

    }else{
        die(header('Location: form_login.php')); //ไม่มีอีเมลนี้ในระบบ
    }

}else{
    die(header('Location: form_login.php')); //กรุณากรอกข้อมูล
}

?>
