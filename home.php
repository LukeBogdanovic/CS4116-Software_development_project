<?php
session_start();
/* Checking if the user is not logged in
 * If user is not logged in: the user is redirected to the login page and the script exits
 */
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

require_once "includes/utils.php";
require_once "includes/database.php";

$id = $_SESSION['id'];

$potentialUsers = [];
$results = [];
$result = [];
//get current users gender and seeking preferences
//used for validating suggestions 
$getSeeking = "SELECT Seeking FROM profile WHERE userID = ?";
if ($stmt = mysqli_prepare($con, $getSeeking)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $seeking);
        mysqli_stmt_fetch($stmt);
    }
}

//return array of userID's of users who share an interest with logged in user
//array is formatted as id => reason  where reasonm is the interests matching
$commonInterest = "SELECT I2.UserID, A.InterestName FROM interests I1 INNER JOIN interests I2 ON (I2.InterestID = I1.InterestID) JOIN availableinterests A ON (A.InterestID = I1.InterestID) WHERE I1.UserID = ? AND I2.UserID <> ?";
if ($stmt = mysqli_prepare($con, $commonInterest)) {
    mysqli_stmt_bind_param($stmt, "ii", $id, $id);
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
//return users from potentialUSers whos gender matches users preference
$returnSuitableUsers = "SELECT user.UserID, user.Username, user.Firstname, user.Surname, user.DateOfBirth, profile.Description 
                        FROM user LEFT JOIN profile ON user.UserID=profile.UserID 
                        WHERE user.UserID = ? and profile.Gender = ?";


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
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Home</title>
    <script>
        let sessionID = <?php echo $_SESSION['id'] ?>;
    </script>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php
    require_once "includes/navbar.php";
    ?>
    <!-- Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start" id="applyFilters">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title">Edit Filters</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <form action="" method="POST">
                    <div>
                        <label class="labels">Student?</label>
                        <input type="radio" class="custom-control-input" id="studentYes" name="studentVal" value="Yes">
                        <label class="custom-control-label" for="studentYes">Yes</label>
                        <input type="radio" class="custom-control-input" id="studentNo" name="studentVal" value="No">
                        <label class="custom-control-label" for="studentNo">No</label>
                    </div>
                    <hr>
                    <div>
                        <label class="labels">Age Range</label>
                        <input type="text" id="fname" name="ageLower" size="1"><input type="text" id="fname" name="AgeUpper" size="1">
                    </div>
                    <hr>
                    <div>
                        <label class="labels">Drinks?</label>
                        <input type="radio" class="custom-control-input" id="drinksYes" name="drinksVal" value="Yes">
                        <label class="custom-control-label" for="drinksYes">Yes</label>
                        <input type="radio" class="custom-control-input" id="drinksNo" name="drinksVal" value="No">
                        <label class="custom-control-label" for="drinksNo">No</label>
                    </div>
                    <hr>
                    <div>
                        <label class="labels">Smokes?</label>
                        <input type="radio" class="custom-control-input" id="smokesYes" name="smokesVal" value="Yes">
                        <label class="custom-control-label" for="smokesYes">Yes</label>
                        <input type="radio" class="custom-control-input" id="smokesNo" name="smokesVal" value="No">
                        <label class="custom-control-label" for="smokesNo">No</label>
                    </div>
                    <hr>
                    <div class="form-inline">
                        <label class="labels">County</label>
                        <select name="county" id="county" class="form-select">
                            <option></option>
                            <option>Antrim</option>
                            <option>Armagh</option>
                            <option>Carlow</option>
                            <option>Cavan</option>
                            <option>Clare</option>
                            <option>Cork</option>
                            <option>Derry</option>
                            <option>Donegal</option>
                            <option>Down</option>
                            <option>Dublin</option>
                            <option>Fermanagh</option>
                            <option>Galway</option>
                            <option>Kerry</option>
                            <option>Kildare</option>
                            <option>Kilkenny</option>
                            <option>Laois</option>
                            <option>Leitrim</option>
                            <option>Limerick</option>
                            <option>Longford</option>
                            <option>Louth</option>
                            <option>Mayo</option>
                            <option>Meath</option>
                            <option>Monaghan</option>
                            <option>Offaly</option>
                            <option>Roscommon</option>
                            <option>Sligo</option>
                            <option>Tipperary</option>
                            <option>Tyrone</option>
                            <option>Waterford</option>
                            <option>Westmeath</option>
                            <option>Wexford</option>
                            <option>Wicklow</option>
                        </select>
                    </div>
                    <button class="btn submit" type="submit">Apply Filters</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Button to open the offcanvas sidebar -->

    <div class="container-fluid bg-trasparent my-4 p-3">
        <div class="card">
            <div class="card-header">Suggestions</div>
            <div class="row row-cols-4 row-cols-xs-4 row-cols-sm-4 row-cols-lg-4 g-3" style="position: relative;" id="user-cards" data-user-cards-container>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="usercard card h-100 shadow-sm">
                                <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                                <div class="card-body"><input value="" hidden data-userid>
                                    <h5 class="card-title" data-header>Name</h5>
                                    <h5 class="card-subtitle mb-2 text-muted" data-username>Username</h5>
                                    <h6 class="card-subtitle mb-2 text-muted" data-age>Age</h5>
                                        <p class="card-content" data-body>Bio</p>
                                        <div class="text-center my-4">
                                            <a class="btn submit" data-like>Like User</a>
                                            <a class="btn btn-dark" data-profile>View Profile</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="usercard card h-100 shadow-sm">
                                <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                                <div class="card-body"><input value="" hidden data-userid>
                                    <h5 class="card-title" data-header>Name</h5>
                                    <h5 class="card-subtitle mb-2 text-muted" data-username>Username</h5>
                                    <h6 class="card-subtitle mb-2 text-muted" data-age>Age</h5>
                                        <p class="card-content" data-body>Bio</p>
                                        <div class="text-center my-4">
                                            <a class="btn submit" data-like>Like User</a>
                                            <a class="btn btn-dark" data-profile>View Profile</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="usercard card h-100 shadow-sm">
                                <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                                <div class="card-body">
                                    <input value="" hidden data-userid>
                                    <h5 class="card-title" data-header>Name</h5>
                                    <h5 class="card-subtitle mb-2 text-muted" data-username>Username</h5>
                                    <h6 class="card-subtitle mb-2 text-muted" data-age>Age</h5>
                                        <p class="card-content" data-body>Bio</p>
                                        <div class="text-center my-4">
                                            <a class="btn submit" data-like>Like User</a>
                                            <a class="btn btn-dark" data-profile>View Profile</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="usercard card h-100 shadow-sm">
                                <img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                                <div class="card-body">
                                    <input value="" hidden data-userid>
                                    <h5 class="card-title" data-header>Name</h5>
                                    <h5 class="card-subtitle mb-2 text-muted" data-username>Username</h5>
                                    <h6 class="card-subtitle mb-2 text-muted" data-age>Age</h5>
                                        <p class="card-content" data-body>Bio</p>
                                        <div class="text-center my-4">
                                            <a class="btn submit" data-like>Like User</a>
                                            <a class="btn btn-dark" data-profile>View Profile</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn py-1 submit" type="button" data-bs-toggle="offcanvas" data-bs-target="#applyFilters">
                    Edit Filters
                </button>
            </div>
            <!-- <?php
                    foreach ($suggestedUsers as $value) {
                        echo "<div> <p> 'users' </p></div>";
                        echo print_r($value);
                    }
                    ?> -->
        </div>
    </div>
    <template data-user-template>
        <div class="col">
            <div class="usercard card h-100 shadow-sm"><img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture">
                <div class="card-body">
                    <input value="" hidden data-userid>
                    <h5 class="card-title" data-header>Name</h5>
                    <h5 class="card-subtitle mb-2 text-muted" data-username>Username</h5>
                    <h6 class="card-subtitle mb-2 text-muted" data-age>Age</h5>
                        <p class="card-content" data-body>Bio</p>
                        <div class="text-center my-4">
                            <a class="btn submit" data-like>Like User</a>
                            <a class="btn btn-dark" data-profile>View Profile</a>
                        </div>
                </div>
            </div>
        </div>
    </template>
    <?php
    require_once "includes/footer.php"
    ?>
</body>

</html>