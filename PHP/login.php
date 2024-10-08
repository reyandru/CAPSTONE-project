<?php
session_start();
include 'database.php'; 
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($email) || empty($password)) {
        $message = "Please enter email and password.";
    } else {
        // First, check if the email is for the admin
        if ($email == "admin@admin.com" && $password == "admin@123") { 
            $_SESSION['login_email'] = $email;
            header("Location: ../adminPHP/adminAttendance.php");
            exit();
        }

        // If not admin, proceed with regular user login
        $sql = "SELECT * FROM registerdb WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['login_email'] = $email;
                header("Location: Home.php");
                exit();
            } else {
                $message = "Incorrect email or password";
            }
        } else {
            $message = "This email is not signed up yet.";
        }

        $stmt->close();
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Acme&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <title>Dr. ACE Fitness Gym</title>
  <link rel="icon" href="assets/logs.png">
  <link rel="stylesheet" href="../CSS/login.css" />
</head>
<body>

<div class="container">

  <div class="conts1">
    <div class="form-wrap">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="ttl-wrap">
      <p id="login-ttl">LOG IN</p>
      </div>

      <div class="inputs-wrap">
      <label for="email"> <ion-icon name="mail-outline"></ion-icon>Email</label>
      <input type="email" name="email" id="email" class="in" >

      <label for="passw"><ion-icon name="lock-closed"></ion-icon> Password</label>
      <input type="password" name="password" id="passw" class="in" >
      <a href="#">Forgot your password?</a>
      <div> <input type="submit" name="submitBtn" value="Log in" class="SU"></div>
      <p>Don’t have an account? <a href="register.php"> Register</a></p>
        <div id="messageAlert">
          <p style="color:black;" class="errorMsg"><?php echo $message; ?></p>
        </div>
      </div>
      </form>
    </div>
  </div>

  <div class="conts2">
    <img src="../assets/logs.png" alt="" height="150">
  </div>

</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="../Javascript/message.js"></script>
</body>
</html>
