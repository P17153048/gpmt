<?php

function sendMail($subject, $message, $to) {
    global $admin_email;
    $headers = "From: " . $admin_email . "\r\n";
    $headers .= "Reply-To: ". $admin_email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    if (mail($to, $subject, $message, $headers)) {
        return true;
    }
}