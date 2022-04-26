<?php
session_start();
/* Checking if the user is not logged in
 * If user is not logged in: the user is redirected to the login page and the script exits
 */
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
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
    <script src="js/home.js" defer></script>
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
            <form>
                <div>
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
                        <input type="text" id="ageLower" name="ageLower" size="1">to<input type="text" id="ageUpper" name="ageUpper" size="1">
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
                    <button class="btn submit" type="submit" onsubmit="getSuggestedUsers();">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid bg-trasparent my-4 p-3">
        <div class="card">
            <div class="card-header">Suggestions</div>
            <div class="card-body">
                <div class="row">
                    <div class="row row-cols-1 row-cols-xs-2 row-cols-sm-2 row-cols-lg-4 g-3" style="position: relative;" id="user-cards" data-user-cards-container></div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn py-1 submit" type="button" data-bs-toggle="offcanvas" data-bs-target="#applyFilters">
                    Edit Filters
                </button>
            </div>
        </div>
    </div>
    <template data-user-template>
        <div class="col">
            <div class="usercard card h-100 shadow-sm"><img src="assets/images/profile_pic.png" class="card-img-top" alt="Profile Picture" data-img>
                <div class="card-body">
                    <input value="" hidden data-userid>
                    <h5 class="card-title" data-header>Name</h5>
                    <h5 class="card-subtitle mb-2 text-muted" data-username>Username</h5>
                    <h6 class="card-subtitle mb-2 text-muted" data-age>Age</h5>
                        <p class="card-content" data-body>Bio</p>
                        <div data-interests></div>
                        <div class="text-center my-4">
                            <a class="btn submit" data-like>Like User</a>
                            <a class="btn btn-dark" data-profile>View Profile</a>
                            <a class="btn submit" data-dismiss>Dismiss User</a>
                        </div>
                </div>
            </div>
        </div>
    </template>
    <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHeader"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a type="button" class="btn submit" data-view-profile>View Profile</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once "includes/footer.php"
    ?>
</body>

</html>