<?php
session_start();

// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: Home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" sizes="16x16">
</head>
<body>
    <!-- Dans la balise body va se trouver tout le contenu visible de mon site -->
    <header>
      
          
           
        </body>
    </header>

    <div class="backgrnd">
        <img alt="" src="assets/images/fond_coeurs.jpg" />
        <div class="text2">
                <div class="container">

                    <div class="bouton1">
                        <form>
                            <a class="btn btn-secondary btn-outline-secondary btn-lg" style="background-color: #6D071A;" href="signup.php" role="button">
                                Sign Up
                            </a>
                          </form>
                    </div>

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
    
        
            
            
    </main>

    
   



   

   
   
</body>
</html>