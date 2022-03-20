<?php
session_start();

// Checking if the user is already logged in to the website and redirecting to Home if they are
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: Home.php");
    exit;
}
?>
<!DOCTYPE html>
<head>
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

    <div class="image2">
        <img alt="" src="images/fond_coeurs.PNG" />
        <div class="text2">
        
            <div class="">
                <div class="container">

                    <div class="bouton">
                        <form>
                            <input type="button" onclick="window.location.href = '';" value="Sign up" />
                          </form>

                          <form>
                            <input type="button" onclick="window.location.href = '';" value="Log in" />
                          </form>
        </div>

        <div class="text3">
            <form>
                <input type="button" onclick="window.location.href = '';" value="Help" />
              </form>
        </div>

      </div>
    
       
            
            
    </main> 
   
</body>
</html>