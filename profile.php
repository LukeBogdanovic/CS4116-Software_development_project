<?php
require_once "includes/database.php";
session_start();

if (empty($_GET["profile"])) {
    $id = $_SESSION["id"];
} else {
    $id = $_GET["profile"];
}
// Checking if the user is already logged in to the website and redirecting to Home if they are
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
//must check if the dit button will be displayed
$editable = false;
//if GET profile is set, check if its = to current users ID. if its not set then its the users profile and they can edit it.
//also show edit if current logged in user is admin 
if (!empty($_GET["profile"])) {
    if ($_GET["profile"] == $_SESSION["id"] || $_SESSION['admin'] == true) {
        $editable = true;
    }
} else {
    $editable = true;
}

//fetch users profile info, in next satement fetch their interests
$fetchProfile = "SELECT user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Smoker, profile.Drinker, profile.Gender, profile.Seeking, profile.Description, profile.County, profile.Town, profile.Employment, profile.Student, profile.College, profile.Degree FROM profile JOIN user ON user.UserID = profile.UserID WHERE user.userID = ?";
if ($stmt = mysqli_prepare($con, $fetchProfile)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        //bind results of search to user variable 
        mysqli_stmt_bind_result($stmt, $username, $firstnameStored, $surnameStored, $dobStored, $smokerStored, $drinkerStored, $genderStored, $seekingStored, $descriptionStored, $countyStored, $townStored, $employmentStored, $studentStored, $collegeStored, $degreeStored);
        mysqli_stmt_fetch($stmt);
        ($studentStored == 0) ? $studentStored = 'No' : $studentStored = 'Yes';
    }
}

//fetch interests, has issues
$fetchInterests = "SELECT availableinterests.interestName FROM interests JOIN availableinterests ON interests.InterestID = availableinterests.InterestID WHERE interests.UserID = ? ORDER BY availableinterests.InterestID ASC;";
if ($stmt = mysqli_prepare($con, $fetchInterests)) {
    $interestStored = [];
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $interestname);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            //put retrieved Interests in the interestStored array
            while (mysqli_stmt_fetch($stmt)) {
                array_push($interestStored, $interestname);
            }
        }
    }
}
while (count($interestStored) < 4) {
    array_push($interestStored, $interestname);
}
?>

<!DOCTYPE html>

<head>
    <title><?php echo "{$firstnameStored} {$surnameStored}" ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body class="text-center">
    <?php
    require_once "includes/navbar.php";
    ?>

    <div class="container py-5">

        <div id="profileCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/images/profile_pic.png" class="img-fluid rounded mx-auto d-block w-75" alt="No Image">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/1619563.png" class="img-fluid rounded mx-auto d-block w-75" alt="No Image">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/logo.png" class="img-fluid rounded mx-auto d-block w-75" alt="No Image">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#profileCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#profileCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="card mb-4 mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Full Name</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $firstnameStored . " " . $surnameStored ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Description</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $descriptionStored ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Interests</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $interestStored[0] . " " . $interestStored[1] . " " . $interestStored[2] . " " . $interestStored[3] ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Smoker</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $smokerStored ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Drinker</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $drinkerStored ?> </p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Gender</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $genderStored ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Seeking</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $seekingStored ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">County</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $countyStored ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Town</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $townStored ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Employment</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $employmentStored ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Student</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $studentStored ?></p>
                    </div>
                </div>
                <?php if (!empty($collegeStored)) {
                    echo
                    '<hr>
              <div class="row">
                <div class="col-sm-3">
                  <p class="mb-0">College</p>
                </div>
                <div class="col-sm-9">
                  <p class="text-muted mb-0">' . $collegeStored . '</p>
                </div>
              </div>';
                }
                if (!empty($degreeStored)) {
                    echo
                    '<hr>
              <div class="row">
                <div class="col-sm-3">
                  <p class="mb-0">Degree</p>
                </div>
                <div class="col-sm-9">
                  <p class="text-muted mb-0">' . $degreeStored . '</p>
                </div>
              </div>';
                } ?>
            </div>
        </div>
    </div>
    <?php
    if ($editable == true) {
        echo
        '<div class="align-items-center justify-content-center h-25">
          <a class="btn btn-lg submit" style="background-color: #6D071A;" href="profileSetup.php" role="button">
              Edit
          </a>
        </div>';
    }
    ?>
    <?php
    require_once "includes/footer.php"
    ?>
</body>

</html>