<?php
session_start();

// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: Home.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/utils.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
</head>
<body>
<?php
    require_once "includes/navbar.php";
    ?>
    <div class="backgrnd">
        <img alt="" src="assets/images/fond_coeurs.jpg" />
        <div class="text2">

                    <div class="bouton1">
                        <form>
                            <a class="btn btn-secondary btn-outline-secondary btn-lg" style="background-color: #6D071A;" href="signup.php" role="button">
                                Sign Up
                            </a>
                          </form>
                    </div>

                <div class="bouton2">
                    <form>
                      <a class="btn btn-secondary btn-outline-secondary btn-lg" style="background-color: #6D071A;" href="login.php" role="button">
                                Log In
                            </a>
                    </form>
                    </div>
        
                <div class="bouton3">
                    <form>
                        <a class="btn btn-primary btn-outline-dark btn-lg" style="background-color: #8B4513;" href="help.php" role="button">
                                Help
                            </a>
                      </form>
                </div>
        </div>
      </div>
<?php
    require_once "includes/footer.php"
    ?>
</body>
</html>