<?php
require "../../includes/utils.php";
/**
 * Entry point for the script.
 * Checks if there is a function specified in the ajax request.
 * Calls the function that is requested and provides all data sent from the frontend
 * to the backend via ajax request.
 */
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "get_Search_result":
            get_Search_result($_POST['search']);
            break;
        case "get_user":
            get_user();
            break;
    }
}

/**
 * Returns the user details of all users whose username/firstname & surname matching the searched string
 * @param string search
 */
function get_Search_result($search = "")
{
    // Init our database connection
    require "../../includes/database.php";
    $result = [];
    $results = [];
    $user = [];
    $search = trim($search);
    // Check that the request method is a POST request
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //statement to find all usernames or first names similar to inputted username 
        $stmt = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Description FROM user LEFT JOIN profile ON user.UserID=profile.UserID WHERE CONCAT(user.firstname, ' ',user.Surname, '¾', user.Username) LIKE '%$search%';";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                //bind results of search to user variable 
                mysqli_stmt_bind_result($stmt, $userID, $username, $firstname, $surname, $dob, $description);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 200, 'message' => 'Users found matching search criteria');
                    // Put all retrieved UserIDs into results array
                    while (mysqli_stmt_fetch($stmt)) {
                        $age = get_age($dob);
                        //Create profile description string if profile descritpion returns null
                        if (is_null($description))
                            $description = $firstname . ' ' . $surname . ' has not created their profile yet';
                        $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'description' => $description);
                        array_push($results, $user);
                    }
                    array_push($result, $results);
                } else
                    $result = array('status' => 403, 'message' => 'No Users found matching search criteria');
                echo json_encode($result);
                return;
            }
        }
    }
}

function get_user()
{
    require "../../includes/database.php";
    $stmt = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Description FROM user LEFT JOIN profile ON user.UserID=profile.UserID WHERE user.userID = ?";
    if ($stmt = mysqli_prepare($con, $stmt)) {
        mysqli_stmt_bind_param($stmt, "i", $_POST['id']);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $id, $username, $firstname, $surname, $dob, $description);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_fetch($stmt);
                $results = array('userID' => $id, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'dob' => $dob, 'description' => $description);
                $result = array('status' => 200, 'message' => "User found");
                $result['user'] = $results;
            } else
                $result = array('status' => 403, 'message' => "User not found");
        }
    }
    echo json_encode($result);
    return;
}
