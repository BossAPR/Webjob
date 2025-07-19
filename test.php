<?php 
session_start();

include('server.php');
if(isset($_SESSION['logged_in'])){
    header ('location: welcome.php');
}
    
date_default_timezone_set("Asia/bangkok");
    //library
    require_once 'vendor/autoload.php';
    //client id ,client secret, redirect uri
    $client_id = '1042796273469-g3opshe5b1sov2rgvugnkl9765mp2589.apps.googleusercontent.com';
    $client_secret = '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';
    $redirect_uri = 'http://localhost/emosense/login.php';

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
            


            $g_name = $profile['name'];
            $g_email = $profile['email'];
            $g_id = $profile['id'];

            $currtime = date('Y-m-d H:i:s');


            $query_check = 'select * from users where oauth_id = "'.$g_id.'"';
            $run_query_check = mysqli_query($conn, $query_check);
            $d = mysqli_fetch_object($run_query_check);

            if($d){
                $query_update = 'update users set fullname="'.$g_name.'", email="'.$g_email.'",
                 last_login= "'.$currtime.'" where oauth_id= "'.$g_id.'"';
                $run_query_update = mysqli_query($conn, $query_update);

            }else{
                $query_insert = 'insert into users (fullname, email, oauth_id, last_login) 
                value ("'.$g_name.'","'.$g_email.'","'.$g_id.'","'.$currtime.'")';
                $run_query_insert = mysqli_query($conn, $query_insert);
            }

            $_SESSION['logged_in'] = true;
            $_SESSION['access_token'] = $token['access_token'];
            $_SESSION['uname'] = $g_name;
            $_SESSION['date'] = $currtime;

            header('location: welcome.php');


        }else{
            echo "Login again";
        }
        
    }
   
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>

<div class="pro-data hidden"></div>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="login_process.php" method="post">
        <section class="container forms">
            <div class="form login">
                <div class="form-content">
                    <header>Login</header>
                    <form action="#">
                        <div class="field input-field">
                            <input type="email" name="email" placeholder="Email" class="input">
                        </div>

                        <div class="field input-field">
                            <input type="password" name="password" placeholder="Password" class="password">
                            <i class='bx bx-hide eye-icon'></i>
                        </div>

                        <div class="form-link">
                            <a href="#" class="forgot-pass">Forgot password?</a>
                        </div>

                        <div class="field button-field">
                            <button type="submit" name = "login_user" class="btn">Login</button>
                        </div>
                    </form>

                    <div class="form-link">
                        <span>Don't have an account? <a href="#" class="link signup-link">Signup</a></span>
                    </div>
                </div>

                <div class="line"></div>

                <div class="media-options">
                    <a href="<?= $client->createAuthUrl(); ?>" class="field google">
                        <img src="assets/img/google.png" alt="" class="google-img">
                        <span>Login with Google</span>
                    </a>
                </div>

            </div>
            <!-- Signup Form -->
             <form action="singup_process.php" method="post">
            <div class="form signup">
                <div class="form-content">
                    <header>Signup</header>
                    <form action="#">
                        <div class="field input-field">
                            <input type="name" name="name" placeholder="Name" class="input">
                        </div>
                        <div class="field input-field">
                            <input type="email" name= "email" placeholder="Email" class="email">
                        </div>
                        <div class="field input-field">
                            <input type="password" name ="password" placeholder="password" class="password">
                            <i class='bx bx-hide eye-icon'></i>
                        </div>
                        <div class="field button-field">
                            <button type="submit" name= "singup_user" class="btn">Signup</button>
                        </div>
                    </form>
                    <div class="form-link">
                        <span>Already have an account? <a href="#" class="link login-link">Login</a></span>
                    </div>
                </div>
                <div class="media-options">
                <a href="<?= $client->createAuthUrl(); ?>" class="field google">
                        <img src="assets/img/google.png" alt="" class="google-img">
                        <span>Login with Google</span>
                    </a>
                </div>
            </div>
        </section>
       

            

            <!-- JavaScript -->
        <script src="js/script.js"></script>
        <script>
          const forms = document.querySelector(".forms"),
                pwShowHide = document.querySelectorAll(".eye-icon"),
                links = document.querySelectorAll(".link");

          pwShowHide.forEach(eyeIcon => {
              eyeIcon.addEventListener("click", () => {
                  let pwFields = eyeIcon.parentElement.parentElement.querySelectorAll(".password");

                  pwFields.forEach(password => {
                      if (password.type === "password") {
                          password.type = "text";
                          eyeIcon.classList.replace("bx-hide", "bx-show");
                          return;
                      }
                      password.type = "password";
                      eyeIcon.classList.replace("bx-show", "bx-hide");
                  });
              });
          });

          links.forEach(link => {
              link.addEventListener("click", e => {
                  e.preventDefault(); // Preventing form submit
                  forms.classList.toggle("show-signup");
              });
          });
        </script>
   
</body>
</html