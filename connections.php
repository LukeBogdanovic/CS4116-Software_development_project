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
<html>

<head>
    <title>Connections</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="js/connections.js" defer></script>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php
    require_once "includes/navbar.php";
    ?>
    <input type="hidden" id="userID" name="userID" value="<?php echo $_SESSION['id'] ?>">
    <main>
        <div id="container" class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
            <h3>Connected Users</h3>
            <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3" data-user-cards-container></div>
        </div>
    </main>

    <div id="container" class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
        <h3>Liked Users</h3>
        <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3" data-user-cards-liked-container></div>
    </div>

    <template data-user-template>
        <div class="col">
            <div class="usercard card h-100 shadow-sm"> <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                <div class="card-body">
                    <input value="" hidden data-userid>
                    <h5 class="card-title" data-header>Name</h5>
                    <h5 class="card-subtitle mb-2 text-muted" data-username>Username</h5>
                    <h6 class="card-subtitle mb-2 text-muted" data-age>Age</h5>
                        <p class="card-content" data-body>Bio</p>
                        <p class="card-content text-muted" data-connection>Connected date</p>
                        <div class="text-center my-4">
                            <a class="btn btn-dark">View Profile</a>
                        </div>
                </div>
            </div>
        </div>
    </template>

    <?php
    require_once "includes/footer.php";
    ?>
</body>

</html>