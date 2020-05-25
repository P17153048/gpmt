<?php
require_once './data/database.php';

function get_unread_message_count($user_id){
    $database = new DB();
    return $database->num_rows ('SELECT * FROM messages WHERE status = 0 AND deliver_id = ' . $user_id);
}