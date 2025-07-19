<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css.css" />
  <title>Change Password</title>
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
  </style>
</head>

<body>
  <div class="container sign-up-mode">
    <div class="forms-container">
      <div class="signin-signup" style="left: 50%;z-index:99;">
        <form method="POST" class="sign-up-form" action="process_changepass.php">
          <h2 class="title">Change Password</h2>

          <?php
if (isset($_POST['account_email'])) {
    $email_forget = $_POST['account_email'];
    echo $email_forget;
} else {
    // Handle the case where email is not sent
    echo "Error: Email address not found";
    exit;
}?>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="old_password" placeholder="Current Password" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="new_password" placeholder="New Password" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required />
          </div>
          
          <!-- ส่งค่าแบบซ่อน-->
          <input type="hidden" name="account_email" value="<?=$email_forget?>">
          <input type="submit" name="submit" class="btn" value="Change Password" />
        </form>
      </div>
    </div>
  </div>
</body>

</html>