<?php
require_once './data/database.php';
require_once 'user.php';
require_once 'mailer.php';

function get_projects()
{
    $database = new DB();
    return $database->get_results ( "SELECT p.*, IFNULL(t.unfinished_tasks,0) unfinished_tasks, u.f_name, u.l_name FROM projects p
                                                LEFT JOIN (SELECT project_id, COUNT(*) AS unfinished_tasks 
                                                            FROM tasks
                                                            WHERE status = 0
                                                            GROUP BY project_id) t ON t.project_id = p.id
                                                INNER JOIN users u ON u.id = p.created_by
                                    " );
}

function create_project($title, $description, $date_created, $created_by, $status){
    $database = new DB();
    return $database->insert ( 'projects', array(
        'title' => $title,
        'description' => $description,
        'date_created' => $date_created,
        'created_by' => $created_by,
        'status' => $status
    ) );
}

function get_project($project_id){
    $database = new DB();
    return $database->get_record ('SELECT * FROM projects WHERE id = ' . $project_id);
}

function complete_project($project_id){
    $database = new DB();
    $tasks_completed = complete_tasks ($project_id);
    $update = array(
        'status' => 1
    );
    $update_where = array( 'id' => $project_id );
    return $tasks_completed && $database->update ('projects', $update, $update_where);
}

function delete_project($project_id){
    $database = new DB();
    $where = array( 'id' => $project_id);
    $deleted_invites = delete_invites ($project_id);
    $deleted_tasks = delete_tasks ($project_id);
    return $deleted_invites && $deleted_tasks && $database->delete( 'projects', $where, 1 );
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function invite_user($project_id, $user_id){
    $database = new DB();
    $user_invited = $database->insert ( 'invites', array(
        'id' => generateRandomString (),
        'project_id' => $project_id,
        'user_id' => $user_id,
        'confirmed' => 0
    ) );
    try{
        $subject = "New message";
        $message = "You're invited to a new project. Click this link to view: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI'])."/projects.php";
        $user = get_user_by_id ($user_id);
        $email = $user['email'];
        sendMail($subject, $message, $email);
    }catch (Exception $e){

    }

    return $user_invited;
}

function update_project($project_id, $title, $description){
    $database = new DB();
    $update = array(
        'title' => $title,
        'description' => $description
    );
    $update_where = array( 'id' => $project_id );
    return $database->update ('projects', $update, $update_where);
}

function delete_invites($project_id){
    $database = new DB();
    $where = array( 'project_id' => $project_id);
    return $database->delete( 'invites', $where, 999999999 );
}

function delete_tasks($project_id){
    $database = new DB();
    $where = array( 'project_id' => $project_id);
    return $database->delete( 'tasks', $where, 999999999 );
}

function complete_tasks($project_id){
    $database = new DB();
    $update = array(
        'status' => 1,
        'complete_date' => strtotime (date('Y-m-d H:i:s'))
    );
    $update_where = array( 'project_id' => $project_id );
    return $database->update ('tasks', $update, $update_where);
}