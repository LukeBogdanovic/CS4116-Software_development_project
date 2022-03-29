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
$fetchinfo = "SELECT Smoker, Drinker, Gender, Seeking, Description, County, Town, Employment, Student, College, Degree FROM profile WHERE userID = ?";
if ($stmt = mysqli_prepare($con, $fetchinfo)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        //bind results of search to user variable 
        mysqli_stmt_bind_result($stmt, $smokerStored, $drinkerStored, $genderStored, $seekingStored, $descriptionStored, $countyStored, $townStored, $employmentStored, $studentStored, $collegeStored, $degreeStored);
        mysqli_stmt_fetch($stmt);
        ($studentStored==0) ? $studentStored='No': $studentStored = 'Yes';
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $gender = $seeking = $smoker = $drinker = $employment = $student = $college = $degree = $county = $town = $description =NULL;
    // Fill in all variables from the form
    $gender = mysqli_real_escape_string($con, trim($_POST['gender']));
    $seeking = mysqli_real_escape_string($con, trim($_POST['seeking']));
    $smoker = mysqli_real_escape_string($con, trim($_POST['smoker']));
    $drinker = mysqli_real_escape_string($con, trim($_POST['drinker']));
    $employment = mysqli_real_escape_string($con, trim($_POST['employment']));
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
    $student = mysqli_real_escape_string($con, trim($student));
    $college = mysqli_real_escape_string($con, trim($_POST['college']));
    $degree = mysqli_real_escape_string($con, trim($_POST['degree']));
    $county = mysqli_real_escape_string($con, trim($_POST['county']));
    $town = mysqli_real_escape_string($con, trim($_POST['town']));
    $description = mysqli_real_escape_string($con, trim($_POST['description']));
    
    $inputs= array(&$gender, &$seeking, &$smoker, &$drinker, &$employment, &$student, &$college, &$degree, &$county, &$town, &$description);
    foreach ($inputs as &$value) {
        if($value == "" || $value == ''){
            $value = null;
        }
    }

    //insert data from profile changes into database
    if($stmt = $con -> prepare("INSERT INTO profile (UserID, Smoker, Drinker, Gender, Seeking, Description, County, Town, Employment, Student, College, Degree) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE UserID = VALUES(UserID), Smoker= VALUES(Smoker), Drinker= VALUES(Drinker), Gender= VALUES(Gender), Seeking = VALUES(Seeking), Description = VALUES(Description), County = VALUES(County), Town = VALUES(Town), Employment = VALUES(Employment), Student = VALUES(Student), College = VALUES(College), Degree = VALUES(Degree);")){
    //if($stmt = $con -> prepare("INSERT INTO profile (UserID, Smoker, Drinker, Gender, Seeking, Description, County, Town, Employment, Student, College, Degree) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE UserID = VALUES(UserID), Smoker= VALUES(Smoker), Drinker= VALUES(Drinker), Gender= VALUES(Gender), Seeking = VALUES(Seeking), Description = VALUES(Description), County = VALUES(County), Town = VALUES(Town), Employment = VALUES(Employment), Student = VALUES(Student), College = VALUES(College), Degree = VALUES(Degree);")){
        //bind params to statement
        if(mysqli_stmt_bind_param($stmt,"issssssssiss",$id, $smoker, $drinker, $gender, $seeking, $description, $county, $town, $employment , $student , $college , $degree)){
            //Attempt to execute the sql statement
            if (mysqli_stmt_execute($stmt)) {
            }
        }
    }
    mysqli_stmt_close($stmt);          
    mysqli_close($con);        
        
    
    
    
    //INSERT INTO profile (UserID, Smoker, Drinker, Gender, Seeking, Description, County, Town, Employment, Student, College, Degree)
    //VALUES (10, "Social Smoker", "Social Drinker",  "Male", "Female", "compooter", "Tipperary", "cashel", "compooter" , "1" , "UL", "")
    //ON DUPLICATE KEY UPDATE Smoker= "Social Smoker", Drinker= "Social Drinker",  Gender= "Male", Seeking = "Female",Description = "compooter", County = "Tipperary", Town = "cashel", Employment = "compooter", Student = "1", College = "UL", Degree = "Computer Science";

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
                        <h4 class="text-right">Edit Profile</h4>
                        <span class="font-weight-bold">username</span>
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

                        <div class="d-flex justify-content-center">
                            <div class="row mt-3">
                            <label class="labels">Add Pictures</label>
                            <input type="file" id="myFile" name="filename">
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