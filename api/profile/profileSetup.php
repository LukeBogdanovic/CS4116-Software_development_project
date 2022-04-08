<?php
if (isset($_POST['function'])) {
    switch ($_POST['function']) {
        case "fetch_profile":
            fetch_profile($_POST['id']);
            break;
        case "fetch_interests":
            fetch_interests($_POST['id']);
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
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $fetchProfile = "SELECT Smoker, Drinker, Gender, Seeking, Description, County, Town, Employment, Student, College, Degree FROM profile WHERE userID = ?";
        if ($stmt = mysqli_prepare($con, $fetchProfile)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                //bind results of search to user variable 
                mysqli_stmt_bind_result($stmt, $smokerStored, $drinkerStored, $genderStored, $seekingStored, $descriptionStored, $countyStored, $townStored, $employmentStored, $studentStored, $collegeStored, $degreeStored);
                mysqli_stmt_fetch($stmt);
                ($studentStored == 0) ? $studentStored = 'No' : $studentStored = 'Yes';
                $user = array('smoker' => $smokerStored, 'drinker' => $drinkerStored, 'gender' => $genderStored, 'seeking' => $seekingStored, 'description' => $descriptionStored, 'county' => $countyStored, 'town' => $townStored, 'employment' => $employmentStored, 'student' => $studentStored, 'college' => $collegeStored, 'degree' => $degreeStored);
                $result = array('status' => 200, 'message' => 'User profile details found.');
                array_push($result, $user);
                echo json_encode($result);
                return;
            }
        }
    }
    $result = array('status' => 403, 'message' => 'Unable to retireve User Profile information.');
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
