<?php

include('../connection.php');

if(!empty($_POST['order_id'])){

    $order_id = $_POST['order_id'];

    $find_order = $mysqli->prepare('SELECT id FROM orders WHERE id = ?');
    $find_order->bind_param('i',$order_id);
    $find_order->execute();
    $find_order->store_result();


    if($find_order->num_rows == 0){
        $response['status'] = 'error';
        $response['message'] = 'Order Not Found';
        echo json_encode($response);
        exit;
    }

    $delete_order = $mysqli->prepare('DELETE FROM orders WHERE id = ?');
    $delete_order->bind_param('i', $order_id);

    if($delete_order->execute()){
        $response['status'] = 'success';
        $response['message'] = 'order Deleted successfully';
        echo json_encode($response);
        exit;
    }
}else{
    $response['status'] = 'error';
    $response['message'] = 'Invalid Input please provide order_id';
    echo json_encode($response);
    exit;
}