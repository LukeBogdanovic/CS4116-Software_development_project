<?php
session_start();

// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    require_once "includes/navbar.php";
    ?>
    <div class="bg-image d-flex align-items-center justify-content-center text-center vh-100" style="background-image: url('assets/images/bg_og.jpg'); height: 100vh; background-size: cover;">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="container">
                <img src="assets/images/logo_transparentbg.png" alt="no image">
            </div>
            <div class="container">
                <a class="btn submit btn-outline-light btn-lg" href="signup.php" role="button">Sign Up</a>
                <a class="btn submit btn-outline-light btn-lg" href="login.php" role="button">Log In</a>
            </div>
        </div>
    </div>
    </div>
    <?php
    require_once "includes/footer.php";
    ?>
</body>

</html>