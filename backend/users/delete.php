<?php

include('../connection.php');

if(!empty($_POST['customer_id'])){
    $customer_id = $_POST['customer_id'];


    $check_user = $mysqli->prepare('SELECT id FROM customers WHERE id =?');
    $check_user->bind_param('i', $customer_id);
    $check_user->execute();
    $check_user->store_result();

    if($check_user->num_rows == 0){
        $response['status'] = 'error';
        $response['message'] = 'User Not Found';
        echo json_encode($response);
        exit;
    }

    $delete_orders = $mysqli->prepare('DELETE FROM orders WHERE customer_id = ?');
    $delete_orders->bind_param('i',$customer_id);
    $delete_orders->execute();

    $delete_user = $mysqli->prepare('DELETE FROM customers WHERE id = ?');
    $delete_user->bind_param('i',$customer_id);

    if($delete_user->execute()){
        $response['status'] = 'success';
        $response['message'] = 'User and associated deleted successfully';
        echo json_encode($response);
        exit;
    }else{
        $response['status'] = 'error';
        $response['message'] = 'Failed to delete user';
        echo json_encode($response);
        exit;
    }
}else{
    $response['status'] = 'error';
    $response['message'] = 'Invalid Input';
    echo json_encode($response);
    exit;
}