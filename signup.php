<?php
session_start();
// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: Home.php");
    exit;
}
// Intializing all form variables & err
$username = $password = $confirmpassword = $firstname = $surname = $email = "";
$username_err = $password_err = $confirmpassword_err = $firstname_err = $surname_err = $email_err = "";
// Check for if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Must check all fields to see if they are empty
    // Check for empty Username field in the form submission
    if (empty(trim($_POST['username']))) {
        $username_err = "Please enter your Username.";
    } else {
        $username = trim($_POST['username']);
    }
    //Check for empty firstname field in the form submission
    if (empty(trim($_POST['firstname']))) {
        $firstname_err = "Please enter your first name.";
    } else {
        $firstname = trim($_POST['firstname']);
    }
    //Check for empty surname field in the form submission
    if (empty(trim($_POST['surname']))) {
        $surname_err = "Please enter your surname.";
    } else {
        $surname = trim($_POST['surname']);
    }
    //Check for empty email field in the form submission
    if (empty(trim($_POST['email']))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST['email']);
    }
    //Check for empty password field in the form submission
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST['password']);
    }
    //Check for empty confirmpassword field in the form submission
    if (empty(trim($_POST['confirmpassword']))) {
        $confirmpassword_err = "Please confirm your password";
    } else {
        $confirmpassword = trim($_POST['confirmpassword']);
    }
    //Make sure all 6 variables are not null. i=6 if all are not empty
    $i=0;
    foreach($_POST as $name => $value) {
        if($value != "") {
          $i++;
        }
    }
    //
    if($i==6){

        //must run checks on email and username to see if theyre already taken
        //Username check
        $username_test = "SELECT Username, Email FROM user WHERE Username = '$username' OR Email = '$email'";
        //prepare sql statement for username check
        if($stmt = mysqli_prepare($con, $username_test)){
            // Attempt to execute the sql statement
            if (mysqli_stmt_execute($stmt)) {
                // Store the result of the sql statement
                mysqli_stmt_store_result($stmt);
                //bind results from stmt to variables to be used
                mysqli_stmt_bind_result($stmt, $username_stored, $email_stored);
                //fetch the results of the stmt
                if (mysqli_stmt_fetch($stmt)) {
                    //if the result returns with a username matching the one entered by the user, the username is already in use
                    if ($username == $username_stored) {
                        $username_err = "Username is already in use";
                    }
                    //if the result returns with an email matching the one entered by the user, the email is already in use
                    if($email == $email_stored) {
                        $email_err = "Email is already in use";
                    }
                }
            }else{echo "Oops! Something went wrong. Please try again later.";}
            //Close statement
            mysqli_stmt_close($stmt);
        }
    }

    if($username_err == '' && $email_err == ''){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = "INSERT INTO user (UserID, Username, Firstname, Surname, Email, Password) VALUES (DEFAULT,'$username', '$firstname', '$surname', '$email','$hashed_password')";
        if($stmt = mysqli_prepare($con, $insert)){
            // Attempt to execute the sql statement
            if (mysqli_stmt_execute($stmt)) {
                // Starting a new session for the user due to password being correct
                session_start();
                // Storing user data in the session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id; //trying to figure out a way to retrieve the id created for the user without a whole ass sql statement. Bear with me, hit me with suggestioons if you have
                $_SESSION['username'] = $username;
                // Redirect to the Home page of Account
                header("location: Home.php");
            } else{echo "it failed";}
        }
    }
    mysqli_close($con);
}
?>
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign Up</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="css/login.css" rel="stylesheet" type="text/css">
    <link href="css/utils.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    require "navbar.php";
    ?>
    <div>
        <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                        <img src="" class="img-fluid" alt="image to be found">
                    </div>
                    <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                        <?php
                        // Error informing should go here above the input form
                        ?>
                        <form action="signup.php" method="POST">
                            <div class="form-floating mb-4">
                                <input name="username" type="text" data-toggle="tooltip" class="form-control form-control-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" id="username" placeholder="Username" title="Must be less than 16 characters. Can contain alphanumeric characters and underscores.">
                                <label for="username">Username</label>
                                <span id="usermsg"></span>
                                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="firstname" type="text" class="form-control form-control-lg <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" id="firstname" placeholder="First Name">
                                <label for="firstname">First Name</label>
                                <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="surname" type="text" class="form-control form-control-lg <?php echo (!empty($surname_err)) ? 'is-invalid' : ''; ?>" id="surname" placeholder="Surname">
                                <label for="surname">Surname</label>
                                <span class="invalid-feedback"><?php echo $surname_err; ?></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="email" type="email" class="form-control form-control-lg <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email" placeholder="Email Address">
                                <label for="email">Email Address</label>
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="password" type="password" data-toggle="tooltip" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="pwd" placeholder="Password" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long.">
                                <label for="password">Password</label>
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                                <span id="pwdmsg"></span>
                            </div>
                            <div class="form-floating mb-4">
                                <input name="confirmpassword" type="password" class="form-control form-control-lg <?php echo (!empty($confirmpassword_err)) ? 'is-invalid' : ''; ?>" id="confirmpwd" placeholder="Confirm Password">
                                <label for="confirmpassword">Confirm password</label>
                                <span id="confirmpwdmsg"></span>
                                <span class="invalid-feedback"><?php echo $confirmpassword_err; ?></span>
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
    <?php
    require "footer.php";
    ?>
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
        $(document).keyup(() => {
            if ($("#username").val() === "" || $("#pwd").val() === "" || $("#confirmpwd").val() === "" || $("#email").val() === "" || $("#surname").val() === "" || $("#firstname").val() === "") {
                $("#submit").prop('disabled', true);
            } else {
                $("#submit").prop('disabled', false);
            }
        });
        $(document).ready(() => {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>

</html>