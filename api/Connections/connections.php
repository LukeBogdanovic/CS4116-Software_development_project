<?php
require "../../includes/utils.php";

if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "get_Connected_Users":
            if (isset($_POST['id']))
                get_Connected_Users($_POST['id']);
            break;
        case "get_Liked_Users":
            if (isset($_POST['id']))
                get_Liked_Users($_POST['id']);
            break;
        case "like_user":
            like_user();
            break;
        case "check_connection":
            check_connection();
            break;
    }
}

function get_Connected_Users($id)
{
    require "../../includes/database.php";
    $results = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Getting all users with connection to the user that is logged in
        $stmt = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, connections.ConnectionDate, profile.Description FROM connections INNER JOIN user ON( CASE WHEN connections.userID1 = $id THEN connections.userID2 = user.UserID WHEN connections.userID2 = $id THEN connections.userID1 = user.UserID ELSE NULL END ) INNER JOIN profile ON profile.UserID = user.userID WHERE ( CASE WHEN connections.userID2 = $id THEN connections.userID2 = $id WHEN connections.userID1 = $id THEN connections.userID1 = $id ELSE NULL END ) ORDER BY connections.ConnectionDate DESC;";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $userID, $username, $firstname, $surname, $dob, $connectionDate, $description);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 200, 'message' => 'Users connected to logged in user found');
                    // Put all retrieved UserIDs into results array
                    while (mysqli_stmt_fetch($stmt)) {
                        $photoStmt = "SELECT photos.PhotoID FROM photos WHERE photos.UserID = ?";
                        if ($photoStmt = mysqli_prepare($con, $photoStmt)) {
                            if (mysqli_stmt_bind_param($photoStmt, "i", $userID)) {
                                if (mysqli_stmt_execute($photoStmt)) {
                                    mysqli_stmt_store_result($photoStmt);
                                    mysqli_stmt_bind_result($photoStmt, $PhotoID);
                                    mysqli_stmt_fetch($photoStmt);
                                }
                            }
                        }
                        // Changing dob to age and putting in array
                        $age = get_age($dob);
                        $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'daysSinceConnection' => date_difference($connectionDate, "Connected"), 'description' => $description, 'photo' => $PhotoID);
                        array_push($results, $user);
                        $PhotoID = "";
                    }
                    array_push($result, $results);
                } else {
                    $result = array('status' => 403, 'message' => 'No Users found connected to the logged in user');
                }
                echo json_encode($result);
                return;
            }
        }
    }
}

function get_Liked_Users($id)
{
    require "../../includes/database.php";
    $results = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $stmt = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, liked.LikedDate, profile.Description FROM liked INNER JOIN user ON liked.UserID2 = user.UserID INNER JOIN profile ON profile.UserID = user.UserID WHERE liked.UserID1 = $id ORDER BY liked.LikedDate DESC;";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $userID, $username, $firstname, $surname, $dob, $likedDate, $description);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 200, 'message' => 'Users liked by logged in user found');
                    while (mysqli_stmt_fetch($stmt)) {
                        $age = get_age($dob);
                        $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'daysSinceLike' => date_difference($likedDate, "Liked"), 'description' => $description);
                        array_push($results, $user);
                    }
                    array_push($result, $results);
                } else {
                    $result = array('status' => 403, 'message' => 'No Users found liked by the logged in user');
                }
                echo json_encode($result);
                return;
            }
        }
    }
}

function like_user()
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $stmt = "SELECT * FROM liked WHERE UserID1 = ? AND UserID2 = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_bind_param($stmt, "ii", $_POST['userID1'], $_POST['userID2'])) {
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) < 1) {
                        $liked = true;
                    } else {
                        $result = array('status' => 403, 'message' => "User liked previously");
                        $liked = false;
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
        if ($liked) {
            $stmt = "INSERT INTO liked (UserID1,UserID2,LikedDate) VALUES (?,?,?)";
            if ($stmt = mysqli_prepare($con, $stmt)) {
                $date = date("Y-m-d");
                if (mysqli_stmt_bind_param($stmt, "iis", $_POST['userID1'], $_POST['userID2'], $date)) {
                    if (mysqli_stmt_execute($stmt)) {
                        $result = array('status' => 200, 'message' => "User liked succesfully.");
                    } else {
                        $result = array('status' => 403, 'message' => "Unable to like user.");
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($con);
    echo json_encode($result);
    return;
}

function check_connection()
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID1 = $_POST['userID1'];
        $userID2 = $_POST['userID2'];
        $stmt = "SELECT * FROM liked WHERE(CASE WHEN liked.UserID1 = $userID2 THEN liked.UserID2 = $userID1 WHEN liked.UserID2 = $userID2 THEN liked.UserID1 = $userID1 ELSE NULL END);";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 2) {
                    $connect = true;
                } else {
                    $connect = false;
                    $result = array('status' => 403, 'message' => "User's are not connected yet");
                }
            }
            mysqli_stmt_close($stmt);
        }
        if ($connect) {
            $date = date("Y-m-d");
            $stmt = "INSERT INTO connections (ConnectionID,userID1,userID2,ConnectionDate) VALUES (DEFAULT,$userID1,$userID2,?);";
            if ($stmt = mysqli_prepare($con, $stmt)) {
                if (mysqli_stmt_bind_param($stmt, "s", $date)) {
                    if (mysqli_stmt_execute($stmt)) {
                        $result = array('status' => 200, 'message' => "Connection added succesfully");
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        echo json_encode($result);
        return;
    }
}
