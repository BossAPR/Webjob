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



if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // ตรวจสอบอีเมลและรหัสผ่านจากฐานข้อมูล
  $query = "SELECT * FROM users_account WHERE account_email = '$email'";
  $result = mysqli_query($connect, $query);
  $user = mysqli_fetch_assoc($result);

  if ($user && password_verify($password, $user['account_password'])) {
      // ตั้งค่า session
      $_SESSION['logged_in'] = true;
      $_SESSION['account_id'] = $user['account_id'];
      $_SESSION['uname'] = $user['account_name'];

      $_SESSION['fname'] = $user['first_name'];
      $_SESSION['lname'] = $user['last_name'];
      //$_SESSION['account_images'] = 'webjob/assets/account_images/' . $user['account_images']; // ตรวจสอบว่ามีการตั้งค่า session สำหรับรูปภาพโปรไฟล์
      $_SESSION['email'] = $user['account_email'];


      // Set the image path based on database value or default image
      $_SESSION['account_images'] = !empty($user['account_images']) ? 'webjob/assets/account_images/' . $user['account_images'] : 'webjob/assets/account_images/default_images_account.jpg';

      // ไปที่หน้าโปรไฟล์
      header('Location: edit_profile.php');
      exit;
  } else {
      echo "Invalid email or password.";
  }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equi ="X-UA-Compatible" content="IE=edge">
    <meta name= "viewport" content="width=device-wigth, initial-scale=1.0">
    
    
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/css.css" />

    <title>เข้าสู่ระบบ</title>
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
    .Forget-Pass{
      display: flex;
      width: 65%;
    }
    .Forget{
      color: #2E9AFE;
      font-weight: 500;
      text-decoration: none;
      margin-left: auto;
      
    }
  </style>
</head>

<body>
<div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="process_login.php" method="POST" class="sign-in-form">
          <h2 class="title">เข้าสู่ระบบ</h2>

        <div class="input-field">
        <i class="fas fa-user"></i>
            <input name="account_email" type="text" placeholder="อีเมล" required>
        </div>

        <div class="input-field">
        <i class="fas fa-lock"></i>
            <input name="account_password" type="password" placeholder="รหัสผ่าน" required>
        </div>

        <div class="Forget-Pass">
        <a href="Forget.php" class="Forget">ลืมรหัสผ่าน ?</a></div>

        <input type="submit" name="submit" value="เข้าสู่ระบบ" class="btn solid" />

        <p class="social-text">Or Sign in with social platforms</p>
          <div class="social-media">

            <a href="<?= $client->createAuthUrl(); ?>" class="field google">
              <img src="assets/account_images/google.png" alt="" class="google-img" style="width: 25px;">
              <span>Login with Google</span>
            </a>

          </div>

</div></div></div>

    </form>


    <div class="panels-container">
      <div class="panel left-panel"><center>
        <div class="content">
          <h3>ผู้ใช้ใหม่ ?!</h3>
          <p>
          ยังไม่มีบัญชีใช่หรือไม่?
          </p>
          <a href="form_register.php" class="btn transparent" id="sign-in-btn" style="padding:10px 20px;text-decoration:none">
          สร้างบัญชีใหม่
          </a>
        </div>
        <img src="assets/account_images/log.svg" class="image" alt="" />
      </div>
    </div>
  </div>

</body>
</html>