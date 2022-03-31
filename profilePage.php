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
    <link rel="stylesheet" href="css/utils.css">
    <link rel="stylesheet" href="css/profile.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>
<body>
<?php
    require_once "includes/navbar.php";
    ?>
    <div class="profile">
	<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="assets/images/profile_pic.png" class="rounded mx-auto d-block" class="d-block w-20" alt="Not Found">
    </div>
    <div class="carousel-item">
      <img src="assets/profile_pic.png" class="rounded mx-auto d-block" class="d-block w-20" alt="Not Found">
    </div>
    <div class="carousel-item">
      <img src="assets/profile_pic.png" class="rounded mx-auto d-block" class="d-block w-20" alt="Not Found">
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

    <div class="container">
        <div class="text">
            <p>Name</p>
        <p>Age</p>
        <p>Location</p>
        <p>Height</p>
        <p>Interests</p>
        <p>Bio</p>
        </div>
    </div>

    <div class="bouton">
        <form>
            <a class="btn btn-secondary btn-outline-secondary btn-lg" style="background-color: #6D071A;" href="" role="button">
                                Edit
                            </a>
          </form>
    </div>      
<?php
    require_once "includes/footer.php"
    ?>
</body>
</html>