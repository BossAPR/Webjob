<!--?php
session_start();
require 'vendor/autoload.php';
require('connectdb.php');
//$client = new Google_Client();
$client = new Google_Client(['client_id' => "112743898689763707582"]);

$client->setAuthConfig('webjob-426516-54d8fef9d2d1.json');
$client->setRedirectUri('http://localhost/webjob/callback.php'); // ตั้งค่า URI ของ callback.php
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

if (!isset($_GET['code'])) {
    // ถ้าไม่มีโค้ดจาก Google ให้ไปที่หน้าลงชื่อเข้าใช้
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    // ถ้ามีโค้ดจาก Google ให้รับโค้ดและขอโทเค็น
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // รับข้อมูลโปรไฟล์จาก Google
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();
    $email = $userInfo->email;
    $name = $userInfo->name;

    // ตรวจสอบว่าผู้ใช้มีบัญชีอยู่แล้วหรือไม่
    
    if (mysqli_ping($connect)) {
        echo "Connected to MySQL database\n";
    } else {
        echo "Failed to connect to MySQL database: " . mysqli_error($connect) . "\n";
        die();
    }

    $query_check_email_account = "SELECT account_name FROM users_account WHERE account_email = '$email'";
    $call_back_query_check_email_account = mysqli_query($connect, $query_check_email_account);

    if (mysqli_num_rows($call_back_query_check_email_account) > 0) {
        // มีผู้ใช้งานนี้อยู่แล้ว ให้เข้าสู่ระบบ
        $_SESSION['user_email'] = $email;
        header('Location: form_login.php');
    } else {
        // ไม่มีผู้ใช้งานนี้ ให้ลงทะเบียนใหม่
        $token = bin2hex(random_bytes(32));
        $account_password = ''; // ผู้ใช้ที่ลงทะเบียนผ่าน Google จะไม่ต้องมีรหัสผ่าน
        $account_salt = ''; // ไม่ต้องใช้ salt
        $account_role = 'member'; // ตั้งค่าบทบาทเริ่มต้น

        $query_create_account = "INSERT INTO users_account (account_name, account_email, account_password, account_salt, account_role, token) VALUES ('$name', '$email', '$account_password', '$account_salt', '$account_role', '$token')";
        $call_back_create_account = mysqli_query($connect, $query_create_account);

        if ($call_back_create_account) {
            $_SESSION['user_email'] = $email;
            header('Location: form_register.php');
        } else {
            die(header('Location: form_register.php'));
        }
    }
}
?-->
<!-- *********************************************************************************************************************************************** -->

<?php
ob_start();
session_start();

include('connectdb.php');

if(isset($_SESSION['logged_in'])){
    header ('location: indextest.php');
    exit;
}

date_default_timezone_set("Asia/Bangkok");
require_once 'vendor/autoload.php';

$client_id = '448469483185-onb33prl496rcpkb74qjqs39ukvh40au.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'http://localhost/webjob/callback.php';

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope('email');
$client->addScope('profile');

if(isset($_GET['code'])){
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if(!isset($token['error'])){
        $client->setAccessToken($token['access_token']);
        $service = new Google_Service_Oauth2($client);
        $profile = $service->userinfo->get();

        $email = $profile['email'];
        $g_id = $profile['id'];
        $account_images = $profile['picture'];
        $full_name = $profile['name'];
        $currtime = date('Y-m-d H:i:s');

        $fname = $profile['fname'];
        $lname = $profile['lname'];

        // แก้ใหม่เพิ่มมา
        $birthday = isset($profile['birthday']) ? $profile['birthday'] : 'N/A';
        $gender = isset($profile['gender']) ? $profile['gender'] : 'N/A';
        $addresses = 'N/A'; // Address data is not available from the Google Oauth2 service
        $phonenumbers = 'N/A'; // Phone number data is not available from the Google Oauth2 service


        // ตรวจสอบว่ามีค่า google_id ซ้ำหรือไม่
        $query_check = 'SELECT * FROM users_account WHERE oauth_id = "'.$g_id.'"';
        $run_query_check = mysqli_query($connect, $query_check);

        /*if (mysqli_num_rows($run_query_check) == 0) {
            $query_insert = 'INSERT INTO users_account (account_name, account_email, oauth_id, last_login, account_images) 
            VALUES ("'.$full_name.'","'.$email.'","'.$g_id.'","'.$currtime.'","'.$account_images.'")';
            mysqli_query($connect, $query_insert);
        } else {
            $query_update = 'UPDATE users_account SET account_name="'.$full_name.'", account_email="'.$email.'", last_login="'.$currtime.'", account_images="'.$account_images.'" WHERE oauth_id="'.$g_id.'"';
            mysqli_query($connect, $query_update);
        }*/

        if (mysqli_num_rows($run_query_check) == 0) {
            $query_insert = 'INSERT INTO users_account (account_name, account_email, oauth_id, last_login, account_images, birthday, gender, addresses, phone_numbers, account_role) 
            VALUES ("'.$full_name.'","'.$email.'","'.$g_id.'","'.$currtime.'", "'.$account_images.'", "'.$birthday.'", "'.$gender.'", "'.$addresses.'", "'.$phonenumbers.'", "user")';
            mysqli_query($connect, $query_insert);

            // ดึง account_id ของผู้ใช้ที่เพิ่งสร้างใหม่
            $account_id = mysqli_insert_id($connect);


        } else {
            /*update ค่าตลอดตอน login
            $query_update = 'UPDATE users_account SET account_name="'.$full_name.'", account_email="'.$email.'", last_login="'.$currtime.'", account_images="'.$account_images.'", birthday="'.$birthday.'", gender="'.$gender.'", addresses="'.$addresses.'", phone_numbers="'.$phonenumbers.'" WHERE oauth_id="'.$g_id.'"';
            mysqli_query($connect, $query_update);*/

                //เพิ่มใหม่แก้ update ค่าตลอดตอน login
                $row = mysqli_fetch_assoc($run_query_check);

                //ใช้ไม่ได้ error needs_update = false อย่างเดียว
                $needs_update = false;
                if ($row['account_name'] != $full_name || 
                $row['account_email'] != $email || 
                $row['account_images'] != $account_images ||
                $row['birthday'] != $birthday ||
                $row['gender'] != $gender ||
                $row['addresses'] != $addresses ||
                $row['phone_numbers'] != $phonenumbers) {
        
                $needs_update = true;
                }
                //แก้ขัดได้
                if ($needs_update==false) {
                $query_update = 'UPDATE users_account SET account_name="'.$full_name.'", account_email="'.$email.'", last_login="'.$currtime.'", account_images="'.$account_images.'", birthday="'.$birthday.'", gender="'.$gender.'", addresses="'.$addresses.'", phone_numbers="'.$phonenumbers.'" WHERE oauth_id="'.$g_id.'"';
                mysqli_query($connect, $query_update);
                }
            // ดึง account_id ของผู้ใช้ที่มีอยู่แล้ว
            $query_get_account_id = 'SELECT account_id FROM users_account WHERE oauth_id = "'.$g_id.'"';
            $result_get_account_id = mysqli_query($connect, $query_get_account_id);
            $row = mysqli_fetch_assoc($result_get_account_id);
            $account_id = $row['account_id'];
        }

         $_SESSION['logged_in'] = true;
        $_SESSION['access_token'] = $token['access_token'];
        $_SESSION['uname'] = $full_name;
        $_SESSION['account_images'] = $account_images;
        
        $_SESSION['fname'] = $full_name;
        $_SESSION['lname'] = $full_name;
        $_SESSION['date'] = $currtime;
        $_SESSION['birthday'] = $birthday;
        $_SESSION['gender'] = $gender;
        $_SESSION['addresses'] = $addresses;
        $_SESSION['phonenumbers'] = $phonenumbers;

        $_SESSION['account_id'] = $account_id; // เพิ่มการตั้งค่า account_id ใน session
        header('Location: indextest.php');
    } else {
        echo "Login again";
    }
}

ob_end_flush();
?>


<!-- *********************************************************************************************************************************************** -->
<!--?php 
session_start();

include('connectdb.php');
if(isset($_SESSION['logged_in'])){
    header ('location: form_login.php');
}
    
date_default_timezone_set("Asia/bangkok");
    //library
    require_once 'vendor/autoload.php';
    //client id ,client secret, redirect uri
    $client_id = '448469483185-onb33prl496rcpkb74qjqs39ukvh40au.apps.googleusercontent.com';
    $client_secret = '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';
    $redirect_uri = 'http://localhost/webjob/callback.php';

    //google client
    $client = new Google_Client();

    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri);

    $client->addScope('email');
    $client->addScope('profile');  

    if(isset($_GET['code'])){
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        

        if(!isset($token['error'])){
            $client->setAccessToken($token['access_token']);
            //google service oauth2
            $service = new Google_Service_Oauth2($client);
            $profile = $service->userinfo->get();
            
            $email = $profile['email'];

            //เพิ่มที่หลัง
            $query_check_email_account = "SELECT account_name FROM users_account WHERE account_email = '$email'";
            $call_back_query_check_email_account = mysqli_query($connect, $query_check_email_account);

            if (mysqli_num_rows($call_back_query_check_email_account) > 0) {
                // มีผู้ใช้งานนี้อยู่แล้ว ให้เข้าสู่ระบบ
                $_SESSION['user_email'] = $email;
                header('Location: form_login.php');
            } else {
                // ไม่มีผู้ใช้งานนี้ ให้ลงทะเบียนใหม่
                $token = bin2hex(random_bytes(32));
                $account_password = ''; // ผู้ใช้ที่ลงทะเบียนผ่าน Google จะไม่ต้องมีรหัสผ่าน
                $account_salt = ''; // ไม่ต้องใช้ salt
                $account_role = 'member'; // ตั้งค่าบทบาทเริ่มต้น
        
            //อันเก่า
            $g_name = $profile['name'];
            $g_email = $profile['email'];
            $g_id = $profile['id'];

            $currtime = date('Y-m-d H:i:s');


            $query_check = 'select * from users_account where oauth_id = "'.$g_id.'"';
            $run_query_check = mysqli_query($connect, $query_check);
            $d = mysqli_fetch_object($run_query_check);

            if($d){
                $query_update = 'update users_account set account_name="'.$g_name.'", account_email="'.$g_email.'",
                 last_login= "'.$currtime.'" where oauth_id= "'.$g_id.'"';
                $run_query_update = mysqli_query($connect, $query_update);


                //เพิ่มที่หลัง
                $query_create_account = "INSERT INTO users_account (account_name, account_email, account_password, account_salt, account_role, token) 
                VALUES ('$name', '$email', '$account_password', '$account_salt', '$account_role', '$token')";
                $call_back_create_account = mysqli_query($connect, $query_create_account);



            }else{
                $query_insert = 'insert into users_account (account_name, account_email, oauth_id, last_login) 
                value ("'.$g_name.'","'.$g_email.'","'.$g_id.'","'.$currtime.'")';
                $run_query_insert = mysqli_query($connect, $query_insert);


                //เพิ่มที่หลัง
                $query_create_account = "INSERT INTO users_account (account_name, account_email, account_password, account_salt, account_role, token) 
                VALUES ('$name', '$email', '$account_password', '$account_salt', '$account_role', '$token')";
                $call_back_create_account = mysqli_query($connect, $query_create_account);




            }

            $_SESSION['logged_in'] = true;
            $_SESSION['access_token'] = $token['access_token'];
            $_SESSION['uname'] = $g_name;
            $_SESSION['date'] = $currtime;

            header('location: form_login.php');


            }

        }else{
            echo "Login again";
        }
        
    }
   
?-->

<!--?php
session_start();
require 'vendor/autoload.php';
require('connectdb.php');
//$client = new Google_Client();
$client = new Google_Client(['client_id' => "112743898689763707582"]);

$client->setAuthConfig('webjob-426516-54d8fef9d2d1.json');
$client->setRedirectUri('http://localhost/webjob/callback.php'); // ตั้งค่า URI ของ callback.php
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

if (!isset($_GET['code'])) {
    // ถ้าไม่มีโค้ดจาก Google ให้ไปที่หน้าลงชื่อเข้าใช้
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    // ถ้ามีโค้ดจาก Google ให้รับโค้ดและขอโทเค็น
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // รับข้อมูลโปรไฟล์จาก Google
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();
    $email = $userInfo->email;
    $name = $userInfo->name;

    // ตรวจสอบว่าผู้ใช้มีบัญชีอยู่แล้วหรือไม่
    
    if (mysqli_ping($connect)) {
        echo "Connected to MySQL database\n";
    } else {
        echo "Failed to connect to MySQL database: " . mysqli_error($connect) . "\n";
        die();
    }

    $query_check_email_account = "SELECT account_name FROM users_account WHERE account_email = '$email'";
    $call_back_query_check_email_account = mysqli_query($connect, $query_check_email_account);

    if (mysqli_num_rows($call_back_query_check_email_account) > 0) {
        // มีผู้ใช้งานนี้อยู่แล้ว ให้เข้าสู่ระบบ
        $_SESSION['user_email'] = $email;
        header('Location: form_login.php'); // เปลี่ยนเป็น form_login.php
    } else {
        // ไม่มีผู้ใช้งานนี้ ให้ลงทะเบียนใหม่
        $token = bin2hex(random_bytes(32));
        $account_password = ''; // ผู้ใช้ที่ลงทะเบียนผ่าน Google จะไม่ต้องมีรหัสผ่าน
        $account_salt = ''; // ไม่ต้องใช้ salt
        $account_role = 'member'; // ตั้งค่าบทบาทเริ่มต้น

        $query_create_account = "INSERT INTO users_account (account_name, account_email, account_password, account_salt, account_role, token) VALUES ('$name', '$email', '$account_password', '$account_salt', '$account_role', '$token')";
        $call_back_create_account = mysqli_query($connect, $query_create_account);

        if ($call_back_create_account) {
            // ไม่ต้องตั้ง session ให้ตรงนี้
            header('Location: form_login.php'); // เปลี่ยนเป็น form_login.php
        } else {
            die(header('Location: form_register.php'));
        }
    }
}
?-->
