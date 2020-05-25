<?php
require_once './data/database.php';

function get_tasks($user_id){
    $database = new DB();
    return $database->get_results ( "SELECT t.*, p.title project_title, p.description project_description, p.date_created project_created, p.status project_status 
                                            FROM tasks t
                                                INNER JOIN projects p ON p.id = t.project_id 
                                        	WHERE t.user_id = " . $user_id);
};

function get_tasks_by_project($project_id){
    $database = new DB();
    return $database->get_results ( "SELECT t.*, p.title project_title, p.description project_description, p.date_created project_created, p.status project_status, u.f_name, u.l_name, u.username 
                                            FROM tasks t
                                                INNER JOIN projects p ON p.id = t.project_id
                                                INNER JOIN users u ON u.id = t.user_id
                                        	WHERE t.project_id =" . $project_id);
}

function add_task($project_id, $user_id, $description, $deadline_date, $status){
    $database = new DB();
    $task_added =  $database->insert ( 'tasks', array(
        'project_id' => $project_id,
        'user_id' => $user_id,
        'description' => $description,
        'deadline_date' => $deadline_date,
        'status' => $status
    ) );
    $id = $database->lastid ();
    try{
        if ($id) {
            $subject = "New Task";
            $message = "A new task is assigned to you. Click this link to view: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI'])."/index.php";
            $user = get_user_by_id ($user_id);
            $email = $user['email'];
            sendMail($subject, $message, $email);
        }

    }catch(Exception $e){

    }

    return $task_added;

}

function update_task($task_id, $user_id, $description, $deadline_date){
    $database = new DB();
    $update = array(
        'user_id' => $user_id,
        'description' => $description,
        'deadline_date' => $deadline_date
    );
    $update_where = array( 'id' => $task_id );
    return $database->update ('tasks', $update, $update_where);
}

function get_tasks_by_date($user_id, $date){
    $database = new DB();
    return $database->get_results ( "SELECT t.*, p.title project_title, p.description project_description, p.date_created project_created, p.status project_status, u.f_name, u.l_name, u.username
                                            FROM tasks t
                                                INNER JOIN projects p ON p.id = t.project_id
                                                INNER JOIN users u ON u.id = t.user_id
                                        	WHERE DATE_FORMAT(FROM_UNIXTIME(deadline_date), '%d-%m-%Y') = '" . $date . "' AND t.user_id = " . $user_id);
}


function complete_task($task_id){
    $database = new DB();
    $update = array(
        'status' => 1,
        'complete_date' => strtotime (date('Y-m-d H:i:s'))
    );
    $update_where = array( 'id' => $task_id );
    return $database->update ('tasks', $update, $update_where);
}