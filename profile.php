<?php
session_start();
// Checking if the user is already logged in to the website and redirecting to Home if they are
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

require "includes/database.php";
$id=$_GET['profile'];
$stmt = "SELECT photos.PhotoID FROM photos WHERE photos.UserID = ?";
if ($stmt = mysqli_prepare($con, $stmt)) {
    if (mysqli_stmt_bind_param($stmt, "i", $id)) {
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $PhotoID);
            if(mysqli_stmt_fetch($stmt)){
                $photoLocation = $PhotoID;
                $result = array('status' => 200, 'message' => "User's Photo retrieved");
            }
        } 
    }
}else {
    $result = array('status' => 403, 'message' => "User's Photo unable to be retrieved");
}
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        let sessionID = "<?php echo $_SESSION['id']; ?>";
        let admin = "<?php echo $_SESSION['admin']; ?>";
    </script>
    <script src="js/profile.js" defer></script>
    <script src="js/utils.js" defer></script>
</head>

<body class="text-center">
    <?php
    require_once "includes/navbar.php";
    ?>
    <div class="min-vh-100" id="body">
        <div class="d-flex vh-100 justify-content-center" id="spinner">
            <div class="spinner-border" role="status"></div>
        </div>
        <div class="container mt-5" id="cont" hidden>
            <div>
                <img src=<?php echo(!empty($photoLocation)) ? ("assets/images/{$photoLocation}") : ("assets/images/profile_pic.png");  ?> class="img-fluid rounded" alt="No Image">
            </div>
            <div class="card mb-4 mt-2">
                <div class="card-body" id="card">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Full Name</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="fullname"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Age</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="age"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Description</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="description"></p>
                        </div>
                    </div>
                    <hr id="hzInterests">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Interests</p>
                        </div>
                        <div class="col-sm-9" id="interests">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Smoker</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="smoker"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Drinker</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="drinker"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Gender</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="gender"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Seeking</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="seeking"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">County</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="county"></p>
                        </div>
                    </div>
                    <hr id="hzTown">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Town</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="town"></p>
                        </div>
                    </div>
                    <hr id="hzEmployment">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Employment</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="employment"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Student</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0" id="student"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="userLike" hidden>
            <a class="btn btn-lg submit mb-3" id="likeButton" role="button" onclick="likeUser(event);">Like User</a>
        </div>
        <div class="mb-3" id="edit"></div>
    </div>
    <?php
    require_once "includes/footer.php"
    ?>
    <template data-template>
        <div class="row">
            <div class="col-sm-3">
                <p class="mb-0" data-name></p>
            </div>
            <div class="col-sm-9">
                <p class="text-muted mb-0" data-value></p>
            </div>
        </div>
    </template>
</body>

</html>