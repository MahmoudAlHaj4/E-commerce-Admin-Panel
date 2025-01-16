<?php

include('../connection.php');

if(!empty($_GET['customer_id'])){ 
    $id = $_GET['customer_id'];


    $find_user = $mysqli->prepare('SELECT id, username, email, name, phone, address, created_at FROM customers WHERE id = ?');
    $find_user->bind_param('i', $id);
    $find_user->execute();
    $find_user->store_result();

    if($find_user->num_rows == 0){
        $response['status'] = 'error';
        $response['message'] = 'User not found';
        echo json_encode($response);
        exit;
    }

    $find_user->bind_result($id, $username, $email, $name, $phone, $address, $created_at);
    $find_user->fetch();


    $user = [
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'name' => $name,
        'phone' => $phone,
        'address' => $address,
        'created_at' => $created_at
    ];

    $response['status'] = 'success';
    $response['message'] = "User found";
    $response['user'] = $user;
    echo json_encode($response);
    exit;

} else {

    $find_user = $mysqli->prepare('SELECT id, username, email, name, phone, address, created_at FROM customers');
    $find_user->execute();
    $find_user->store_result();
    $find_user->bind_result($id, $username, $email, $name, $phone, $address, $created_at);

    $users = []; 
    while($find_user->fetch()){
        $user = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'created_at' => $created_at
        ];
        $users[] = $user; 
    }


    $response['status'] = 'success';
    $response['message'] = "Users found";
    $response['users'] = $users; 
    echo json_encode($response);
}
