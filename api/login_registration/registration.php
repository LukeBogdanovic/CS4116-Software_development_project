<?php
require "../../includes/utils.php";

if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "info_validate":
            info_validate();
            break;
        case "register_user":
            register_user();
            break;
    }
}


function info_validate()
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Fill in all variables from the form
        $username = mysqli_real_escape_string($con, trim($_POST['username']));
        $firstname = mysqli_real_escape_string($con, trim($_POST['firstname']));
        $surname = mysqli_real_escape_string($con, trim($_POST['surname']));
        $dob = mysqli_real_escape_string($con, trim($_POST['dob']));
        $email = mysqli_real_escape_string($con, trim($_POST['email']));
        $password = mysqli_real_escape_string($con, trim($_POST['password']));
        $confirmpassword = mysqli_real_escape_string($con, trim($_POST['confirmpassword']));

        //Check date of birth entered by user
        if (get_age($dob) < 18) {
            $result = array('status' => 403, 'message' => "Unfortunately you are too young to avail of our service");
            echo json_encode($result);
            return;
        } else if (get_age($dob) > 130) {
            $result = array('status' => 403, 'message' => "You have provided an invalid age");
            echo json_encode($result);
            return;
        }

        //must run checks on email and username to see if theyre already taken
        //Username check
        $username_test = "SELECT Username, Email FROM user WHERE Username = '$username' OR Email = '$email'";
        //prepare sql statement for username check
        if ($stmt = mysqli_prepare($con, $username_test)) {
            // Attempt to execute the sql statement
            if (mysqli_stmt_execute($stmt)) {
                // Store the result of the sql statement
                mysqli_stmt_store_result($stmt);
                //bind results from stmt to variables to be used
                mysqli_stmt_bind_result($stmt, $username_stored, $email_stored);
                //fetch the results of the stmt
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 403);
                    while (mysqli_stmt_fetch($stmt)) {
                        //if the result returns with a username matching the one entered by the user, the username is already in use
                        if ($username == $username_stored) {
                            $result['message_username'] = "Username is already in use";
                        }
                        //if the result returns with an email matching the one entered by the user, the email is already in use
                        if ($email == $email_stored) {
                            $result['message_email'] = "Email is already in use";
                        }
                    }
                } else
                    $result = array('status' => 200, 'message' => 'All data has been validated!');
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            //Close statement
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
    }
    echo json_encode($result);
    return;
}


function register_user()
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = mysqli_real_escape_string($con, trim($_POST['username']));
        $firstname = mysqli_real_escape_string($con, trim($_POST['firstname']));
        $surname = mysqli_real_escape_string($con, trim($_POST['surname']));
        $dob = mysqli_real_escape_string($con, trim($_POST['dob']));
        $email = mysqli_real_escape_string($con, trim($_POST['email']));
        $password = mysqli_real_escape_string($con, trim($_POST['password']));
        $confirmpassword = mysqli_real_escape_string($con, trim($_POST['confirmpassword']));
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = "INSERT INTO user (UserID, Username, Firstname, Surname, DateOfBirth, Email, Password) VALUES (DEFAULT,'$username', '$firstname', '$surname', '$dob', '$email','$hashed_password')";
        if ($stmt = mysqli_prepare($con, $insert)) {
            if (mysqli_stmt_execute($stmt)) {
                if ($id = mysqli_insert_id($con)) {
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['admin'] = false;
                    $result = array('status' => 200, 'message' => 'User successfully registered!', 'id' => $id);
                }
            } else {
                $result = array('status' => 403, 'message' => 'Something went wrong during registration. Please try again later');
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    echo json_encode($result);
    return;
}
