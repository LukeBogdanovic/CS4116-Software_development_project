<?php

/**
 * Database Connection script; Used for connecting to the database
 * Tests the connection to the database and checks for connection error codes
 * If error code is found the script is exited
 * Otherwise the success of the connection is printed to the integrated developer console of the browser
 */
$DATABASE_HOST = 'sql302.epizy.com';
$DATABASE_USER = 'epiz_31123825';
$DATABASE_PASS = '0nwNXAwGlQ0KzjV';
$DATABASE_NAME = 'epiz_31123825_group13';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to db: ' . mysqli_connect_error());
}
mysqli_set_charset($con,"utf8mb4");