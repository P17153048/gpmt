<?php
session_start ();
require_once ( './data/database.php' );

function login($username, $password)
{
    $database = new DB();
    $user = $database->get_record ( "SELECT * FROM users WHERE email = '" . $username . "'" );
    if ($user != null) {
        if ($user[ "password" ] == hash ( "sha256", $password )) {
            $_SESSION[ "user" ] = $user;
            return 1;
        } else {
            return 2;
        }
    }
    return 0;
}

function create($username, $email, $f_name, $l_name, $password, $permission)
{
    $database = new DB();
    $exists = $database->get_record ( "SELECT * FROM users WHERE email = '" . $email . "'" );

    if ($exists != null) {
        return false;
    }
    return $database->insert ( 'users', array(
        'username' => $username,
        'email' => $email,
        'f_name' => $f_name,
        'l_name' => $l_name,
        'password' => hash ( 'sha256', $password ),
        'permissions' => $permission
    ) );
}