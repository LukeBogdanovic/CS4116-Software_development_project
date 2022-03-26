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
        $stmt = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Description FROM user INNER JOIN profile ON user.UserID=profile.UserID WHERE CONCAT(user.firstname, ' ',user.Surname, ' ', user.Username) LIKE '%$search%';";
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
                        $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'description' => $description);
                        array_push($results, $user);
                    }
                    array_push($result, $results);
                } else {
                    $result = array('status' => 403, 'message' => 'No Users found matching search criteria');
                }
                echo json_encode($result);
                return;
            }
        }
    }
}
