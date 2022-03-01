<?php

/**
 * Verifies the password entered by the user upon login and returns true if password matches
 * stored password or false value if password does not match stored password
 * @param string $user_password
 * @param string $hashed
 * @return boolean
 */
function verify_password($user_password, $hashed)
{
    if (password_verify($user_password, $hashed))
        return true;
    return false;
}

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
