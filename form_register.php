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
        header('Location: dashboard.php');
    } else {
        // ไม่มีผู้ใช้งานนี้ ให้ลงทะเบียนใหม่
        $token = bin2hex(random_bytes(32));
        $account_password = ''; // ผู้ใช้ที่ลงทะเบียนผ่าน Google จะไม่ต้องมีรหัสผ่าน
        $account_salt = ''; // ไม่ต้องใช้ salt
        $account_role = 'user'; // ตั้งค่าบทบาทเริ่มต้น

        $query_create_account = "INSERT INTO users_account (account_name, account_email, account_password, account_salt, account_role, token) VALUES ('$name', '$email', '$account_password', '$account_salt', '$account_role', '$token')";
        $call_back_create_account = mysqli_query($connect, $query_create_account);

        if ($call_back_create_account) {
            $_SESSION['user_email'] = $email;
            header('Location: dashboard.php');
        } else {
            die(header('Location: form_register.php'));
        }
    }
}
?-->


<?php
// รวม Google API client library
require_once 'vendor/autoload.php';

// Client ID, Client Secret, and Redirect URI
$client_id = '448469483185-onb33prl496rcpkb74qjqs39ukvh40au.apps.googleusercontent.com';
$client_secret = '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';
$redirect_uri = 'http://localhost/webjob/callback.php';

// Google client
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);

$client->addScope('email');
$client->addScope('profile');
?>


<!-- ********************************************************************************************************************************************   -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>

    
    <script src="https://apis.google.com/js/platform.js" async defer></script>

    <meta name="google-signin-client_id" content="448469483185-onb33prl496rcpkb74qjqs39ukvh40au.apps.googleusercontent.com">

    <link rel="stylesheet" href="assets/css/css.css" />
    <title>Sign in & Sign up Form</title>
    <style>
        .alert {
            padding: 1rem;
            border-radius: 5px;
            color: white;
            margin: 1rem 0;
            font-weight: 500;
            width: 65%;
        }
        .alert-success {
            background-color: #42ba96;
        }
        .alert-danger {
            background-color: #fc5555;
        }
        .alert-info {
            background-color: #2E9AFE;
        }
        .alert-warning {
            background-color: #ff9966;
        }

        .g-signin2 {
            width: 300px;
            margin: 0 auto;
            display: block;
        }
        #profile {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border: 1px solid #ccc;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #profile img {
            max-width: 100px;
        }
        #profile button {
            margin-top: 10px;
        }


    </style>
</head>
<body>
    <div class="container sign-up-mode">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="process_register.php" method="POST" class="sign-up-form">
                    <h2 class="title">สร้างบัญชีของคุณ</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="account_name" placeholder="ชื่อผู้ใช้" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="text" name="account_email" placeholder="อีเมล" />
                    </div>
                    <div class="input-field" >
                        <i class="fas fa-lock"></i>
                        <input type="password" name="account_password1" placeholder="รหัสผ่านใหม่" />
                    </div>
                    <div class="input-field" >
                        <i class="fas fa-lock"></i>
                        <input type="password" name="account_password2" placeholder="ยืนยันรหัสผ่าน" />
                    </div>
                    <input type="submit" name="submit" class="btn" value="สร้างบัญชี" />
                    <p class="social-text">Or Sign up with social platforms</p>
                    <div class="social-media">


                        <!--div id="gSignIn" class="g-signin2 social-icon" data-onsuccess="onSuccess" data-onfailure="onFailure">
                            <i class="fab fa-google"></i>
                        </div-->

                        <!--a href="#" class="social-icon" id="gSignIn">
                        <i class="fab fa-google"></i>
                        </a-->

                        <!--div id="gSignIn" class="g-signin2" data-onsuccess="onSuccess" data-onfailure="onFailure"></div-->



                        <a href="<?= $client->createAuthUrl(); ?>" class="field google">
                        <img src="assets/account_images/google.png" alt="" class="google-img" style="width: 25px;">
                        <span>Login with Google</span>
                        </a>


                    </div>
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>มีบัญชีอยู่แล้วใช่หรือไม่?</h3>
                    <p>
                        ถ้ามีบัญชีอยู่แล้วคุณสามารถกดลงทะเบียนได้เลย!!
                    </p>
                    <a href="form_login.php" class="btn transparent" id="sign-in-btn" style="padding:10px 20px;text-decoration:none">
                    เข้าสู่ระบบ
                    </a>
                </div>
                <img src="assets/account_images/register.svg" class="image" alt="" />
            </div>
        </div>
    </div>







    
    <!--         ********************************************************   1     *****************************************************************
    
    
    script src="https://apis.google.com/js/platform.js" async defer></script-->
    <!--script>

    function onSuccess(googleUser) {
    console.log('Google sign-in successful');
    var profile = googleUser.getBasicProfile();
    var id_token = googleUser.getAuthResponse().id_token;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'process_google_login.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Signed in as: ' + xhr.responseText);
            window.location.href = 'dashboard.php';
        } else {
            console.log('Sign-in failed: ' + xhr.responseText);
        }
    };
    xhr.send('idtoken=' + id_token);
}

    

    function renderButton() {
    console.log('Rendering button');
    gapi.signin2.render('gSignIn', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSuccess,
        'onfailure': onFailure
    });
}

    // เรียกใช้ฟังก์ชัน renderButton เมื่อหน้าเว็บถูกโหลด
    window.onload = function() {
    console.log('Window loaded');
    gapi.load('auth2', function() {
        console.log('gapi loaded');
        gapi.auth2.init().then(function() {
            console.log('gapi initialized');
            renderButton();
        }, function(error) {
            console.log('gapi initialization failed:', error);
        });
    });
};

function onFailure(error) {
    console.log('Google sign-in failed:', error);
}

</script-->




<!--script>
        function onSuccess(googleUser) {
            var profile = googleUser.getBasicProfile();
            var id_token = googleUser.getAuthResponse().id_token;

            // แสดงข้อมูลผู้ใช้ใน Popup
            document.getElementById('profile-id').textContent = profile.getId();
            document.getElementById('profile-name').textContent = profile.getName();
            document.getElementById('profile-email').textContent = profile.getEmail();
            document.getElementById('profile-img').src = profile.getImageUrl();
            document.getElementById('profile').style.display = 'block';

            // ส่ง token ไปยังเซิร์ฟเวอร์
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'callback.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Signed in as: ' + xhr.responseText);
                } else {
                    console.log('Sign-in failed: ' + xhr.responseText);
                }
            };
            xhr.send('idtoken=' + id_token);
        }

        function onFailure(error) {
            console.log('Google sign-in failed:', error);
        }

        function renderButton() {
            gapi.signin2.render('gSignIn', {
                'scope': 'profile email',
                'width': 300,
                'height': 50,
                'longtitle': true,
                'theme': 'dark',
                'onsuccess': onSuccess,
                'onfailure': onFailure
            });
        }


        function closePopup() {
            document.getElementById('profile').style.display = 'none';
        }


        window.onload = function() {
            gapi.load('auth2', function() {
                gapi.auth2.init().then(function() {
                    renderButton();
                });
            });
        };

    </script-->

</body>
</html>
