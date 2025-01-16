<?php

include('../connection.php');

if(!empty($_POST['customer_id']) && !empty($_POST['total_amount']) ){

    $customer_id = $_POST['customer_id'];
    $total_amount = $_POST['total_amount'];

    $find_customer = $mysqli->prepare('SELECT customer_id FROM orders WHERE customer_id = ?');
    $find_customer->bind_param('i', $customer_id);
    $find_customer->execute();
    $find_customer->store_result();


    if($find_customer->num_rows == 0 ){
        $response['status'] = 'error';
        $response['message'] = 'Customer Not found';
        echo json_encode($response);
        exit;
    }

    $add_order = $mysqli->prepare('INSERT INTO orders (customer_id, order_date, total_amount) VALUES (?, NOW(), ?)');
    $add_order->bind_param('id', $customer_id, $total_amount);
    
    if($add_order->execute()){
        $response['status'] = 'success';
        $response['message'] = 'Order added successfully';
        $response['order_id'] = $mysqli->insert_id; 
        echo json_encode($response);
        exit;
        echo json_encode($response);
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to add order';
        echo json_encode($response);
    }


}else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid input';
    echo json_encode($response);
}