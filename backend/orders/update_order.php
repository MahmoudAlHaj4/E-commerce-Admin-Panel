<?php

include('../connection.php');


if(!empty($_POST['order_id']) && !empty($_POST['total amount'])){

    $order_id = $_POST['order_id'];
    $total_amount = $_POST['total_amount'];


    $find_order = $mysqli->prepare('SELECT id FROM orders WHERE id = ?');
    $find_order->bind_param('i', $order_id);
    $find_order->execute();
    $find_order->store_result();


    if($find_order->num_rows == 0){
        $response['status'] = 'error';
        $response['message'] = 'order Not Found';
        echo json_encode($response);
        exit;

    }

    $update_order = $mysqli->prepare('UPDATE orders SET total_amount = ?');
    
}