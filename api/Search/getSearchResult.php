<?php

/**
 * Entry point for the script.
 * Checks if there is a function specified in the ajax request.
 * Calls the function that is requested and provides all data sent from the frontend
 * to the backend via ajax request.
 */
if (isset($_POST['function'])) {
    if (isset($_POST['search'])) {
        get_Search_result_username($_POST['search']);
    }
}

/**
 * Returns the username of all users matching the searched string
 * @param string search
 */
function get_Search_result_username($search)
{
    // Init our database connection
    require "../../includes/database.php";
    $result = [];
    $results = [];
    $user = [];
    $search = trim($search);
    // Check that the request method is a POST request
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!$search) {
            $result = array('status' => 403, 'message' => 'No Users found matching search criteria');
            echo json_encode($result);
            return;
        }
        //statement to find all usernames similar to inputted username 
        $stmt = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, profile.Age, profile.Description FROM user INNER JOIN profile ON user.UserID=profile.UserID WHERE user.Username LIKE '%$search%'";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                //bind results of search to user variable 
                mysqli_stmt_bind_result($stmt, $userID, $username, $firstname, $surname, $age, $description);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 200, 'message' => 'Users found matching search criteria');
                    // Put all retrieved UserIDs into results array
                    while (mysqli_stmt_fetch($stmt)) {
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