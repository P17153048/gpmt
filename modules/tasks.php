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