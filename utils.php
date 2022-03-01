<?php

function verify_password($user_password, $hashed)
{
}

/**
 * Validates the email provided to the function for if the email is valid
 * @param string $email
 * @return boolean
 */
function validate_email($email)
{
    $result = false;
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
        $result = true;
    return $result;
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
    $result = false;
    if ($password === $repeat_password)
        $result = true;
    return $result;
}
