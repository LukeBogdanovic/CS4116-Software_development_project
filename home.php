<?php
session_start();
/* Checking if the user is not logged in
 * If user is not logged in: the user is redirected to the login page and the script exits
 */
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

require_once "includes/utils.php";
require_once "includes/database.php";

$id = $_SESSION['id'];

$potentialUsers = [];
$results = [];
$result =[];
//get current users gender and seeking preferences
//used for validating suggestions 
$getSeeking = "SELECT Seeking FROM profile WHERE userID = ?";
if($stmt = mysqli_prepare($con,$getSeeking)){
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $seeking);
        mysqli_stmt_fetch($stmt);
    }
}

//return array of userID's of users who share an interest with logged in user
//array is formatted as id => reason  where reasonm is the interests matching
$commonInterest = "SELECT I2.UserID, A.InterestName FROM interests I1 INNER JOIN interests I2 ON (I2.InterestID = I1.InterestID) JOIN availableinterests A ON (A.InterestID = I1.InterestID) WHERE I1.UserID = ? AND I2.UserID <> ?";
if($stmt = mysqli_prepare($con, $commonInterest)){
    mysqli_stmt_bind_param($stmt, "ii", $id, $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        //bind results of search to user variable 
        mysqli_stmt_bind_result($stmt, $userID, $interestShared);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $result = array('status' => 200, 'message' => 'Users found with similar interests');
            // Put all retrieved UserIDs into results array
            while (mysqli_stmt_fetch($stmt)) {
                $usersInterests = [];
                array_push($usersInterests, $interestShared);
                if(empty($potentialUsers[$userID])){
                    $potentialUsers[$userID] = $usersInterests;
                }else {
                    array_push($potentialUsers[$userID], $interestShared);
                }
            }
        }
    }
}
//return users from potentialUSers whos gender matches users preference
$returnSuitableUsers = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Description FROM user LEFT JOIN profile ON user.UserID=profile.UserID WHERE user.UserID = ? and profile.Gender = ?;";
$suggestedUsers = [];
foreach($potentialUsers as $key => $value){
    if($stmt = mysqli_prepare($con, $returnSuitableUsers)){
        mysqli_stmt_bind_param($stmt, "is", $key, $seeking);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $userID, $username, $firstname, $surname, $dob, $description);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $result = array('status' => 200, 'message' => 'Users found matching search criteria');
                // Put all retrieved UserIDs into results array
                while (mysqli_stmt_fetch($stmt)) {
                    $age = get_age($dob);
                    //Create profile description string if profile descritpion returns null
                    if(is_null($description)){
                        $description = $firstname. ' ' . $surname . ' has not created their profile yet';
                    }
                    $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'description' => $description, 'interests in common'=> $value);
                    array_push($suggestedUsers, $user);
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Home</title>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php
    require_once "includes/navbar.php";
    ?>
    <?php 
    foreach($suggestedUsers as $value){
        echo "<div> <p> 'users' </p></div>";
        echo print_r($value);
        
    }
    ?>
    <?php
    require_once "includes/footer.php"
    ?>

</body>

</html>