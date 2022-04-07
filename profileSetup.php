<?php
require_once "includes/database.php";
session_start();
/* Checking if the user is not logged in 
* If user is not logged in: the user is redirected to the login page and the script exits 
*/
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$smokerStored = $drinkerStored = $genderStored = $seekingStored =  $descriptionStored = $countyStored = $townStored = $employmentStored = $studentStored = $collegeStored = $degreeStored = NULL;

$id = $_SESSION["id"];

//fetch users profile info, in next satement fetch their interests
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
        ($studentStored==0) ? $studentStored='No': $studentStored = 'Yes';
    }
}

//fetch interests, has issues
$fetchInterests = "SELECT availableinterests.interestName FROM interests JOIN availableinterests ON interests.InterestID = availableinterests.InterestID WHERE interests.UserID = ? ORDER BY availableinterests.InterestID ASC;";
if ($stmt = mysqli_prepare($con, $fetchInterests)) {
    $interestStored = [];
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $interestname);
        if (mysqli_stmt_num_rows($stmt) > 0) {
        //put retrieved Interests in the interestStored array
            while (mysqli_stmt_fetch($stmt)) {
                array_push($interestStored, $interestname);
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Fill in all variables from the form
    $gender = $_POST['gender'];
    $seeking = $_POST['seeking'];
    $smoker = $_POST['smoker'];
    $drinker = $_POST['drinker'];
    $employment = $_POST['employment'];
    $student = $_POST['student'];
    if($student=='Yes'){
        $student = 1;
    } 
    else if ($student=='No'){
        $student=0;
    }
    else{
        $student=NULL;
    }
    $student = $student;
    $college = $_POST['college'];
    $degree = $_POST['degree'];
    $county = $_POST['county'];
    $town = $_POST['town'];
    $description = $_POST['description'];
    //fill the current selection of interests from the form. 
    //compare it to the storedInterests which were retrieve on page load. store the items changed in $storedChanged and the new input in $newInput using array_diff
    $interestsInput = array($_POST['interest1'],$_POST['interest2'],$_POST['interest3'],$_POST['interest4']);
    
    $storedChanged=array_diff($interestStored,$interestsInput);
    $newInput=array_diff($interestsInput,$interestStored);

    //Loop through storedChanged and newInput in order to make the necessary updates inserts and deletions 
    for ($i=0; $i<4; $i++){
        if(empty($storedChanged[$i]) && empty($newInput[$i])){
            //both are empty. Either no change to this interest and no new input OR theres no interest here and nothing entered
        }
        else if(empty($storedChanged[$i])){
            //The stored interest is was empty but the new input is not. sql statement must insert new interest
            $newInterest = "INSERT INTO interests (interests.UserID, interests.InterestID) 
            SELECT ?, availableinterests.InterestID 
            FROM availableinterests 
            WHERE availableinterests.InterestName = ?;"; 
            if($stmt = mysqli_prepare($con, $newInterest)){
                //bind params to statement
                if (mysqli_stmt_bind_param($stmt,"is", $id, $newInput[$i])){
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);
            }
        }
        else if($newInput[$i] ="del"){
            //The stored interest was changed but the input is "del". the stored interest must be deleted
            $DeleteInterest ="DELETE I FROM interests I 
            JOIN availableinterests A
            WHERE I.UserID = ? AND A.InterestName = ?;";
            if($stmt = mysqli_prepare($con, $DeleteInterest)){
                //bind params to statement
                if (mysqli_stmt_bind_param($stmt,"is", $id, $storedChanged[$i])){
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);
            }
        }
        else{
            //if both are set we must update stored interest with new input
            $updateInterest ="UPDATE interests I join availableinterests A 
            ON A.InterestID = I.InterestID
            SET I.InterestID = (SELECT availableinterests.InterestID from availableinterests WHERE availableinterests.InterestName = ? )
            WHERE  I.UserID = ?
            AND A.InterestName = ?;";
            if($stmt = mysqli_prepare($con, $updateInterest)){
                //bind params to statement
                if (mysqli_stmt_bind_param($stmt,"sis", $newInput[$i], $id, $storedChanged[$i])){
                    mysqli_stmt_execute($stmt);
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
    
    $inputs= array(&$gender, &$seeking, &$smoker, &$drinker, &$employment, &$student, &$college, &$degree, &$county, &$town, &$description);
    foreach ($inputs as &$value) {
        if($value == "" || $value == ''){
            $value = null;
        }
    }
    
    //insert data from profile into the database or change values in the database
    $profileInsertUpdate = "INSERT INTO profile (UserID, Smoker, Drinker, Gender, Seeking, Description, County, Town, Employment, Student, College, Degree) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE UserID = VALUES(UserID), Smoker= VALUES(Smoker), Drinker= VALUES(Drinker), Gender= VALUES(Gender), Seeking = VALUES(Seeking), Description = VALUES(Description), County = VALUES(County), Town = VALUES(Town), Employment = VALUES(Employment), Student = VALUES(Student), College = VALUES(College), Degree = VALUES(Degree);";
    if($stmt = mysqli_prepare($con, $profileInsertUpdate)){
        //bind params to statement
        if(mysqli_stmt_bind_param($stmt,"issssssssiss",$id, $smoker, $drinker, $gender, $seeking, $description, $county, $town, $employment , $student , $college , $degree)){
            //Attempt to execute the sql statement
            if (mysqli_stmt_execute($stmt)) {
            }
        }
        mysqli_stmt_close($stmt);
    }          
    mysqli_close($con);        
}

?>
<!DOCTYPE html> 
<html>

<head>
    <title>Profile Setup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="assets/images/logo.PNG">
    <link rel="stylesheet" type="text/css" href="css/utils.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <?php
    require_once "includes/navbar.php";
    ?>

    <main>

        <div class="container mt-5 mb-5">

            <form class="d-flex justify-content-center"action="profileSetup.php" method="POST">
                    
                <div class="col-md-6">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Edit <?php echo $firstnameStored . " " . $surnameStored . "'s"?> Profile</h4>
                        <span class="font-weight-bold"><?php echo $username?></span>
                    </div>

                    <div class="row mt-2">

                        <div class="col-md-6">
                            <label class="labels">Gender</label>
                            <select name="gender" id="gender" class="form-select" value="Female">
                                <option <?php echo ($genderStored == NULL) ?'value="" selected>---Select An Option---':'selected>'.$genderStored ?></option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Non-binary</option>
                                <option>Other</option>
                                <option>Prefer not to say</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="labels">Seeking</label>
                            <select name="seeking" id="seeking" class="form-select">
                                <option <?php echo ($seekingStored == NULL) ?'value="" selected>---Select An Option---':'selected>'.$seekingStored ?></option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>All</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="labels">Smoker</label>
                            <select name="smoker" id="smoker" class="form-select">
                                <option <?php echo ($smokerStored == NULL) ?'value="" selected>---Select An Option---':'selected>'.$smokerStored ?></option>
                                <option>Non Smoker</option>
                                <option>Social Smoker</option>
                                <option>Yes</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="labels">Drinker</label>
                            <select name="drinker" id="drinker"class="form-select">
                                <option <?php echo ($drinkerStored == NULL) ?'value="" selected>---Select An Option---':'selected>'.$drinkerStored ?></option>
                                <option>Never</option>
                                <option>Social Drinker</option>
                                <option>Most Days</option>
                                <option>Constantly</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="labels">Employment</label>
                            <input name="employment" id="employment" type="text" class="form-control" placeholder="enter employment" value="<?php echo $employmentStored?>">
                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-md-12">
                            <label class="labels">Student</label>
                            <select name="student" id="student" class="form-select">
                                <option <?php echo ($studentStored == NULL) ?'value="" selected>---Select An Option---':'selected>'.$studentStored ?></option>
                                <option>No</option>
                                <option>Yes</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="labels">College</label>
                            <input name="college" id="college" type="text" class="form-control" placeholder="enter college" value="<?php echo $collegeStored?>">
                        </div>

                        <div class="col-md-12">
                            <label class="labels">Degree</label>
                            <input name="degree" id="degree" type="text" class="form-control" placeholder="enter degree" value="<?php echo $degreeStored?>">
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="labels">Interests</label>
                            <select name= "interest1" id="interest1"class="form-select">
                                <option <?php echo (isset($interestStored[0])) ?'selected>'.$interestStored[0]:'value="" selected>---Select An Interest---' ?></option>
                                <option value = "del">Remove this interest</option>
                                <option>Animals</option>
                                <option>Art</option>
                                <option>Baking</option>
                                <option>Board games</option>
                                <option>Carpentry</option>
                                <option>Computers</option>
                                <option>Cooking</option>
                                <option>DIY</option>
                                <option>Drinking</option>
                                <option>Fitness</option>
                                <option>Food</option>
                                <option>GAA</option>
                                <option>Gardening</option>
                                <option>Golf</option>
                                <option>Movies</option>
                                <option>Music</option>
                                <option>Reading</option>
                                <option>Role Playing Games</option>
                                <option>Rugby</option>
                                <option>Soccer</option>
                                <option>TV</option>
                                <option>Travelling</option>
                                <option>Video Games</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Interests</label>
                            <select name= "interest2" id="interest2"class="form-select">
                                <option <?php echo (isset($interestStored[1])) ?'selected>'.$interestStored[1]:'value="" selected>---Select An Interest---' ?></option>
                                <option value = "del">Remove this interest</option>
                                <option>Animals</option>
                                <option>Art</option>
                                <option>Baking</option>
                                <option>Board games</option>
                                <option>Carpentry</option>
                                <option>Computers</option>
                                <option>Cooking</option>
                                <option>DIY</option>
                                <option>Drinking</option>
                                <option>Fitness</option>
                                <option>Food</option>
                                <option>GAA</option>
                                <option>Gardening</option>
                                <option>Golf</option>
                                <option>Movies</option>
                                <option>Music</option>
                                <option>Reading</option>
                                <option>Role Playing Games</option>
                                <option>Rugby</option>
                                <option>Soccer</option>
                                <option>TV</option>
                                <option>Travelling</option>
                                <option>Video Games</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Interests</label>
                            <select name= "interest3" id="interest3"class="form-select">
                                <option <?php echo (isset($interestStored[2])) ?'selected>'.$interestStored[2]:'value="" selected>---Select An Interest---'?></option>
                                <option value = "del">Remove this interest</option>
                                <option>Animals</option>
                                <option>Art</option>
                                <option>Baking</option>
                                <option>Board games</option>
                                <option>Carpentry</option>
                                <option>Computers</option>
                                <option>Cooking</option>
                                <option>DIY</option>
                                <option>Drinking</option>
                                <option>Fitness</option>
                                <option>Food</option>
                                <option>GAA</option>
                                <option>Gardening</option>
                                <option>Golf</option>
                                <option>Movies</option>
                                <option>Music</option>
                                <option>Reading</option>
                                <option>Role Playing Games</option>
                                <option>Rugby</option>
                                <option>Soccer</option>
                                <option>TV</option>
                                <option>Travelling</option>
                                <option>Video Games</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Interests</label>
                            <select name= "interest4" id="interest4"class="form-select">
                                <option <?php echo (isset($interestStored[3])) ?'selected>'.$interestStored[3]:'value="" selected>---Select An Interest---' ?></option>
                                <option value = "del">Remove this interest</option>
                                <option>Animals</option>
                                <option>Art</option>
                                <option>Baking</option>
                                <option>Board games</option>
                                <option>Carpentry</option>
                                <option>Computers</option>
                                <option>Cooking</option>
                                <option>DIY</option>
                                <option>Drinking</option>
                                <option>Fitness</option>
                                <option>Food</option>
                                <option>GAA</option>
                                <option>Gardening</option>
                                <option>Golf</option>
                                <option>Movies</option>
                                <option>Music</option>
                                <option>Reading</option>
                                <option>Role Playing Games</option>
                                <option>Rugby</option>
                                <option>Soccer</option>
                                <option>TV</option>
                                <option>Travelling</option>
                                <option>Video Games</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="col-md-6">
                            <label class="labels">County</label>
                            <select name="county" id="county"class="form-select">
                                <option <?php echo ($countyStored == NULL) ?'value="" selected>---Select An Option---':'selected>'.$countyStored ?></option>
                                <option>Antrim</option>
                                <option>Armagh</option>
                                <option >Carlow</option>
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
                        
                        <div class="col-md-6">
                            <label class="labels">Town</label>
                            <input name= "town" id="town" type="text" class="form-control" placeholder="enter town" value="<?php echo $townStored?>">
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="labels">Bio</label>
                            <textarea name="description" id="description" maxlength="512" type="text" class="form-control" rows="5" placeholder="enter bio"><?php echo $descriptionStored?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="row mt-3">
                            <label class="labels">Add Pictures</label>
                            <input type="file" id="myFile" name="filename" multiple accept=".png,.jpg,.jpeg">
                            </form>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary profile-button" type="submit">Save Profile</button>
                    </div>

                </div>

            </form>

        </div>

    </main>

    <?php
    require_once "includes/footer.php";
    ?>

</body>

</html>