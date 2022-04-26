<?php

/**
 * Entry point for the script.
 * Checks if there is a function specified in the ajax request.
 * Calls the function that is requested and provides all data sent from the frontend
 * to the backend via ajax request.
 */
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "get_Security_Questions":
            if (isset($_POST['username']))
                get_Security_Questions($_POST['username']);
            break;
        case "get_Security_Answers":
            if (isset($_POST['username']) && isset($_POST['SecurityQuestion']) && isset($_POST['SecurityAnswer']))
                get_Security_Answers($_POST['username'], $_POST['SecurityQuestion'], $_POST['SecurityAnswer']);
            break;
        case "reset_Password":
            if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmPwd']))
                reset_Password($_POST['username'], $_POST['password'], $_POST['confirmPwd']);
            break;
    }
}

/**
 * Returns the sceurity questions answered by the specified user from the database and server to 
 * the frontend ajax request 
 * @param string $username
 */
function get_Security_Questions($username)
{
    // Init our database connection
    require "../../includes/database.php";
    $results = [];
    // Check that the request method is a POST request
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!$id = get_userID($username)) {
            return;
        }
        // Statement to find all security Questions answered by the user in the database
        $stmt = "SELECT SecurityQuestion FROM securityQA WHERE UserID = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            mysqli_stmt_bind_param($stmt, "s", $db_user_id);
            $db_user_id = $id;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $securityQ);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 200, 'message' => 'User Security Questions retrieved.');
                    // Put all retrieved security questions into results array
                    while (mysqli_stmt_fetch($stmt)) {
                        array_push($results, $securityQ);
                    }
                    array_push($result, $results);
                } else {
                    $result = array('status' => 403, 'message' => 'User Security Questions not found.');
                }
                echo json_encode($result);
                return;
            }
        }
    }
}

function get_Security_Answers($username, $securityQ, $securityAnswer)
{
    // Init our database connection
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!$id = get_userID($username)) {
            return;
        }
        $stmt = "SELECT SecurityAnswer FROM securityQA WHERE UserID = ? AND SecurityQuestion = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            mysqli_stmt_bind_param($stmt, "ss", $db_user_id, $db_secQ);
            $db_user_id = $id;
            $db_secQ = $securityQ;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $securityA);
                mysqli_stmt_fetch($stmt);
                if ($securityAnswer === $securityA)
                    //If security answer provided and found in database match return 200 status
                    $result = array('status' => 200, 'message' => "Security Answer correct");
                else
                    $result = array('status' => 403, 'message' => "Security Answer incorrect");
                echo json_encode($result);
                return;
            }
        }
    }
}


function reset_password($username, $password, $confirm_password)
{
    // Checking is the password and confirm password entered the exact same 
    if ($password === $confirm_password) {
        // Init our database connection
        require "../../includes/database.php";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (!$id = get_userID($username)) {
                return;
            }
            $stmt = "SELECT Password FROM user WHERE UserID = ?";
            if ($stmt = mysqli_prepare($con, $stmt)) {
                mysqli_stmt_bind_param($stmt, "s", $db_user_id);
                $db_user_id = $id;
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $hashed);
                    mysqli_stmt_fetch($stmt);
                    // Check has user entered previously entered password
                    if (password_verify($password, $hashed)) {
                        $result = array('status' => 403, 'message' => "Password entered is the same as previous password.");
                        echo json_encode($result);
                        return;
                    }
                }
            }
            // Encrypt the password
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = "UPDATE `user` SET `Password` = ? WHERE `user`.`UserID` = ?;";
            if ($stmt = mysqli_prepare($con, $stmt)) {
                mysqli_stmt_bind_param($stmt, "ss", $db_password, $db_user_id);
                $db_password = $password;
                $db_user_id = $id;
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    // Check the number of affected rows of the update statement
                    if (mysqli_affected_rows($con) > 0) {
                        $result = array('status' => 200, 'message' => "Password Updated Succesfully.");
                    } else {
                        $result = array('status' => 403, 'message' => "Password Not Updated Succesfully.");
                    }
                    echo json_encode($result);
                    return;
                }
            }
        }
    }
    $result = array('status' => 403, 'message' => "Password and Confirm Password entered do not match.");
    echo json_encode($result);
    return;
}

/**
 * Returns the userID of the username queried in the database
 * @param string $username
 */
function get_userID($username)
{
    require "../../includes/database.php";
    $username = trim($username);
    $stmt = "SELECT UserID FROM `user` WHERE Username = ?";
    if ($stmt = mysqli_prepare($con, $stmt)) {
        // Bind the params to the statement
        mysqli_stmt_bind_param($stmt, "s", $db_username);
        $db_username = $username;
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            // Check that there is only one user matching
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $id);
                mysqli_stmt_fetch($stmt);
                return $id;
            } else {
                // If the user can't be found return this information to the user and exit function
                $result = array('status' => 403, 'message' => "Username cannot be found");
                echo json_encode($result);
                return;
            }
        }
    }
}
