<?php
require_once './data/database.php';

function get_projects()
{
    $database = new DB();
    return $database->get_results ( "SELECT p.*, IFNULL(t.unfinished_tasks,0) unfinished_tasks FROM projects p
                                                LEFT JOIN (SELECT project_id, COUNT(*) AS unfinished_tasks 
                                                            FROM tasks
                                                            WHERE status = 0
                                                            GROUP BY project_id) t ON t.project_id = p.id;
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
    $update = array(
        'status' => 1
    );
    $update_where = array( 'id' => $project_id );
    return $database->update ('projects', $update, $update_where);
}

function delete_project($project_id){
    $database = new DB();
    $where = array( 'id' => $project_id);
    return $database->delete( 'projects', $where, 1 );
}