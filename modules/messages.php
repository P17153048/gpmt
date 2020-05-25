<?php
require_once './data/database.php';
require_once 'user.php';

function get_unread_message_count($user_id){
    $database = new DB();
    return $database->num_rows ('SELECT * FROM messages WHERE status = 0 AND deliver_id = ' . $user_id);
}

function send_message($sent_id, $deliver_id, $title, $message, $status, $date){
    $database = new DB();
    $sent =  $database->insert ( 'messages', array(
        'sent_id' => $sent_id,
        'deliver_id' => $deliver_id,
        'title' => $title,
        'message' => $message,
        'status' => $status,
        'date' => $date
    ) );
    $id = $database->lastid ();
    try{
        if ($id) {
            $subject = "New message";
            $message = "You've got new message. Click this link to view: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI'])."/message.php?id=".$id;
            $user = get_user_by_id ($deliver_id);
            $email = $user['email'];
            sendMail($subject, $message, $email);
        }
    }catch(Exception $e){

    }

    return $sent;
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

function get_message($message_id){
    $database = new DB();
    return $database->get_record ('SELECT m.*, u.f_name, u.l_name, u.email, u.username FROM messages m 
                                                INNER JOIN users u ON u.id = m.sent_id
                                            WHERE m.id =' . $message_id);
}

function read_message($message_id){
    $database = new DB();
    $update = array(
        'status' => 1
    );
    $update_where = array( 'id' => $message_id );
    $database->update ('messages', $update, $update_where);
}