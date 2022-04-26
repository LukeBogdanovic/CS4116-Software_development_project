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
        case "get_filtered_users":
            get_filtered_users();
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


function get_filtered_users()
{
    require "../../includes/database.php";
    $search = $_POST['search'];
    $stmt = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Description FROM user LEFT JOIN profile ON user.UserID=profile.UserID WHERE CONCAT(user.firstname, ' ',user.Surname, '¾', user.Username) LIKE '%$search%'";
    if (!empty($_POST["student"]))
        $filterStudent = $_POST["student"];
    if (!empty($_POST["gender"]))
        $filterGender = $_POST["gender"];
    if (!empty($_POST["smoker"]))
        $filterSmoker = $_POST["smoker"];        // "Yes" returns smokers and social smokers, "No" returns never smokers
    if (!empty($_POST["drinker"]))
        $filterDrinker = $_POST["drinker"];       // "No" filters out non-drinkers, anything else returns all types of drinkers except non-drinkers
    if (!empty($_POST["county"]))
        $filterCounty = $_POST["county"];       // County name "Tipperary"
    if (!empty($_POST["ageLower"]))
        $lowerAge = $_POST["ageLower"];         // lower age bracket
    if (!empty($_POST["ageUpper"]))
        $upperAge = $_POST["ageUpper"];             // upper age boundary
    //check if empty before applying filter
    if (!empty($filterGender))
        $stmt = apply_gender_filter($stmt, $filterGender);
    if (!empty($filterStudent))
        $stmt = applyStudentFilter($stmt, $filterStudent);
    if (!empty($filterCounty))
        $stmt = applyCountyFilter($stmt, $filterCounty);
    if (!empty($filterSmoker))
        $stmt = applySmokerFilter($stmt, $filterSmoker);
    if (!empty($filterDrinker))
        $stmt = applyDrinkerFilter($stmt, $filterDrinker);
    $filteredUsers = [];
    if ($stmt = mysqli_prepare($con, $stmt)) {
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
                    $user = array('userID' => $userID, 'username' => $username, 'firstname' => $firstname, 'surname' => $surname, 'age' => $age, 'description' => $description);
                    if (!empty($upperAge) && !empty($lowerAge)) {
                        if (checkAgeRange($user, $lowerAge, $upperAge)) {
                            array_push($filteredUsers, $user);
                        }
                    } else {
                        array_push($filteredUsers, $user);
                    }
                }
                $result['filtered_users'] = $filteredUsers;
            }
        }
    }
    if (empty($result['filtered_users'])) {
        $result = array('status' => 403, 'message' => "No Users found matching the current filters");
    }
    echo json_encode($result);
    return;
}

//Filter by student status
function applyStudentFilter($stmt, $studentYN)
{
    if ($studentYN == "Yes")
        $studentYN = 1;
    else if ($studentYN == "No")
        $studentYN = 0;

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
    if ($smokesYN == "Yes")
        return $stmt = "$stmt AND profile.Smoker <> 'Never' ";
    else
        return $stmt = "$stmt AND profile.Smoker = 'Never';";
}

//filters by drinker status, No for filtering out drinkers, anything else for filtering out abstainers
function applyDrinkerFilter($stmt, $drinksYN)
{
    if ($drinksYN == "No")
        return $stmt = "$stmt AND profile.Drinker = 'No';";
    else
        return $stmt = "$stmt AND profile.Drinker <> 'No' ";
}

//check if the current $user matches the age range or not.  
//add them to $suggestedUsers if they are in the age range.
function checkAgeRange($user, $lowerAge, $upperAge)
{
    if ($user['age'] <= $upperAge && $user['age'] >= $lowerAge)
        return true;
    else
        return null;
}

function apply_gender_filter($stmt, $gender)
{
    if ($gender == "Male")
        return $stmt = "$stmt AND profile.Gender = 'Male'";
    else if ($gender == "Female")
        return $stmt = "$stmt AND profile.Gender = 'Female'";
    else if ($gender == "Non-Binary")
        return $stmt = "$stmt AND profile.Gender = 'Non-Binary'";
    else if ($gender == "Other")
        return $stmt = "$stmt AND profile.Gender = 'Other'";
    else if ($gender == "Prefer not to say")
        return $stmt = "$stmt AND profile.Gender = 'Prefer not to say'";
}
