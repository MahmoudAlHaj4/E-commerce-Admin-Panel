<?php

include('../connection.php');
require '../vendor/autoload.php';
use Firebase\JWT\JWT;

$secret_key = '';

if(!empty($_POST['login']) && !empty($_POST['password'])){

    $loginInput = $_POST['login'];
    $password = $_POST['password'];
}else{

    $response['status'] = 'error';
    $response['message'] = 'All Feilds Required';
    echo json_encode($response);
    exit;
}

$query = $mysqli->prepare('
    SELECT id, email, password, role FROM admins WHERE email = ? OR username = ?
    UNION 
    SELECT id, email, password, NULL as role FROM customers WHERE email = ? OR username = ?');
$query->bind_param('ssss', $loginInput, $loginInput, $loginInput, $loginInput);
$query->execute();
$query->store_result();

if($query->num_rows === 0){
    $response['status'] = 'error';
    $response['message'] = 'Invalid Username or email';
    echo json_encode($response);
    exit;
}

$query->bind_result($id, $email, $hashed_password, $role);
$query->fetch();


if(password_verify($password , $hashed_password)){
    $issued_at = time();
    $expiration_time = $issued_at + 3600;  
    $payload = [
        'customer_id' => $id,
        'email' => $email,
        'role' => $role, 
        'iat' => $issued_at,
        'exp' => $expiration_time,

    ];

    $jwt = JWT::encode($payload, $secret_key, 'HS256');

    $response['status'] = 'success';
    $response['user'] = [
        'id'=>$id,
        'email' => $email,
        'role' => $role
    ];
    $response['token'] = $jwt;
}else{
    $response['status'] = 'error';
    $response['message'] = 'Invalid Password';

}
echo json_encode($response);

