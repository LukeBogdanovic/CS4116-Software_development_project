<?php
session_start();
/* Checking if the user is not logged in 
* If user is not logged in: the user is redirected to the login page and the script exits 
*/
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}
if ($_GET['profile'] != $_SESSION['id'] && !$_SESSION['admin']) {
    header("location: home.php");
    exit;
}
//messy but I need a way to access whos profile it is in upload.php
if(isset($_GET['profile'])){
    $_SESSION['profile']=$_GET['profile'];
}else{
    $_SESSION['profile']=$_SESSION['id'];
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="js/profileSetup.js" defer></script>
</head>

<body>
    <?php
    require_once "includes/navbar.php";
    ?>
    <main id="main">
        <div class="d-flex vh-100 justify-content-center" id="spinner">
            <div class="spinner-border" role="status"></div>
        </div>
        <section>
            <div class="container mt-5 mb-5" id="hide" hidden>
                <form class="d-flex justify-content-center" id="form" method="POST">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="userFirst" class="text-right"></h4>
                            <span id="username" class="font-weight-bold"></span>
                            <input id="userID" value="<?php echo (isset($_GET['profile'])) ? $_GET['profile'] : $_SESSION['id'] ?>" hidden>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">Gender</label>
                                <select name="gender" id="gender" class="form-select">
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
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>All</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Smoker</label>
                                <select name="smoker" id="smoker" class="form-select">
                                    <option>Non Smoker</option>
                                    <option>Social Smoker</option>
                                    <option value="Smoker">Yes</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Drinker</label>
                                <select name="drinker" id="drinker" class="form-select">
                                    <option value="No">Never</option>
                                    <option>Social Drinker</option>
                                    <option>Most Days</option>
                                    <option>Constantly</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Employment</label>
                                <input name="employment" id="employment" type="text" class="form-control" placeholder="enter employment" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Student</label>
                                <select name="student" id="student" class="form-select">
                                    <option>No</option>
                                    <option>Yes</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">College</label>
                                <input name="college" id="college" type="text" class="form-control" placeholder="enter college" value="">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Degree</label>
                                <input name="degree" id="degree" type="text" class="form-control" placeholder="enter degree" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="labels">Interests</label>
                                <select name="interest1" id="interest1" class="form-select">
                                    <option value="del">Remove this interest</option>
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
                                <select name="interest2" id="interest2" class="form-select">
                                    <option value="del">Remove this interest</option>
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
                                <select name="interest3" id="interest3" class="form-select">
                                    <option value="del">Remove this interest</option>
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
                                <select name="interest4" id="interest4" class="form-select">
                                    <option value="del">Remove this interest</option>
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
                                <select name="county" id="county" class="form-select">
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
                            <div class="col-md-6">
                                <label class="labels">Town</label>
                                <input name="town" id="town" type="text" class="form-control" placeholder="enter town" value="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Bio</label>
                                <textarea name="description" id="description" maxlength="512" type="text" class="form-control" rows="5" placeholder="enter bio"></textarea>
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <button class="btn btn-primary profile-button" onclick="updateProfile(event);" type="submit">Save Profile</button>
                        </div>
                </form>
            </div>
        </section>
        <Section>
            <form action="api/profile/upload.php" method="POST" enctype="multipart/form-data">
                <div class="d-flex justify-content-center">
                    <div class="row mt-3">
                        <label class="labels">Add Profile Picture (only accepts .png,.jpg,.jpeg)</label>
                        <input type="file" id="photo" name="photo" accept=".png,.jpg,.jpeg">
                    </div>
                </div>
                <div class="mt-5 text-center">
                    <button class="btn btn-primary profile-button" name="submit" type="submit">Save Photo</button>
                </div>
            </form>
        </Section>
        <section>
            <div class="container mt-5 mb-5" id="hide2" hidden>
                <hr />
                <form class="d-flex justify-content-center" id="form2" method="POST">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="userFirst" class="text-right"></h4>
                            <span id="username" class="font-weight-bold"></span>
                        </div>
                        <div>
                            <div class="col-md-12">
                                <label class="labels">Security Question 1</label>
                                <select name="securityQuestion1" id="securityQuestion1" class="form-select">
                                    <option>Mothers maiden name</option>
                                    <option>First pets name</option>
                                    <option>First school</option>
                                    <option>Best friends name</option>
                                    <option>Favourite teacher</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Security Answer 1</label>
                                <input name="securityAnswer1" id="securityAnswer1" type="text" class="form-control" placeholder="Security Answer" value="" />
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Security Question 2</label>
                                <select name="securityQuestion2" id="securityQuestion2" class="form-select">
                                    <option>Mothers maiden name</option>
                                    <option>First pets name</option>
                                    <option>First school</option>
                                    <option>Best friends name</option>
                                    <option>Favourite teacher</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Security Answer 2</label>
                                <input name="securityAnswer2" id="securityAnswer2" type="text" class="form-control" placeholder="Security Answer" value="" />
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary profile-button" onclick="updateSecurityQuestionsAnswers(event);" type="submit">Save Account Recovery Questions</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php
    require_once "includes/footer.php";
    ?>
</body>

</html>