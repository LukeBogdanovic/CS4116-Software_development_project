<?php

/**
 * Logout script; Used for logging the user out of the website upon their request
 * Reinitializes the session variables to their original state before login and destroys the session
 * Provides a redirect header for the user that brings them to the login page then exits the script
 */
session_start();
$_SESSION = array();
session_destroy();
header("location: login.php");
exit;
