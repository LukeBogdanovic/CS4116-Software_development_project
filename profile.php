<?php
session_start();

// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: home.php");
    exit;
}
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
</head>
<body class="text-center">
<?php
    require_once "includes/navbar.php";
    ?>

<div class="container py-5">

  <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assets/images/profile_pic.png" class="img-fluid rounded mx-auto d-block w-50" alt="No Image">
      </div>
      <div class="carousel-item">
        <img src="assets/images/profile_pic.png" class="img-fluid rounded mx-auto d-block w-50" alt="No Image">
      </div>
      <div class="carousel-item">
        <img src="assets/images/profile_pic.png" class="img-fluid rounded mx-auto d-block w-50" alt="No Image">
      </div>
    </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
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
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Smoker</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Drinker</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Gender</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Seeking</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">County</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Town</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Employment</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Student</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">College</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Degree</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Description</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">Input</p>
              </div>
            </div>
          </div>
        </div>
</div>

    <div class="align-items-center justify-content-center h-25">
        <a class="btn btn-secondary btn-outline-light btn-lg" style="background-color: #6D071A;" href="profileSetup.php" role="button">
                                Edit
            </a>
    </div>
    
    <?php
    require_once "includes/footer.php"
    ?>
</body>
</html>