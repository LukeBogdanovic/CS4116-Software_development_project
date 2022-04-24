<?php 
session_start();
// Checking if the user is already logged in to the website and redirecting to Home if they are
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
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

                    <!-- Ban User List Display-->
                        <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                            <h5 class="text-right">List of Banned Users:</h5>
                        </div>

                        <?php
                            require_once "includes/database.php";

                            $query = "SELECT userID, Username, Banned FROM user WHERE Banned ='1'";

                            $result = mysqli_query($con, $query);

                            if ($result->num_rows > 0) {
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

                        <!-- Ban User Button-->
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

                                        <input class="btn input-group-text btn-warning" type="submit" value="Ban">

                                    </div>
                                </form>

                            </div>

                        </div>

                        <!-- Unban User Button-->
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

                        <!-- Admin User Button-->
                        <div class="row mt-3">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-right">Give Admin to a User:</h5>
                            </div>

                            <div class="input-group mb-3">

                            <form action="" method="POST">
                                    <div>
                                        <label class="labels">Username</label>
                                        <input type="text" id="username3" name="username3">
                                        <input class="btn input-group-text btn-info" type="submit" value="Admin">
                                    </div>

                                </form>


                            </div>

                        </div>

                        <!-- Remove Admin User Button-->
                        <div class="row mt-3">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-right">Remove Admin to a User:</h5>
                            </div>

                            <div class="input-group mb-3">

                            <form action="" method="POST">
                                    <div>
                                        <label class="labels">Username</label>
                                        <input type="text" id="username4" name="username4">
                                        <input class="btn input-group-text btn-info" type="submit" value="Remove Admin">
                                    </div>

                                </form>


                            </div>

                        </div>

                        <!-- Remove User Button-->
                        <div class="row mt-3">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-right">Remove a User:</h5>
                            </div>

                            <div class="input-group mb-3">

                            <form action="" method="POST">
                                    <div>
                                        <label class="labels">Username</label>
                                        <input type="text" id="username5" name="username5">
                                        <input class="btn input-group-text btn-danger" type="submit" value="Remove User">
                                    </div>

                                </form>


                            </div>

                        </div>

                        <!-- Ban A user -->
                            <div class="justify-content-between align-items-center">
                                <?php 
                                    date_default_timezone_set('Europe/Dublin');

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
                                                                /*
                                                                echo( '<script>
                                                                            document.location.reload(true);
                                                                    </script>');
                                                                */
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

                                    <!-- Unban A user -->
                                    <?php 
                                    date_default_timezone_set('Europe/Dublin');
                                    
                                        if(!empty($_POST["username2"])){

                                            require_once "includes/database.php";
                                            
                                            $username2 = $_POST["username2"];

                                            // Check connection
                                            if($con === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            // Fetch Data
                                            $query2 = "SELECT userID, Username, Banned FROM user WHERE Username ='$username2'";

                                            $resultbis = mysqli_query($con, $query2);

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
                                                    $sql3 = "UPDATE user SET Banned='0' WHERE userID= $rowbis[0]";

                                                    if($rowbis[2] == 0) {
                                                        print_r( "User Already Unbanned.");
                                                    }
                                                    else {
                                                        if(mysqli_query($con, $sql3)){
                                                            print_r( '<p>'."Records were updated successfully. User Unban.".'<p>');
                                                            $date = date("Y-m-d");
                                                            $sql4 = "DELETE FROM bannedusers WHERE userID= $rowbis[0]";
                                                            if(mysqli_query($con, $sql4)) {
                                                                print_r( "Successfully removed from Banned User List.");
                                                                /*
                                                                echo( '<script>
                                                                            document.location.reload(true);
                                                                    </script>');
                                                                */
                                                            }
                                                            else
                                                            {
                                                                print_r( "ERROR: Could not able to execute $sql4. " . mysqli_error($con));
                                                            }
                                                        } 
                                                        
                                                        else {
                                                            print_r( "ERROR: Could not able to execute $sql3. " . mysqli_error($con));
                                                        }
                                                    }
                                                } 
                                            }
                                            // Close connection
                                            mysqli_close($con);
                                        }
                                    ?>

                                <!-- Admin A user -->
                                <?php 
                                        if(!empty($_POST["username3"])){

                                            require_once "includes/database.php";

                                            $username3 = $_POST["username3"];

                                            // Check connection
                                            if($con === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            // Fetch Data
                                            $query3 = "SELECT userID, Username, `Admin` FROM user WHERE Username ='$username3'";

                                            $result3 = mysqli_query($con, $query3);

                                            if (!$result3) {
                                                print_r("Error : No user found" . $con -> error);
                                            }

                                            else {
                                                $row3 = mysqli_fetch_row($result3);

                                                if (empty($row3)){
                                                    print_r("Error : No user found" . $con -> error);
                                                }
                                                
                                                else {
                                                    print_r( "User ID Value :");
                                                    print_r( '<p>'.$row3[0].'</p>'); // id
                                                    print_r( "Username Value :");
                                                    print_r( '<p>'.$row3[1].'</p>'); // username
                                                    print_r( "Admin Value :");
                                                    print_r( '<p>'.$row3[2].'</p>'); // Admin Banned

                                                    if ($row3[2] == 1) {
                                                        print_r( "User Already Admin.");
                                                    }

                                                    else {
                                                        // Attempt update query execution
                                                        $sql5 = "UPDATE user SET `Admin`='1' WHERE userID= $row3[0]";

                                                        if(mysqli_query($con, $sql5)){
                                                            print_r( '<p>'."Records were updated successfully. User now Admin.".'<p>');
                                                        } 
                                                        
                                                        else {
                                                            print_r( "ERROR: Could not able to execute $sql5. " . mysqli_error($con));
                                                        }
                                                    }
                                                } 
                                            }
                                            
                                            // Close connection
                                            mysqli_close($con);
                                        }    
                                    ?>

                                    <!-- Remove Admin A user -->
                                    <?php 
                                        if(!empty($_POST["username4"])){

                                            require_once "includes/database.php";

                                            $username4 = $_POST["username4"];

                                            // Check connection
                                            if($con === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            // Fetch Data
                                            $query3 = "SELECT userID, Username, `Admin` FROM user WHERE Username ='$username4'";

                                            $result3 = mysqli_query($con, $query3);

                                            if (!$result3) {
                                                print_r("Error : No user found" . $con -> error);
                                            }

                                            else {
                                                $row3 = mysqli_fetch_row($result3);

                                                if (empty($row3)){
                                                    print_r("Error : No user found" . $con -> error);
                                                }
                                                
                                                else {
                                                    print_r( "User ID Value :");
                                                    print_r( '<p>'.$row3[0].'</p>'); // id
                                                    print_r( "Username Value :");
                                                    print_r( '<p>'.$row3[1].'</p>'); // username
                                                    print_r( "Admin Value :");
                                                    print_r( '<p>'.$row3[2].'</p>'); // Admin Banned

                                                    if ($row3[2] == 0) {
                                                        print_r( "User is not an Admin.");
                                                    }

                                                    else {
                                                        // Attempt update query execution
                                                        $sql5 = "UPDATE user SET `Admin`='0' WHERE userID= $row3[0]";

                                                        if(mysqli_query($con, $sql5)){
                                                            print_r( '<p>'."Records were updated successfully. Admin Status Removed.".'<p>');
                                                        } 
                                                        
                                                        else {
                                                            print_r( "ERROR: Could not able to execute $sql5. " . mysqli_error($con));
                                                        }
                                                    }
                                                } 
                                            }
                                            
                                            // Close connection
                                            mysqli_close($con);
                                        }    
                                    ?>

                                    <!-- Remove A user -->
                                    <?php 
                                        if(!empty($_POST["username5"])){

                                            require_once "includes/database.php";

                                            $username5 = $_POST["username5"];

                                            // Check connection
                                            if($con === false){
                                                die("ERROR: Could not connect. " . mysqli_connect_error());
                                            }

                                            // Fetch Data
                                            $query3 = "SELECT userID, Username FROM user WHERE Username ='$username5'";

                                            $result3 = mysqli_query($con, $query3);

                                            if (!$result3) {
                                                print_r("Error : No user found" . $con -> error);
                                            }

                                            else {
                                                $row3 = mysqli_fetch_row($result3);

                                                if (empty($row3)){
                                                    print_r("Error : No user found" . $con -> error);
                                                }
                                                
                                                else {
                                                    print_r( "User ID Value :");
                                                    print_r( '<p>'.$row3[0].'</p>'); // id
                                                    print_r( "Username Value :");
                                                    print_r( '<p>'.$row3[1].'</p>'); // username

                                                    // Attempt update query execution
                                                    $sql5 = "DELETE FROM user WHERE userID= $row3[0]";

                                                    if(mysqli_query($con, $sql5)){
                                                        print_r( '<p>'."Records were updated successfully. User Removed.".'<p>');
                                                    } 
                                                    
                                                    else {
                                                        print_r( "ERROR: Could not able to execute $sql5. " . mysqli_error($con));
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