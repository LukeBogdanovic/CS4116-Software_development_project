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
        <img alt="" src="images/fond_coeurs.jpg" />
        <div class="text2">
                <div class="container">

                    <div class="bouton1">
                        <form>
                            <input type="button" onclick="window.location.href = '';" value="Sign up" />
                          </form>
                    </div>

                </div>

                <div class="bouton2">
                    <form>
                      <input type="button" onclick="window.location.href = '';" value="Log in" />
                    </form>
                    </div>
        
                <div class="bouton3">
                    <form>
                        <input type="button" onclick="window.location.href = '';" value="Help" />
                      </form>
                </div>

        </div>

       

      </div>
    
        
            
            
    </main>

    
   



   

   
   
</body>
</html>