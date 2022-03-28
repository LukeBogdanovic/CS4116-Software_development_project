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
    <title>Sign Up</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/login.css" rel="stylesheet" type="text/css">
    <link href="css/utils.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="js/registration.js"></script>
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
                    <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                        <form onsubmit="registerNewUser(event);" method="POST" id="signupform">
                            <div class="form-floating mb-4">
                                <input name="username" type="text" data-toggle="tooltip" class="form-control form-control-lg" id="username" placeholder="Username" title="Must be less than 16 characters. Can contain alphanumeric characters and underscores.">
                                <label for="username">Username</label>
                                <span id="usermsg"></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="firstname" type="text" class="form-control form-control-lg" id="firstname" placeholder="First Name">
                                <label for="firstname">First Name</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="surname" type="text" class="form-control form-control-lg" id="surname" placeholder="Surname">
                                <label for="surname">Surname</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="dob" type="date" class="form-control form-control-lg" id="dob" placeholder="Date Of Birth">
                                <label for="dob">Date Of Birth</label>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="email" type="email" class="form-control form-control-lg" id="email" placeholder="Email Address">
                                <label for="email">Email Address</label>
                                <span id="emailmsg"></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="password" type="password" data-toggle="tooltip" class="form-control form-control-lg" id="pwd" placeholder="Password" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long.">
                                <label for="password">Password</label>
                                <span id="pwdmsg"></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="confirmpassword" type="password" class="form-control form-control-lg" id="confirmpwd" placeholder="Confirm Password">
                                <label for="confirmpassword">Confirm password</label>
                                <span id="confirmpwdmsg"></span>
                            </div>
                            <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block" disabled="disabled">Submit</button>
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
    <?php
    require_once "includes/footer.php";
    ?>
    <script>
        $("#username").on("keyup", () => {
            if ($("#username").val().length > 16) {
                $("#usermsg")
                    .html("Username must not exceed 16 characters")
                    .css("color", "red");
            } else {
                $("#usermsg").html("");
            }
        });
        $("#pwd").on("keyup", () => {
            if ($("#pwd").val().length < 8) {
                $("#pwdmsg")
                    .html("Password must be at least 8 characters")
                    .css("color", "red");
            } else if ($("#pwd").val().length > 16) {
                $("#pwdmsg")
                    .html("Password must not exceed 16 characters")
                    .css("color", "red");
            } else {
                $("#pwdmsg").html("");
            }
        });
        $("#confirmpwd").on("keyup", () => {
            if ($("#pwd").val() == $("#confirmpwd").val()) {
                $("#confirmpwdmsg").html("Matching").css("color", "green");
            } else {
                $("#confirmpwdmsg").html("Not Matching").css("color", "red");
            }
        });
        $(document).on("ready", () => {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>

</html>