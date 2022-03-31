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
 <?php
    require_once "includes/navbar.php";
    ?>
  <head>
    <title>Welcome Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        
    <!-- Custom styles for this template -->
    <link href="cover.css" rel="stylesheet">
  </head>
  <div class="has-bg-image">
    <body class="text-center vh-100">
     <div class="row d-flex align-items-center justify-content-center h-100">
        <main role="main" class="inner cover">
          <h1 class="cover-heading text-light">Welcome</h1>
          <p class="lead">
            <a href="#" class="btn btn-lg btn-secondary">Sign Up</a>
            <a href="#" class="btn btn-lg btn-secondary">Log In</a>
          </p>
        </main>
      </div>
    </body>
    <img class="bg-img" src="assets/images/fond_coeurs.jpg" alt="no image">
  </div>
  <?php
    require_once "includes/footer.php";
    ?>
</html>