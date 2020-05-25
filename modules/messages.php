<?php
require_once './data/database.php';

function get_unread_message_count($user_id){
    $database = new DB();
    return $database->num_rows ('SELECT * FROM messages WHERE status = 0 AND deliver_id = ' . $user_id);
}

function send_message($sent_id, $deliver_id, $title, $message, $status, $date){
    $database = new DB();
    return $database->insert ( 'messages', array(
        'sent_id' => $sent_id,
        'deliver_id' => $deliver_id,
        'title' => $title,
        'message' => $message,
        'status' => $status,
        'date' => $date
    ) );
}