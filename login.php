<?php
session_start();
require "database.php";
require_once "utils.php";

// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: Home.php");
    exit;
}
// Intializing username and password variables
$username = $password = "";
$username_err = $password_err = $login_err = "";
// Check for if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Check for empty Username field in the form submission
    if (empty(trim($_POST['username']))) {
        $username_err = "Please enter your Username.";
    } else {
        $username = trim($_POST['username']);
    }
    // Check for empty Password field in the form submission
    if (empty(trim($_POST['password']))) {
        $password_err = "Please enter your Password.";
    } else {
        $password = trim($_POST['password']);
    }
    // Validating username and password were filled in at form submission
    if (empty($username_err) && empty($password_err)) {
        // Preparation of SQL Select statement
        $username_query = "SELECT UserID, Username, Password FROM user WHERE Username = ?";
        if ($stmt = mysqli_prepare($con, $username_query)) {
            // Binding variable db_username to the sql statement at the ?
            mysqli_stmt_bind_param($stmt, "s", $db_username);
            $db_username = $username;
            // Attempting to execute the sql statement
            if (mysqli_stmt_execute($stmt)) {
                // Storing the result of the sql statement
                mysqli_stmt_store_result($stmt);
                /** 
                 *Checking that the username has been returned
                 * There should only be one username that matches due to usernames
                 * having to be unique
                 */
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Binding the return values to php variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Starting a new session for the user due to password being correct
                            session_start();
                            // Storing user data in the session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $username;
                            // Redirect to the Home page of Account
                            header("location: Home.php");
                        } else {
                            /**
                             * Setting login_err to provide feedback to the user
                             * In this case password is not valid
                             * Display a generic error message as such 
                             */
                            $login_err = "Invalid Username or Password";
                        }
                    }
                } else {
                    /**
                     * 
                     */
                    $login_err = "Invalid Username or Password";
                }
            } else {
                // 
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close the SQL statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close the connection to the database
    mysqli_close($con);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="http://group13.epizy.com/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="http://group13.epizy.com/js/utils.js"></script>
</head>

<body>

    <div>
        <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex align-items-center justify-content-center h-100">
                    <div class="col-md-8 col-lg-7 col-xl-6">
                        <img src="" class="img-fluid" alt="image to be found">
                    </div>
                    <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1" id="form">
                        <?php
                        if (!empty($login_err)) {
                            echo '<div class="alert alert-danger">' . $login_err . '</div>';
                        }
                        ?>
                        <form action="login.php" method="POST">
                            <div class="form-outline mb-4">
                                <input name="username" type="text" id="username" data-toggle="tooltip" class="form-control form-control-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" placeholder="Username" pattern="[A-Za-z0-9_]{0,16}" title="Must be less than 16 characters. Can contain alphanumeric characters and underscores." value="<?php echo $username; ?>" />
                                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            </div>
                            <div class="form-outline mb-4">
                                <input name="password" type="password" id="pwd" data-toggle="tooltip" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}" title="Must contain at least one number and one uppercase and lowercase character, and between 8 and 16 characters long." />
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="d-flex justify-content-around align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="form1checkbox" />
                                    <label class="form-check-label" for="form1checkbox"> Remember Me </label>
                                </div>
                                <a onclick="forgotPassword();" href="#!">Forgot Password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
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
    <script>
        $(document).ready(() => {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
</body>

</html>