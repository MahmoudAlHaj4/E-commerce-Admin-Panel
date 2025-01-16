<?php

include('../connection.php');

if(!empty($_GET['admin_id'])){ 
    $id = $_GET['admin_id'];

    $find_admin = $mysqli->prepare('SELECT id, username, email, role, created_at FROM admins WHERE id = ?');
    $find_admin->bind_param('i', $id);
    $find_admin->execute();
    $find_admin->store_result();

    if($find_admin->num_rows == 0){
        $response['status'] = 'error';
        $response['message'] = 'Admin not found';
        echo json_encode($response);
        exit;
    }

    $find_admin->bind_result($id, $username, $email, $role, $created_at);
    $find_admin->fetch();


    $admin = [
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'role' => $role,
        'created_at' => $created_at
    ];

    $response['status'] = 'success';
    $response['message'] = "Admin found";
    $response['user'] = $admin;
    echo json_encode($response);
    exit;

}else {

    $find_admin = $mysqli->prepare('SELECT id, username, email, role, created_at FROM admins');
    $find_admin->execute();
    $find_admin->store_result();
    $find_admin->bind_result($id, $username, $email, $role, $created_at);

    $admins = []; 
    while($find_admin->fetch()){
        $admin = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'role'=>$role,
            'created_at' => $created_at
        ];
        $admins[] = $admin; 
    }


    $response['status'] = 'success';
    $response['message'] = "Admins found";
    $response['admins'] = $admins; 
    echo json_encode($response);
}
