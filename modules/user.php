<?php
session_start ();
require_once ( './data/database.php' );

function login($username, $password)
{
    $database = new DB();
    $user = $database->get_record ( "SELECT * FROM users WHERE email = '" . $username . "'" );
    if ($user != null) {
        if($user["password"] == hash("sha256", $password)){
            $_SESSION["user"] = $user;
            return 1;
        }else{
            return 2;
        }
    }
    return 0;
}