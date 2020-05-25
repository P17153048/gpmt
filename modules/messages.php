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

function get_all_messages($user_id){
    $database = new DB();
    return $database->get_results ('SELECT * FROM messages WHERE sent_id = ' . $user_id . ' OR deliver_id = ' . $user_id);
}

function get_all_received_messages($user_id){
    $database = new DB();
    return $database->get_results ('SELECT m.*, u.f_name, u.l_name, u.email, u.username FROM messages m 
                                                INNER JOIN users u ON u.id = m.sent_id
                                            WHERE m.deliver_id =' . $user_id);
}

function get_all_sent_messages($user_id){
    $database = new DB();
    return $database->get_results ('SELECT m.*, u.f_name, u.l_name, u.email, u.username FROM messages m 
                                                INNER JOIN users u ON u.id = m.deliver_id
                                            WHERE m.sent_id =' . $user_id);
}