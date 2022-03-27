<?php
require "../../includes/utils.php";

if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "get_Connected_Users":
            if (isset($_POST['id']))
                get_Connected_Users($_POST['id']);
            break;
    }
}


function get_Connected_Users($id)
{
    require "../../includes/database.php";
    $result = [];
    $results = [];
    $userIDs = [];
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
                        // Changing dob to age and putting in array
                        $age = get_age($dob);
                        $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'daysSinceConnection' => date_difference($connectionDate), 'description' => $description);
                        array_push($results, $user);
                        array_push($userIDs, $userID);
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
