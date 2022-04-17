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

                        <div class="table-scroll scrollbar">

                            <table class="table table-striped">

                                <thead>

                                    <tr>
                                        <th scope="col">Username</th>
                                        <th scope="col">Reported By</th>
                                        <th scope="col">Reason for Report</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>
                                        <th scope="row">username1</th>
                                        <td>The Cool Police</td>
                                        <td>Not being cool enough</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">username2</th>
                                        <td>The Cool Police</td>
                                        <td>Being too cool</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">username3</th>
                                        <td>The Cool Police</td>
                                        <td>Being too cool</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">username4</th>
                                        <td>The Cool Police</td>
                                        <td>Being too cool</td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                        <div class="row mt-5">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-right">Ban User:</h5>
                            </div>

                            <div class="input-group mb-3">

                                <form action="" method="POST">
                                    <div>
                                        <label class="labels">username</label>
                                        <input type="text" id="username" name="username">
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
                                        <label class="labels">username</label>
                                        <input type="text" id="username2" name="username2">
                                    </div>
                                </form>


                            </div>

                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                            <h5 class="text-right">List of Banned Users:</h5>
                        </div>

                        <div class="table-scroll scrollbar">

                            <table class="table table-striped">

                                <thead>

                                    <tr>
                                        <th scope="col">Username</th>
                                        <th scope="col">Banned By</th>
                                        <th scope="col">Reason for Ban</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>
                                        <th scope="row">username1</th>
                                        <td>Admin</td>
                                        <td>Reason</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">username2</th>
                                        <td>Admin</td>
                                        <td>Reason</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">username3</th>
                                        <td>Admin</td>
                                        <td>Reason</td>
                                    </tr>

                                    <tr>
                                        <th scope="row">username4</th>
                                        <td>Admin</td>
                                        <td>Reason</td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </main>

        <?php 
        
        //Ban A User

            if(!empty($_POST["username"])){

                $username = $_POST["username"];

                $link = mysqli_connect("localhost", "root", "", "test");
        
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }

                // Fetch Data
                $query = "SELECT userID, Username, Banned FROM user WHERE Username ='$username'";

                $result = mysqli_query($link, $query);

                if (!$result) {
                    echo("Error : No user found" . $link -> error);
                }

                else {
                    $row = mysqli_fetch_row($result);

                    if (empty($row)){
                        echo("Error : No user found" . $link -> error);
                    }
                    
                    else {
                        echo "User ID Value :";
                        echo '<p>'.$row[0].'</p>'; // id
                        echo "Username Value :";
                        echo '<p>'.$row[1].'</p>'; // username
                        echo "Banned Value :";
                        echo '<p>'.$row[2].'</p>'; // Bool Banned

                        // Attempt update query execution
                        $sql = "UPDATE user SET Banned='1' WHERE userID= $row[0]";

                        if(mysqli_query($link, $sql)){
                            echo "Records were updated successfully. User Ban.";
                        } 
                        
                        else {
                            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                        }
                    } 
                }
                
                // Close connection
                mysqli_close($link);
               }
        
        
        ?>

        <?php 
        
        //Unban A User
        
            if(!empty($_POST["username2"])){

                $username2 = $_POST["username2"];

                $link = mysqli_connect("localhost", "root", "", "test");
        
                // Check connection
                if($link === false){
                    die("ERROR: Could not connect. " . mysqli_connect_error());
                }

                // Fetch Data
                $query = "SELECT userID, Username, Banned FROM user WHERE Username ='$username2'";

                $resultbis = mysqli_query($link, $query);

                if (!$resultbis) {
                    echo("Error : No user found" . $link -> error);
                }

                else {
                    $rowbis = mysqli_fetch_row($resultbis);

                    if (empty($rowbis)){
                        echo("Error : No user found" . $link -> error);
                    }
                    
                    else {
                        echo "User ID Value :";
                        echo '<p>'.$rowbis[0].'</p>'; // id
                        echo "Username Value :";
                        echo '<p>'.$rowbis[1].'</p>'; // username
                        echo "Banned Value :";
                        echo '<p>'.$rowbis[2].'</p>'; // Bool Banned

                        // Attempt update query execution
                        $sql = "UPDATE user SET Banned='0' WHERE userID= $rowbis[0]";

                        if(mysqli_query($link, $sql)){
                            echo "Records were updated successfully. User Unban.";
                        } 
                        
                        else {
                            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                        }
                    } 
                }

                // Close connection
                mysqli_close($link);
               }
        
        
        ?>

        <?php
            require_once "includes/footer.php";
        ?>

    </body>

</html>