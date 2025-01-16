<?php

include('../connection.php');


$find_notification = $mysqli->prepare('SELECT message, created_at FROM notification');
$find_notification->execute();
$find_notification->store_result();
$find_notification->bind_result($message, $created_at);

$notifications = []; 
while($find_notification->fetch()){
    $notification = [
        'message' => $message,
        'created_at' => $created_at
    ];
    $notifications[] = $notification; 
}


$response['status'] = 'success';
$response['message'] = "notifications found";
$response['notification'] = $notifications; 
echo json_encode($response);