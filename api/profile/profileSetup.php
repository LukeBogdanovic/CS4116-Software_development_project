<?php
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "fetch_profile":
            fetch_profile($_POST['id']);
            break;
        case "fetch_interests":
            fetch_interests($_POST['id']);
            break;
        case "update_profile":
            update_profile();
            break;
        case "fetch_user_data":
            fetch_user_data();
            break;
        case "fetch_user_security":
            fetch_user_security();
            break;
        case "update_security":
            update_security();
            break;
    }
}

/**
 * Fetches all details related to the profile of the specified user ID
 * @param string $id
 */
function fetch_profile($id)
{
    require "../../includes/database.php";
    $PhotoID = "";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $stmt = "SELECT photos.PhotoID FROM photos WHERE photos.UserID = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_bind_param($stmt, "i", $id)) {
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $PhotoID);
                    mysqli_stmt_fetch($stmt);
                }
            }
        }
        $fetchProfile = "SELECT user.Username, user.Firstname, user.Surname, user.DateOfBirth,
        profile.Smoker, profile.Drinker, profile.Gender, profile.Seeking, profile.Description, profile.County, profile.Town, profile.Employment, profile.Student, profile.College, profile.Degree 
        FROM profile JOIN user 
        ON user.UserID = profile.UserID 
        WHERE user.userID = ?";
        if ($stmt = mysqli_prepare($con, $fetchProfile)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                //bind results of search to user variable 
                mysqli_stmt_bind_result($stmt, $username, $firstnameStored, $surnameStored, $dobStored, $smokerStored, $drinkerStored, $genderStored, $seekingStored, $descriptionStored, $countyStored, $townStored, $employmentStored, $studentStored, $collegeStored, $degreeStored);
                mysqli_stmt_fetch($stmt);
                ($studentStored == 0) ? $studentStored = 'No' : $studentStored = 'Yes';
                $user = array('username' => $username, 'firstname' => $firstnameStored, 'surname' => $surnameStored, 'dob' => $dobStored, 'smoker' => $smokerStored, 'drinker' => $drinkerStored, 'gender' => $genderStored, 'seeking' => $seekingStored, 'description' => $descriptionStored, 'county' => $countyStored, 'town' => $townStored, 'employment' => $employmentStored, 'student' => $studentStored, 'college' => $collegeStored, 'degree' => $degreeStored, 'photo' => $PhotoID);
                $result = array('status' => 200, 'message' => 'User profile details found.');
                array_push($result, $user);
                echo json_encode($result);
                return;
            }
        }
    }
    $result = array('status' => 403, 'message' => 'Unable to retrieve User Profile information.');
    echo json_encode($result);
    return;
}

/**
 * Fetches all interests related to the profile of the specified User ID
 * @param string $id
 */
function fetch_interests($id)
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $fetchInterests = "SELECT availableinterests.interestName FROM interests JOIN availableinterests ON interests.InterestID = availableinterests.InterestID WHERE interests.UserID = ? ORDER BY availableinterests.InterestID ASC;";
        if ($stmt = mysqli_prepare($con, $fetchInterests)) {
            $interestStored = [];
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $interestname);
                $count = 1;
                while (mysqli_stmt_fetch($stmt)) {
                    $interestStored["interest{$count}"] = $interestname;
                    $count++;
                }
                while (count($interestStored) < 4) {
                    $length = count($interestStored);
                    $length++;
                    $interestStored["interest{$length}"] = null;
                }
                $result = array('status' => 200, 'message' => 'User Interests retrieved.');
                array_push($result, $interestStored);
                echo json_encode($result);
                return;
            }
        }
    }
    $result = array('status' => 403, 'message' => 'User Interests unable to be retrieved.');
    echo json_encode($result);
    return;
}

/**
 * 
 */
function update_profile()
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Fill in all variables from the form
        $id = $_POST['id'];
        $interestStored = $_POST['interestStored'];
        $interestStored['0'] = $interestStored['interest1'];
        $interestStored['1'] = $interestStored['interest2'];
        $interestStored['2'] = $interestStored['interest3'];
        $interestStored['3'] = $interestStored['interest4'];
        unset($interestStored['interest1']);
        unset($interestStored['interest2']);
        unset($interestStored['interest3']);
        unset($interestStored['interest4']);
        $gender = $_POST['gender'];
        $seeking = $_POST['seeking'];
        $smoker = $_POST['smoker'];
        $drinker = $_POST['drinker'];
        $employment = $_POST['employment'];
        $student = $_POST['student'];
        if ($student == 'Yes') {
            $student = 1;
            $college = $_POST['college'];
            $degree = $_POST['degree'];
        } else if ($student == 'No') {
            $student = 0;
            $college = "";
            $degree = "";
        } else $student = NULL;
        $county = $_POST['county'];
        $town = $_POST['town'];
        $description = $_POST['description'];
        //fill the current selection of interests from the form. 
        //compare it to the storedInterests which were retrieve on page load. store the items changed in $storedChanged and the new input in $newInput using array_diff
        $interestsInput = array($_POST['0'], $_POST['1'], $_POST['2'], $_POST['3']);
        $storedChanged = array_diff($interestStored, $interestsInput);
        $newInput = array_diff($interestsInput, $interestStored);
        //Loop through storedChanged and newInput in order to make the necessary updates inserts and deletions 
        for ($i = 0; $i < 4; $i++) {
            if (empty($storedChanged[$i]) && empty($newInput[$i])) {
                //both are empty. Either no change to this interest and no new input OR theres no interest here and nothing entered
            } else if (empty($storedChanged[$i])) {
                //The stored interest is was empty but the new input is not. sql statement must insert new interest
                $newInterest = "INSERT INTO interests (interests.UserID, interests.InterestID) 
                SELECT ?, availableinterests.InterestID 
                FROM availableinterests 
                WHERE availableinterests.InterestName = ?;";
                if ($stmt = mysqli_prepare($con, $newInterest)) {
                    //bind params to statement
                    if (mysqli_stmt_bind_param($stmt, "is", $id, $newInput[$i])) {
                        mysqli_stmt_execute($stmt);
                    }
                    mysqli_stmt_close($stmt);
                }
            } else if ($newInput[$i] == "del") {
                //The stored interest was changed but the input is "del". the stored interest must be deleted
                $DeleteInterest = "DELETE I FROM interests I JOIN availableinterests A ON A.InterestID = I.InterestID WHERE I.UserID = ? AND A.InterestName = ?";
                if ($stmt = mysqli_prepare($con, $DeleteInterest)) {
                    //bind params to statement
                    if (mysqli_stmt_bind_param($stmt, "is", $id, $storedChanged[$i])) {
                        mysqli_stmt_execute($stmt);
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                //if both are set we must update stored interest with new input
                $updateInterest = "UPDATE interests I join availableinterests A 
                ON A.InterestID = I.InterestID
                SET I.InterestID = (SELECT availableinterests.InterestID from availableinterests WHERE availableinterests.InterestName = ? )
                WHERE  I.UserID = ?
                AND A.InterestName = ?;";
                if ($stmt = mysqli_prepare($con, $updateInterest)) {
                    //bind params to statement
                    if (mysqli_stmt_bind_param($stmt, "sis", $newInput[$i], $id, $storedChanged[$i])) {
                        mysqli_stmt_execute($stmt);
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
        //set all empty variables to null except for student as student defaults to 0 always. there is no opportunity for it to be empty
        $inputs = array(&$gender, &$seeking, &$smoker, &$drinker, &$employment, &$college, &$degree, &$county, &$town, &$description);
        foreach ($inputs as &$value) {
            if ($value == "" || $value == '') {
                $value = null;
            }
        }
        //insert data from profile into the database or change values in the database
        $profileInsertUpdate = "INSERT INTO profile (UserID, Smoker, Drinker, Gender, Seeking, Description, County, Town, Employment, Student, College, Degree) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE UserID = VALUES(UserID), Smoker= VALUES(Smoker), Drinker= VALUES(Drinker), Gender= VALUES(Gender), Seeking = VALUES(Seeking), Description = VALUES(Description), County = VALUES(County), Town = VALUES(Town), Employment = VALUES(Employment), Student = VALUES(Student), College = VALUES(College), Degree = VALUES(Degree);";
        if ($stmt = mysqli_prepare($con, $profileInsertUpdate)) {
            //bind params to statement
            if (mysqli_stmt_bind_param($stmt, "issssssssiss", $id, $smoker, $drinker, $gender, $seeking, $description, $county, $town, $employment, $student, $college, $degree)) {
                //Attempt to execute the sql statement
                if (mysqli_stmt_execute($stmt)) {
                    $result = array('status' => 200, 'message' => "User's profile has been updated succesfully");
                } else {
                    $result = array('status' => 403, 'message' => "Unable to update the User's profile");
                    echo json_encode($result);
                    return;
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        echo json_encode($result);
        return;
    }
}

function fetch_user_data()
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $stmt = "SELECT user.Username, user.firstname, user.surname FROM user WHERE user.UserID = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_bind_param($stmt, "i", $_POST['id'])) {
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $username, $firstname, $surname);
                    $result = array('status' => 200, 'message' => "User's data has been retrieved");
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_fetch($stmt);
                        array_push($result, array('username' => $username, 'firstname' => $firstname, 'surname' => $surname));
                    }
                } else {
                    $result = array('status' => 403, 'message' => "User's data unable to be retrieved. Try again later.");
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($con);
        echo json_encode($result);
        return;
    }
}

function fetch_user_security()
{
    require "../../includes/database.php";
    $results = [];
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $stmt = "SELECT securityqa.SecurityQuestion, securityqa.SecurityAnswer FROM securityqa WHERE securityqa.UserID = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_bind_param($stmt, "i", $_POST['id'])) {
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $securityQuestion, $securityAnswer);
                    $result = array('status' => 200, 'message' => "User's Security details retrieved");
                    $count = 1;
                    while (mysqli_stmt_fetch($stmt)) {
                        $results["securityQuestion{$count}"] = $securityQuestion;
                        $results["securityAnswer{$count}"] = $securityAnswer;
                        $count++;
                    }
                    $count = 1;
                    while (count($results) < 4) {
                        $results["securityQuestion{$count}"] = null;
                        $results["securityAnswer{$count}"] = null;
                        $count++;
                    }
                    array_push($result, $results);
                } else {
                    $result = array('status' => 403, 'message' => "User's Security Details unable to be retrieved");
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($con);
    echo json_encode($result);
    return $results;
}

function update_security()
{
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $stmt = "DELETE FROM securityqa WHERE UserID = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_bind_param($stmt, "i", $_POST['id'])) {
                if (mysqli_stmt_execute($stmt)) {
                    $result = array('status' => 200, 'message' => "User Security Questions Deleted succesfully");
                } else {
                    $result = array('status' => 403, 'message' => "User Security Questions Unable to be Deleted");
                }
            }
            mysqli_stmt_close($stmt);
        }
        $count = 1;
        while ($count <= 2) {
            $stmt = "INSERT INTO securityqa (UserID, SecurityQuestion, SecurityAnswer) VALUES (?,?,?);";
            if ($stmt = mysqli_prepare($con, $stmt)) {
                if (mysqli_stmt_bind_param($stmt, "iss", $_POST['id'], $_POST["securityQuestion{$count}"], $_POST["securityAnswer{$count}"])) {
                    if (mysqli_stmt_execute($stmt)) {
                        $result = array('status' => 200, 'message' => "Updated User's Security Details successfully");
                    } else {
                        $result = array('status' => 403, 'message' => "Unable to Update User's Security Details. Please try again later.");
                    }
                }
            }
            mysqli_stmt_close($stmt);
            $count++;
        }
        mysqli_close($con);
    }
    echo json_encode($result);
    return;
}
