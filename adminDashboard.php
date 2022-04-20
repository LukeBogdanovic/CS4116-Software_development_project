<?php 
session_start();
// Checking if the user is already logged in to the website and redirecting to Home if they are
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

    <head>
        <title>Admin Dashboard Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/utils.css">
        <link rel="stylesheet" type="text/css" href="css/admindashboard.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>

    <body>
        
        <?php
            require_once "includes/navbar.php";
        ?>

        <main>

            <div class="container mt-5 mb-5">

                <div class="d-flex justify-content-center">

                    <div class="col-md-6">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-right">Reports Table:</h5>
                        </div>

                        <?php
                            require_once "includes/database.php";

                            $query = "SELECT userID, ReportReason FROM reports";

                            $result = mysqli_query($con, $query);

                            if ($result->num_rows > 0) {
                                // Reported Users
                                while($row = $result->fetch_assoc()) {
                                    print_r("<b>ID: </b>" . $row["userID"]. "<b> - Reason: </b>" . $row["ReportReason"]);
                                    if($row["ReportReason"] == NULL){
                                        print_r("No Specified Reason");
                                    }
                                    print_r(" ");
                                    $b = $row["userID"];
                                    $query2 = "SELECT userID, Username FROM user WHERE userID ='$b'";
                                    $result2 = mysqli_query($con, $query2);
                                    $rowbis = mysqli_fetch_row($result2);
                                    print_r("<b>- Username: </b>" . $rowbis[1]. "<br>");
                                }
                              } else {
                                print_r("<b>0 results</b>");
                              }
                        ?>

                        <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                            <h5 class="text-right">List of Banned Users:</h5>
                        </div>

                        <?php
                            require_once "includes/database.php";

                            $query = "SELECT userID, Username, Banned FROM user WHERE Banned ='1'";

                            $result = mysqli_query($con, $query);

                            if ($result->num_rows > 0) {
                                // Banned Users
                                while($row = $result->fetch_assoc()) {
                                    print_r("<b>ID: </b>" . $row["userID"]. "<b> - Username: </b>" . $row["Username"]. " ");

                                    $b = $row["userID"];
                                    $query2 = "SELECT userID, `Date`, Reason, BannedByID FROM bannedusers WHERE userID ='$b'";
                                    $result2 = mysqli_query($con, $query2);
                                    $rowbis = mysqli_fetch_row($result2);

                                    if($rowbis[2]==NULL){
                                        $rowbis[2] = "No Specified Reason";
                                    }

                                    print_r("<b>- Date: </b>" . $rowbis[1]."<b> - Ban Reason: </b>" . $rowbis[2] . "<br>");

                                    $query3 = "SELECT userID, Username FROM user WHERE userID ='$rowbis[3]'";
                                    $result3 = mysqli_query($con, $query3);
                                    $rowtres = mysqli_fetch_row($result3);

                                    print_r("<b>Banned By : </b>" . $rowtres[1]. "<br>");
                                }
                              } else {
                                print_r("<b>0 results</b>");
                              }
                        ?>

                        <div class="row mt-5">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-right">Ban User:</h5>
                            </div>

                            <div class="input-group mb-3">

                                <form action="" method="POST">
                                    <div>
                                        <label class="labels">Username</label>
                                        <input type="text" id="username" name="username"> <br>   

                                        <fieldset> <br>
                                            <legend style="font-size:20px">Ban Reason : </legend>      
                                            <input type="checkbox" name="ban_reason" value="Harassment"> Harassment<br>      
                                            <input type="checkbox" name="ban_reason" value="Disrespectful behaviour"> Disrespectful behaviour<br>      
                                            <input type="checkbox" name="ban_reason" value="Hate Speech"> Hate Speech<br>
                                            <input type="checkbox" name="ban_reason" value="Catfish"> Catfish<br>
                                            <input type="checkbox" name="ban_reason" value="Bot account"> Bot account<br>
                                        </fieldset> <br>

                                        <input class="btn btn-danger" type="submit" value="Ban">

                                    </div>
                                </form>

                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-right">Unban User:</h5>
                            </div>

                            <div class="input-group mb-3">

                            <form action="" method="POST">
                                    <div>
                                        <label class="labels">Username</label>
                                        <input type="text" id="username2" name="username2">
                                        <input class="btn input-group-text btn-success" type="submit" value="Unban">
                                    </div>

                                </form>


                            </div>

                        </div>
                            <div class="justify-content-between align-items-center">
                                <?php 
                                    date_default_timezone_set('Europe/Dublin');
                                    //Ban A User

                                        if(!empty($_POST["username"])){

                                            require_once "includes/database.php";

                                            $username = $_POST["username"];
                                            $banbyid = $_SESSION['id'];

                                            // Check For Ban Reason
                                            if(!empty($_POST["ban_reason"])){
                                                $banreason = $_POST["ban_reason"];
                                                print_r("Selected Ban Reason : ". $banreason.'</p>');
                                            }
                                            else {
                                                $banreason = NULL;
                                            }
                                    
                                            // Check connection
                                            if($con === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            // Fetch Data
                                            $query = "SELECT userID, Username, Banned FROM user WHERE Username ='$username'";

                                            $result = mysqli_query($con, $query);

                                            if (!$result) {
                                                print_r("Error : No user found" . $con -> error);
                                            }

                                            else {
                                                $row = mysqli_fetch_row($result);

                                                if (empty($row)){
                                                    print_r("Error : No user found" . $con -> error);
                                                }
                                                
                                                else {
                                                    print_r( "User ID Value :");
                                                    print_r( '<p>'.$row[0].'</p>'); // id
                                                    print_r( "Username Value :");
                                                    print_r( '<p>'.$row[1].'</p>'); // username
                                                    print_r( "Banned Value :");
                                                    print_r( '<p>'.$row[2].'</p>'); // Bool Banned

                                                    if ($row[2] == 1) {
                                                        print_r( "User Already Banned.");
                                                    }

                                                    else {
                                                        // Attempt update query execution
                                                        $sql = "UPDATE user SET Banned='1' WHERE userID= $row[0]";

                                                        if(mysqli_query($con, $sql)){
                                                            print_r( '<p>'."Records were updated successfully. User Ban.".'<p>');
                                                            $date = date("Y-m-d");
                                                            $sql2 = "INSERT INTO bannedusers (UserID, BanID, `Date`, BannedByID, Reason) VALUES ('.$row[0].', '0', '$date', '.$banbyid.', '$banreason')"; // BannedByID to $_SESSION['id'] 
                                                            if(mysqli_query($con, $sql2)) {
                                                                print_r( "Successfully added to Banned User List.");
                                                                echo( '<script>
                                                                            document.location.reload(true);
                                                                    </script>');
                                                            }
                                                            else
                                                            {
                                                                print_r( "ERROR: Could not able to execute $sql2. " . mysqli_error($con));
                                                            }
                                                        } 
                                                        
                                                        else {
                                                            print_r( "ERROR: Could not able to execute $sql. " . mysqli_error($con));
                                                        }
                                                    }
                                                } 
                                            }
                                            
                                            // Close connection
                                            mysqli_close($con);
                                        }    
                                    ?>

                                    <?php 
                                    
                                    //Unban A User
                                    date_default_timezone_set('Europe/Dublin');
                                    
                                        if(!empty($_POST["username2"])){

                                            require_once "includes/database.php";
                                            
                                            $username2 = $_POST["username2"];

                                            // Check connection
                                            if($con === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            // Fetch Data
                                            $query = "SELECT userID, Username, Banned FROM user WHERE Username ='$username2'";

                                            $resultbis = mysqli_query($con, $query);

                                            if (!$resultbis) {
                                                print_r("Error : No user found" . $con -> error);
                                            }

                                            else {
                                                $rowbis = mysqli_fetch_row($resultbis);

                                                if (empty($rowbis)){
                                                    print_r("Error : No user found" . $con -> error);
                                                }
                                                
                                                else {
                                                    print_r( "User ID Value :");
                                                    print_r( '<p>'.$rowbis[0].'</p>'); // id
                                                    print_r( "Username Value :");
                                                    print_r( '<p>'.$rowbis[1].'</p>'); // username
                                                    print_r( "Banned Value :");
                                                    print_r( '<p>'.$rowbis[2].'</p>'); // Bool Banned

                                                    // Attempt update query execution
                                                    $sql = "UPDATE user SET Banned='0' WHERE userID= $rowbis[0]";

                                                    if($rowbis[2] == 0) {
                                                        print_r( "User Already Unbanned.");
                                                    }
                                                    else {
                                                        if(mysqli_query($con, $sql)){
                                                            print_r( '<p>'."Records were updated successfully. User Unban.".'<p>');
                                                            $date = date("Y-m-d");
                                                            $sql2 = "DELETE FROM bannedusers WHERE userID= $rowbis[0]";
                                                            if(mysqli_query($con, $sql2)) {
                                                                print_r( "Successfully removed from Banned User List.");
                                                                echo( '<script>
                                                                            document.location.reload(true);
                                                                    </script>');
                                                            }
                                                            else
                                                            {
                                                                print_r( "ERROR: Could not able to execute $sql2. " . mysqli_error($con));
                                                            }
                                                        } 
                                                        
                                                        else {
                                                            print_r( "ERROR: Could not able to execute $sql. " . mysqli_error($con));
                                                        }
                                                    }
                                                } 
                                            }
                                            // Close connection
                                            mysqli_close($con);
                                        }
                                    ?>
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