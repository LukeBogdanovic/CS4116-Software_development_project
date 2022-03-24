<?php


/**
 * Takes the Input of a password string and returns a string of the hashed password using the PASSWORD_DEFAULT algorithm
 * @param string $password
 * @return string
 */
function hash_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Validates the email provided to the function for if the email is valid
 * @param string $email
 * @return boolean
 */
function validate_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
        return true;
    return false;
}

/**
 * Verifies that the password entered by the user matches the confirm password
 * entry entered by the user at time of registration
 * @param string $password
 * @param string $repeat_password
 * @return boolean
 */
function validate_password($password, $repeat_password)
{
    if ($password === $repeat_password)
        return true;
    return false;
}

/**
 * Logs output param provided to the function to the developer console of the browser in use 
 * @param $output String Output to be printed
 * @param $with_script_tags boolean Selector for script html tags
 * @return null
 */
function console_log($output, $with_script_tags  = true)
{
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

/**
 * returns an int $age when given a date of birth in the format yyyy-mm-dd
 * @param $date_of_birth String formatted yyyy-mm-dd
 * @return int
 */
function get_age($date_of_birth){
    //date in mm-dd-yyyy format; or it can be in other formats as well
    //explode the date to get month, day and year
    $date_of_birth = explode("-", $date_of_birth);
    //get age from date or date_of_birth
    $age = (date("md", date("U", mktime(0, 0, 0, $date_of_birth[0], $date_of_birth[1], $date_of_birth[2]))) > date("md")
        ? ((date("Y") - $date_of_birth[0]) - 1)
        : (date("Y") - $date_of_birth[0]));
    return $age;
}
