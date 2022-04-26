<?php
require "../../includes/utils.php";

if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "login_user":
            login_user($_POST['username'], $_POST['password']);
            break;
    }
}

function login_user($username, $password)
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $stmt = "SELECT UserID, Username, Password, Admin, Banned FROM user WHERE Username = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            mysqli_stmt_bind_param($stmt, "s", $db_username);
            $db_username = $username;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Binding the return values to php variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $admin, $banned);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            if ($banned == 1) {
                                $result = array('status' => 403, 'message' => "User is currently Banned!");
                                echo json_encode($result);
                                return;
                            }
                            session_start();
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $username;
                            if ($admin == 1)
                                $_SESSION['admin'] = true;
                            else
                                $_SESSION['admin'] = false;
                            $result = array('status' => 200, 'message' => "User logged in successfully.");
                        } else {
                            $result = array('status' => 403, 'message' => "Invalid Username or Password");
                            echo json_encode($result);
                            return;
                        }
                    }
                } else {
                    $result = array('message' => "Invalid Username or Password");
                    echo json_encode($result);
                    return;
                }
            } else {
                $result = array('status' => 403, 'message' => "Oops! Something went wrong. Please try again later.");
                echo json_encode($result);
                return;
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
    }
    echo json_encode($result);
    return;
}
