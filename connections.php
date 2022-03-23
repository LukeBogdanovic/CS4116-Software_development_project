<!DOCTYPE html>
<html>

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
  
    <head>
        <title>Connections</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/utils.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>

    <body>

        <?php
            require_once "includes/navbar.php";
        ?>

        <main>
            <div class="container-fluid bg-trasparent my-4 p-3" style="position: relative;">
                
                <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3">
                    
                    <div class="col">
                        <div class="card h-100 shadow-sm"> <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                            <div class="card-body">
                                <h5 class="card-title">Name, Age</h5>
                                <p class="card-content">Bio</p>
                                <div class="text-center my-4"> <a class="btn btn-dark">View Profile</a> </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="card h-100 shadow-sm"> <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                            <div class="card-body">
                                <h5 class="card-title">Name, Age</h5>
                                <p class="card-content">Bio</p>
                                <div class="text-center my-4"> <a class="btn btn-dark">View Profile</a> </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col">
                        <div class="card h-100 shadow-sm"> <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                            <div class="card-body">
                                <h5 class="card-title">Name, Age</h5>
                                <p class="card-content">Bio</p>
                                <div class="text-center my-4"> <a class="btn btn-dark">View Profile</a> </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="col">
                        <div class="card h-100 shadow-sm"> <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                            <div class="card-body">
                                <h5 class="card-title">Name, Age</h5>
                                <p class="card-content">Bio</p>
                                <div class="text-center my-4"> <a class="btn btn-dark">View Profile</a> </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </main>

        <?php
            require_once "includes/footer.php";
        ?>

    </body>

</html>