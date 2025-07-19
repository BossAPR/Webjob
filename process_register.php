<?php
session_start();
require('connectdb.php');

$token = generateToken();
echo $token;
//exit();


if(isset($_POST['account_name']) && isset($_POST['account_email']) && isset($_POST['account_password1']) && isset($_POST['account_password2'])){
    /* ใส่ / เมื่อ er and เก็บข้อมูลอักษรพิเศษ*/
    $account_name = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_name'])); 
    $account_email = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_email']));
    $account_password1 = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_password1']));
    $account_password2 = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_password2']));

    $account_realpassword = htmlspecialchars(mysqli_real_escape_string($connect, $_POST['account_password1']));

    if(empty($account_name)){
        die(header('Location: form_register.php')); //ไม่ได้กรอกชื่อผู้ใช้
    }else if(empty($account_email)){
        die(header('Location: form_register.php')); //ไม่ได้กรอกอีเมล
    }else if(empty($account_password1)){
        die(header('Location: form_register.php')); //ไม่ได้กรอกรหัสผ่าน
    }else if(empty($account_password2)){
        die(header('Location: form_register.php')); //ไม่ได้กรอกยืนยันรหัสผ่าน
    }else{
        $query_check_email_account = "SELECT account_name FROM users_account WHERE account_email = '$account_email'"; //หาว่ามีเมลซ้ำไหมเจอจะแสดง
        $call_back_query_check_email_account = mysqli_query($connect, $query_check_email_account); 

        if(mysqli_num_rows($call_back_query_check_email_account)>0){
            die(header('Location: form_register.php')); //มีผู้ใช้เมลนี้แล้ว
        }else{ //hash+salt
            $length = random_int(1, 128);
            $account_salt = bin2hex(random_bytes($length)); //แปลงเลขเป็นฐาน16 สร้างsalt
            $account_password1 = $account_password1 . $account_salt; //เอารหัสผ่านต่อsalt
            //ARGON
            $algo =  PASSWORD_ARGON2ID;
            // เพื่อเพิมเวลา hash
            $options = [
                'cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
                'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
            ];

            //password จริง เป็นการนำ pass ต่อ salt ด้วยวิธี ARGON2ID
            $account_password = password_hash($account_password1, $algo, $options);


            //สร้างใบติดต่อ database
            /*$query_create_account = "INSERT INTO users_account 
            VALUES ('','$account_name','$account_email','$account_realpassword','$account_password',
            '$account_salt','','default_imges_account.jpg','','','','$token')";
            $call_back_create_account = mysqli_query($connect, $query_create_account);*/

            /*$query_create_account = "INSERT INTO users_account 
            (account_id, google_id, account_name, account_email, account_realpassword, account_password
            , account_salt, account_role, account_images, account_countlogin, account_lock, account_ban, oauth_id, last_login, created_at, token) 
            VALUES ('', '', '$account_name', '$account_email', '$account_realpassword', '$account_password', '$account_salt'
            , 'member', 'default_imges_account.jpg', 0, 0, NULL, '', NULL, NOW(), '$token')";

            $call_back_create_account = mysqli_query($connect, $query_create_account);*/
 

            //Google OAuth
            
            $google_id = $userInfo->id; // assuming you get this from Google API
            /*$query_create_account = "INSERT INTO users_account 
            (account_id, google_id, account_name, account_email, account_realpassword, account_password, account_salt
            , account_role, account_images, account_countlogin, account_lock, account_ban, oauth_id, last_login, created_at, token) 
            VALUES ('', '$google_id', '$account_name', '$account_email', '$account_realpassword', '$account_password', '$account_salt'
            , 'member', 'default_imges_account.jpg', 0, 0, NULL, '', NULL, NOW(), '$token')";*/

            $query_create_account = "INSERT INTO users_account 
            (account_id, google_id, account_name, account_email, account_realpassword, account_password, account_salt, account_role
            , account_images, account_countlogin, account_lock, account_ban, oauth_id, last_login, created_at, token, birthday, gender, addresses, phone_numbers) 
            VALUES ('', '', '$account_name', '$account_email', '$account_realpassword', '$account_password', '$account_salt', 'user'
            , 'default_images_account.jpg', 0, 0, NULL, '', NULL, NOW(), '$token', 'N/A', 'N/A', 'N/A', 'N/A')";


            $call_back_create_account = mysqli_query($connect, $query_create_account);


            //สร้างลิงก์ยืนยัน: --------------------------------------------------------------------------------------------------------------
            $link = "http://localhost/webjob/confirm_email.php?token=$token&email=$account_email";

            //ตั้งค่าเนื้อหาอีเมล: --------------------------------------------------------------------------------------------------------------
            $subject = "ยืนยันอีเมลของคุณสำหรับบัญชี [ชื่อเว็บไซต์ของคุณ]";
            $message = "สวัสดี $account_name,\n\n";
            $message .= "กรุณาคลิกลิงก์ด้านล่างเพื่อยืนยันอีเมลของคุณ:\n";
            $message .= "$link\n\n";
            $message .= "หากคุณไม่ได้เป็นผู้ลงทะเบียนบัญชีนี้ โปรดละเลยอีเมลนี้\n\n";
            $message .= "ขอแสดงความนับถือ,\n";
            $message .= "[ชื่อเว็บไซต์ของคุณ]";

            //ส่งอีเมล: --------------------------------------------------------------------------------------------------------------
            if (mail($account_email, $subject, $message)) {
                // อีเมลถูกส่งสำเร็จ
                echo 'อีเมลถูกส่งสำเร็จ';
            } else {
                // อีเมลส่งล้มเหลว
                echo 'อีเมลส่งล้มเหลว';
            }
            
            //สร้าง admin กับ user ******************************************************************************************************
            
            $data = "SELECT * FROM users_account";
            $result = mysqli_query($connect, $data);

            // ตรวจสอบว่าข้อมูลตรงกับคำว่า "admin" หรือไม่
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $accountName = $row["account_name"];

                    // เพิ่มคำว่า "admin" ลงในช่อง account_role
                    if (strpos($accountName, "admin") !== false) {
                    $sqlUpdate = "UPDATE users_account SET account_role = 'admin' WHERE account_name = '$accountName'";
                    mysqli_query($connect, $sqlUpdate);
                    }else{
                    $sqlUpdate = "UPDATE users_account SET account_role = 'user' WHERE account_name = '$accountName'";
                    mysqli_query($connect, $sqlUpdate);
                    }
                }
            } else {
                echo "0 results";
              }



            if($call_back_create_account){
                die(header('Location: form_login.php')); // สร้างบัญชีสำเร็จ
            }else{
                die(header('Location: form_register.php'));
            }
        }
    }
}
else{
    die(header('Location: form_register.php'));
}
?>
<?php
function generateToken() {
    $token = bin2hex(random_bytes(32)); // สร้างค่าโทเค็นสุ่ม 32 ไบต์
    return $token;
}
?>