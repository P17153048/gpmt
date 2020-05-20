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