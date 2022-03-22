<?php

/**
 * Entry point for the script.
 * Checks if there is a function specified in the ajax request.
 * Calls the function that is requested and provides all data sent from the frontend
 * to the backend via ajax request.
 */
if (isset($_POST['function'])) 
{
    if (isset($_POST['search']))
    {
        get_Search_result_username($_POST['search']);
    }
}

function get_Search_result_username($search)
{
    // Init our database connection
    require "../../includes/database.php";
    $result = [];
    $results = [];
    // Check that the request method is a POST request
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //statement to find all usernames similar to inputted username 
        $stmt = "SELECT UserID FROM user WHERE Username = %?%";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            mysqli_stmt_bind_param($stmt, "s", $search);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                //bind results of search to user variable 
                mysqli_stmt_bind_result($stmt, $userID);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 200, 'message' => 'Users found matching search criteria');
                    // Put all retrieved UserIDs into results array
                    while (mysqli_stmt_fetch($stmt)) {
                        array_push($results, $userID);
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
