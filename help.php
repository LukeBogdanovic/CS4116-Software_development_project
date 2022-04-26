<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Help</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <link rel="stylesheet" type="text/css" href="css/help.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="js/utils.js" defer></script>
</head>

<body>
    <?php
    require_once "includes/navbar.php";
    ?>
    <section class="vh-100">
        <div class="row d-flex align-items-center justify-content-center h-100">
            <div class="col-lg-6 col-md-6">
                <div class="leftside d-flex align-items-center justify-content-center h-100">
                    <img src="assets/images/logo.PNG" class="img-fluid" alt="logo" width="700" height="700">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="row d-flex align-items-center justify-content-center h-100">
                    <button type="button" class="btn btn-dark btn-lg">Need Some Help? Get in touch!</button>
                </div>
                <div class="row d-flex align-items-center justify-content-center h-25">
                    <div class="col d-flex align-items-center justify-content-center h-100">
                        <img src="assets/images/1619563.png" class="img-fluid" alt="Text box" width="200" height="200">
                    </div>
                    <div class="col d-flex align-items-center justify-content-center h-100">
                        <button type="button" class="btn btn-outline-dark btn-lg">Please contact us via phone: 123456789</button>
                    </div>
                </div>
                <div class="row d-flex align-items-center justify-content-center h-25">
                    <div class="col d-flex align-items-center justify-content-center h-100">
                        <img src="assets/images/aticon.png" class="img-fluid" alt="at icon" width="200" height="200">
                    </div>
                    <div class="col d-flex align-items-center justify-content-center h-100">
                        <button type="button" class="btn btn-outline-dark btn-lg">Please contact us via email: gragodeo@gmail.com</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    require_once "includes/footer.php";
    ?>
</body>

</html>