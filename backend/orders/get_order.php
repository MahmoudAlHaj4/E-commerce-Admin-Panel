<?php

include('../connection.php');

if(!empty($_POST['order_id'])){

    $order_id = $_POST['order_id'];


    $find_order = $mysqli->prepare('SELECT * FROM orders WHERE id = ?');
    $find_order->bind_param('i',$order_id);
    $find_order->execute();
    $find_order->store_result();

    if($find_order->num_rows == 0){
        $response['status'] = 'error';
        $response['message'] = 'Order Not Found';
        echo json_encode($response);
        exit;
    }

    $find_order->bind_result($id, $customer_id, $total_amount, $status, $created_at);
    $find_order->fetch();

    $response['status'] = 'success';
    $response['order'] = [
        'id'=>$id,
        'customer_id'=> $customer_id,
        'total'=>$total_amount,
        'status'=>$status,
        'created_at'=>$created_at
    ];
    echo json_encode($response);
    exit;

}elseif(!empty($_POST['customer_id'])){
    $customer_id = $_POST['customer_id'];

  
    $find_customer_order = $mysqli->prepare('SELECT * FROM orders WHERE customer_id = ?');
    $find_customer_order->bind_param('i',$customer_id);
    $find_customer_order->execute();
    $find_customer_order->store_result();

    if($find_customer_->num_rows == 0){
        $response['status'] = 'error';
        $response['message'] = 'order Not found';
        echo json_encode($response);
        exit;
    }

    $find_customer_order->bind_result($id, $total_amount, $status, $created_at);

    $orders = [];

    while($find_customer_order->fetch()){
        $order = [
           'id' => $id,
            'total_amount' => $total_amount,
            'status' => $status,
            'created_at' => $created_at
        ];
        $orders[] = $order;


        $response['status'] = 'success';
        $response['orders'] = $orders;
        echo json_encode($response);
        exit;
    }
}else{

    $find_all_orders = $mysqli->prepare('SELECT id, customer_id, total_amount, status, created_at FROM orders');
    $find_all_orders->execute();
    $find_all_orders->bind_result($id, $customer_id, $total_amount, $status, $created_at);

    $orders = [];

    while($find_all_orders->fetch()){
        $order = [
            'id' => $id,
            'total_amount' => $total_amount,
            'status' => $status,
            'created_at' => $created_at
        ];
        $orders[] = $order;

        

    }
    $response['status'] = 'success';
    $response['order'] = $orders;
    echo json_encode($response);
    exit;
}