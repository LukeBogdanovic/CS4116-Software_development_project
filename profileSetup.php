<?php
session_start();
/* Checking if the user is not logged in 
* If user is not logged in: the user is redirected to the login page and the script exits 
*/
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
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

            <div class="d-flex justify-content-center">

                <div class="col-md-6">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Edit Profile</h4>
                        <span class="font-weight-bold"><?php echo $_SESSION['username'] ?></span>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Name</label>
                            <input type="text" class="form-control" placeholder="first name" value="">
                        </div>

                        <div class="col-md-6">
                            <label class="labels">Surname</label>
                            <input type="text" class="form-control" value="" placeholder="surname">
                        </div>

                        <div class="col-md-6">
                            <label class="labels">Gender</label>
                            <select class="form-select">
                                <option>Male</option>
                                <option>Female</option>
                                <option>Prefer not to say</option>
                            </select>
                        </div>

                        <div class="col-md-6"><label class="labels">Seeking</label>
                            <select class="form-select">
                                <option>Male</option>
                                <option>Female</option>
                                <option>Both</option>
                            </select>
                        </div>

                        <div class="col-md-12"><label class="labels">Smoker</label>
                            <select class="form-select">
                                <option>Yes</option>
                                <option>No</option>
                                <option>Sometimes</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="labels">Employment</label>
                            <input type="text" class="form-control" placeholder="enter employment" value="">
                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-md-12"><label class="labels">Student</label>
                            <select class="form-select">
                                <option>Yes</option>
                                <option>No</option>
                                <option>Part-time</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="labels">College</label>
                            <input type="text" class="form-control" placeholder="enter college" value="">
                        </div>

                        <div class="col-md-12">
                            <label class="labels">Degree</label>
                            <input type="text" class="form-control" placeholder="enter degree" value="">
                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-md-6">
                            <label class="labels">Town</label>
                            <input type="text" class="form-control" placeholder="enter town" value="">
                        </div>

                        <div class="col-md-6"><label class="labels">County</label>
                            <select class="form-select">
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

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="labels">Bio</label>
                            <textarea maxlength="512" type="text" class="form-control" placeholder="enter bio" value=""> </textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="row mt-3">
                            <label class="labels">Add Pictures</label>
                            <form action="/action_page.php">
                                <input type="file" id="myFile" name="filename">
                                <input type="submit">
                            </form>
                        </div>
                    </div>

                    <div class="mt-5 text-center">
                        <button class="btn btn-primary profile-button" type="button">Save Profile</button>
                    </div>

                </div>

            </div>

        </div>

    </main>

    <?php
    require_once "includes/footer.php";
    ?>

</body>

</html>