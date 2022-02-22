<?php
$DATABASE_HOST = 'sql302.epizy.com';
$DATABASE_USER = 'epiz_31123825';
$DATABASE_PASS = '0nwNXAwGlQ0KzjV';
$DATABASE_NAME = 'epiz_31123825_group13';
$con = mysqli_connect($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);
if(mysqli_connect_errno())
    exit('Failed to connect to db: ' . mysqli_connect_errno());
else
    console_log('Connected to Database: ' . $DATABASE_NAME);

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags)
        $js_code = '<script>' . $js_code . '</script>';
    echo $js_code;
}
?>