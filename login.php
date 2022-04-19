<?php
session_start();
// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/login.js"></script>
</head>

<body>
    <?php
    require_once "includes/navbar.php";
    ?>
    <div>
        <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex align-items-center justify-content-center h-100">
                    <div class="col-md-8 col-lg-7 col-xl-6">
                        <img src="" class="img-fluid" alt="image to be found">
                    </div>
                    <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1" id="form">
                        <form onsubmit="loginUser(event);" method="POST" id="loginForm">
                            <div class="form-floating mb-4">
                                <input name="username" type="text" id="username" class="form-control form-control-lg" pattern="[A-Za-z0-9_]{0,16}" placeholder="Username" title="Must be less than 16 characters. Can contain alphanumeric characters and underscores." />
                                <label for="username">Username</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="password" type="password" id="pwd" class="form-control form-control-lg" pattern="[A-Za-z0-9_$%^&*()@?><-]{8,16}" placeholder="Password" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long." />
                                <label for="password">Password</label>
                            </div>
                            <div class="d-flex justify-content-around align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="form1checkbox" />
                                    <label class="form-check-label" for="form1checkbox"> Remember Me </label>
                                </div>
                                <a href="resetPassword.php">Forgot Password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block" id="submit" disabled="disabled">Sign In</button>
                            <div class="divider d-flex align-items-center my-4">
                                <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                            </div>
                            <a class="btn btn-primary btn-lg btn-block" style="background-color: #3b5998;" href="signup.php" role="button">
                                Register for an Account
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
    require_once "includes/footer.php";
    ?>
</body>

</html>