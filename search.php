<?php
session_start();
/* Checking if the user is not logged in
 * If user is not logged in: the user is redirected to the login page and the script exits
 */
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="js\search.js" defer></script>
    <title>Search</title>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php
    require_once "includes/navbar.php";
    ?>
    <div>
        <div class="container py-5 h-5">
            <input name="search" type="text" id="search" class="form-control" placeholder="Search">
        </div>
        <div class="col">
            <label for="ageRange" class="form-label"></label>
            <input type="range" class="form-range" id="ageRange" min="18" max="130">
        </div>
        <div class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
            <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3" id="user-cards" data-user-cards-container></div>
        </div>

        <template data-user-template>
            <div class="col">
                <div class="usercard card h-100 shadow-sm"> <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                    <div class="card-body">
                        <h5 class="card-title" data-header>Name</h5>
                        <h5 class="card-subtitle mb-2 text-muted" data-username>Username</h5>
                        <h6 class="card-subtitle mb-2 text-muted" data-age>Age</h5>
                            <p class="card-content" data-body>Bio</p>
                            <div class="text-center my-4">
                                <a class="btn btn-dark">View Profile</a>
                            </div>
                    </div>
                </div>
            </div>
        </template>

    </div>
    <?php
    require_once "includes/footer.php"
    ?>

</body>

</html>