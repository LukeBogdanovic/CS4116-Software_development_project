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
        $stmt = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, connections.ConnectionDate FROM connections INNER JOIN user ON connections.userID2=user.UserID WHERE connections.userID1 = $id;";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $userID, $username, $firstname, $surname, $dob, $connectionDate);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 200, 'message' => 'Users connected to logged in user found');
                    // Put all retrieved UserIDs into results array
                    while (mysqli_stmt_fetch($stmt)) {
                        $age = get_age($dob);
                        $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'connectionDate' => $connectionDate);
                        array_push($results, $user);
                        array_push($userIDs, $userID);
                    }
                    array_push($result, $results);
                } else {
                    $result = array('status' => 403, 'message' => 'No Users found connected to the logged in user');
                }
            }
        }
        for ($i = 0; $i < count($userIDs); $i++) {
            $stmt = "SELECT profile.Description FROM profile WHERE profile.UserID = ?";
            if ($stmt = mysqli_prepare($con, $stmt)) {
                mysqli_stmt_bind_param($stmt, "s", $userIDs[$i]);
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $description);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        // Put all retrieved UserIDs into results array
                        while (mysqli_stmt_fetch($stmt)) {
                            $result[0][$i]['description'] = $description;
                        }
                    } else {
                        $result = array('status' => 403, 'message' => 'No Users found connected to the logged in user');
                    }
                }
            }
        }
        echo json_encode($result);
        return;
    }
}
