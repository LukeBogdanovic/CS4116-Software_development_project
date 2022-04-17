<?php
require_once "../../includes/utils.php";

if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "get_seeking":
            get_seeking();
            break;
        case "get_suggested_users":
            get_suggested_users();
            break;
    }
}


function get_seeking()
{
    require "../../includes/database.php";
    $getSeeking = "SELECT Seeking FROM profile WHERE userID = ?";
    if ($stmt = mysqli_prepare($con, $getSeeking)) {
        mysqli_stmt_bind_param($stmt, "i", $_POST['id']);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $seeking);
            mysqli_stmt_fetch($stmt);
            if (!empty($seeking)) {
                $result = array('status' => 200, 'message' => "Seeked gender for User found", 'seeking' => $seeking);
            } else {
                $result = array('status' => 403, 'message' => "Seeked gender not found for User");
            }
        }
    }
    echo json_encode($result);
    return;
}


function get_suggested_users()
{
    require "../../includes/database.php";
    $potentialUsers = [];
    $seeking = $_POST['seeking'];
    $commonInterest = "SELECT I2.UserID, A.InterestName FROM interests I1 INNER JOIN interests I2 ON (I2.InterestID = I1.InterestID) JOIN availableinterests A ON (A.InterestID = I1.InterestID) WHERE I1.UserID = ? AND I2.UserID <> ?";
    if ($stmt = mysqli_prepare($con, $commonInterest)) {
        mysqli_stmt_bind_param($stmt, "ii", $_POST['id'], $_POST['id']);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            //bind results of search to user variable 
            mysqli_stmt_bind_result($stmt, $userID, $interestShared);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $result = array('status' => 200, 'message' => 'Users found with similar interests');
                // Put all retrieved UserIDs into results array
                while (mysqli_stmt_fetch($stmt)) {
                    $usersInterests = [];
                    array_push($usersInterests, $interestShared);
                    if (empty($potentialUsers[$userID])) {
                        $potentialUsers[$userID] = $usersInterests;
                    } else {
                        array_push($potentialUsers[$userID], $interestShared);
                    }
                }
            }
        }
    }
    $returnSuitableUsers = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Description FROM user LEFT JOIN profile ON user.UserID=profile.UserID WHERE user.UserID = ? and profile.Gender = ?";
    //fill these variable with a POST, 
    if (!empty($_POST["studentVal"])) {
        $filterStudent = $_POST["studentVal"];      // "Yes" or "No"
    }
    if (!empty($_POST["smokesVal"])) {
        $filterSmoker = $_POST["smokesVal"];        // "Yes" returns smokers and social smokers, "No" returns never smokers
    }
    if (!empty($_POST["drinksVal"])) {
        $filterDrinker = $_POST["drinksVal"];       // "No" filters out non-drinkers, anything else returns all types of drinkers except non-drinkers
    }
    if (!empty($_POST["county"])) {
        $filterCounty = $_POST["county"];       // County name "Tipperary"
    }
    if (!empty($_POST["ageLower"])) {
        $lowerAge = $_POST["ageLower"];         // lower age bracket
    }
    if (!empty($_POST["AgeUpper"])) {
        $upperAge = $_POST["AgeUpper"];             // upper age boundary
    }
    //check if empty before applying filter
    if ($seeking == "All") {
        $returnSuitableUsers = applyAllSeekingFilter();
    }
    if (!empty($filterStudent)) {
        $returnSuitableUsers = applyStudentFilter($returnSuitableUsers, $filterStudent);
    }
    if (!empty($filterCounty)) {
        $returnSuitableUsers = applyCountyFilter($returnSuitableUsers, $filterCounty);
    }
    if (!empty($filterSmoker)) {
        $returnSuitableUsers = applySmokerFilter($returnSuitableUsers, $filterSmoker);
    }
    if (!empty($filterDrinker)) {
        $returnSuitableUsers = applyDrinkerFilter($returnSuitableUsers, $filterDrinker);
    }
    $suggestedUsers = [];
    foreach ($potentialUsers as $key => $value) {
        if ($stmt = mysqli_prepare($con, $returnSuitableUsers)) {
            if ($seeking == "All")
                mysqli_stmt_bind_param($stmt, "i", $key);
            else
                mysqli_stmt_bind_param($stmt, "is", $key, $seeking);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $userID, $username, $firstname, $surname, $dob, $description);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $result = array('status' => 200, 'message' => 'Users found matching search criteria');
                    // Put all retrieved UserIDs into results array
                    while (mysqli_stmt_fetch($stmt)) {
                        //Create profile description string if profile descritpion returns null
                        if (is_null($description)) {
                            $description = "$firstname $surname has not created their profile yet";
                        }
                        $age = get_age($dob);
                        $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'description' => $description, 'interests in common' => $value);
                        if (!empty($upperAge) && !empty($lowerAge)) {
                            if (checkAgeRange($user, $lowerAge, $upperAge)) {
                                array_push($suggestedUsers, $user);
                            }
                        } else {
                            array_push($suggestedUsers, $user);
                        }
                    }
                    $result['suggested_users'] = $suggestedUsers;
                } else {
                    $result = array('status' => 403, 'message' => "No users found matching the search criteria");
                }
            }
        }
    }
    echo json_encode($result);
    return;
}

//Filter by student status
function applyStudentFilter($stmt, $studentYN)
{
    if ($studentYN == "Yes") {
        $studentYN = 1;
    } else if ($studentYN == "No") {
        $studentYN = 0;
    }
    return $stmt = "$stmt AND profile.Student = $studentYN";
}

//function for applying a county filter
function applyCountyFilter($stmt, $county)
{
    return $stmt = "$stmt AND profile.County = '$county'";
}

//function for users who either smoke or never smoke
//smokes = Yes /= No 
function applySmokerFilter($stmt, $smokesYN)
{
    if ($smokesYN == "Yes") {
        return $stmt = "$stmt AND profile.Smoker <> 'Never' ";
    } else {
        return $stmt = "$stmt AND profile.Smoker = 'Never';";
    }
}

//filters by drinker status, No for filtering out drinkers, anything else for filtering out abstainers
function applyDrinkerFilter($stmt, $drinksYN)
{
    if ($drinksYN == "No") {
        return $stmt = "$stmt AND profile.Drinker = 'No';";
    } else {
        return $stmt = "$stmt AND profile.Drinker <> 'No' ";
    }
}

//check if the current $user matches the age range or not.  
//add them to $suggestedUsers if they are in the age range.
function checkAgeRange($user, $lowerAge, $upperAge)
{
    if ($user['age'] <= $upperAge && $user['age'] >= $lowerAge) {
        return true;
    } else {
        return null;
    }
}

function applyAllSeekingFilter()
{
    return "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Description FROM user LEFT JOIN profile ON user.UserID=profile.UserID WHERE user.UserID = ?;";
}
