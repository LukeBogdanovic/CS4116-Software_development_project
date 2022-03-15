<?php
session_start();
// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: Home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign Up</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="http://group13.epizy.com/css/login.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <div>
        <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex align-items-center justify-content-center h-100">
                    <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                        <form action="signup.php" method="POST">
                            <div class="form-outline mb-4">
                                <input type="text" class="form-control form-control-lg" id="username" placeholder="Username">
                                <span id="usermsg"></span>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" class="form-control form-control-lg" id="firstname" placeholder="First Name">
                            </div>
                            <div class="form-outline mb-4">
                                <input type="text" class="form-control form-control-lg" id="surname" placeholder="Surname">
                            </div>
                            <div class="form-outline mb-4">
                                <input type="email" class="form-control form-control-lg" id="email" placeholder="Email Address">
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" class="form-control form-control-lg" id="pwd" placeholder="Password">
                                <span id="pwdmsg"></span>
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" class="form-control form-control-lg" id="confirmpwd" placeholder="Confirm Password">
                                <span id="confirmpwdmsg"></span>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
                            <div class="divider d-flex align-items-center my-4">
                                <p class="text-center fw-bold mx-3 mb-0 text-muted">OR</p>
                            </div>
                            <a class="btn btn-primary btn-lg btn-block" style="background-color: #3b5998;" href="login.php" role="button">
                                Sign In
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $("#username").keyup(() => {
            if ($("#username").val().length > 16) {
                $("#usermsg").html("Username must not exceed 16 characters").css("color", "red");
            } else {
                $("#usermsg").html("");
            }
        });
        $("#pwd").keyup(() => {
            if ($("#pwd").val().length < 8) {
                $("#pwdmsg").html("Password must be at least 8 characters").css("color", "red");
            } else if ($("#pwd").val().length > 16) {
                $("#pwdmsg").html("Password must not exceed 16 characters").css("color", "red");
            } else {
                $("#pwdmsg").html("");
            }
        });
        $("#confirmpwd").keyup(() => {
            if ($("#pwd").val() == $("#confirmpwd").val()) {
                $("#confirmpwdmsg").html("Matching").css("color", "green");
            } else {
                $("#confirmpwdmsg").html("Not Matching").css("color", "red");
            }
        });
    </script>
</body>

</html>