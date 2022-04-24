<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Grá Go Deo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href=<?php echo (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) ? "home.php" : 'index.php' ?>>Home</a>
                </li>
                <?php echo (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) ?
                    '<li class="nav-item">
                    <a class="nav-link" href="profile.php?profile='. $_SESSION["id"] .'">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="connections.php">My Connections</a>
                </li>' : '';
                echo (isset($_SESSION['admin']) && $_SESSION['admin'] === true) ?
                    '<li class="nav-item">
                <a class="nav-link" href="adminDashboard.php">Admin Dashboard</a>
                </li>' : '';
                ?>
            </ul>
            <?php echo (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) ?
                '<form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <a class="btn submit" type="submit" href="search.php">Search</a>
                </form>
                <a href="logout.php" class="btn ml-3">Sign Out</a>' : '' ?>
        </div>
    </div>
</nav>