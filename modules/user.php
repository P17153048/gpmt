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
    $exists = $database->get_record ( "SELECT * FROM users WHERE email = '" . $email . "' OR username = '" . $username . "'" );

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

function update($id, $email, $password, $f_name, $l_name){
    $database = new DB();
    $update = array(
        'email' => $email,
        'password' => hash('sha256', $password),
        'f_name' => $f_name,
        'l_name' => $l_name
    );
    $update_where = array( 'id' => $id );
    return $database->update ('users', $update, $update_where);
}

function get_all_users(){
    $database = new DB();
    return $database->get_results( "SELECT id, f_name, l_name, username, email FROM users ORDER BY f_name ASC" );
}

function get_user_by_id($user_id){
    $database = new DB();
    return $database->get_record( "SELECT id, f_name, l_name, username, email FROM users WHERE id = " .  $user_id);
}