<?php

include('../connection.php');
require '../vendor/autoload.php';
use Firebase\JWT\JWT;


$secret_key = "";

if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])
&& !empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['address']) 
){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $role = 'customer';


}else{
    $response['status'] = 'error';
    $response['message'] = 'All Feilds Required';
    echo json_encode($response);
    exit;
}

$check_username_availability = $mysqli->prepare('SELECT username FROM customers WHERE username = ?');
$check_username_availability->bind_param('s', $username);
$check_username_availability->execute();
$check_username_availability->store_result();

if($check_username_availability->num_rows > 0){
    $response['status'] = 'error';
    $response['message'] = 'User Already Taken';
    echo json_encode($response);
    exit;
}


$check_email_availability_in_customer = $mysqli->prepare('SELECT email FROM customers WHERE email =?');
$check_email_availability_in_customer->bind_param('s',$email);
$check_email_availability_in_customer->execute();
$check_email_availability_in_customer->store_result();


if($check_email_availability_in_customer->num_rows > 0){
    $response['status'] = 'error';
    $response['message'] = 'Email already Taken';
    echo json_encode($response);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$add_user = $mysqli->prepare("INSERT INTO customers (username, email, password, name, phone, address ) VALUES (?, ?, ?, ?, ?, ?)");
$add_user->bind_param('ssssss',$username,$email, $hashed_password, $name, $phone, $address);
$add_user->execute();
$add_user->close();

$customer_id = $mysqli->insert_id;

$notification_message = "User '$username' has been created.";
$add_notification = $mysqli->prepare("INSERT INTO notification (message,created_at) VALUES (?, NOW()");
$add_notification->bind_param('s', $notification_message);
$add_notification->execute();
$add_notification->close();


$issued_at = time();
$expiration_time = $issued_at + 3600; 
$payload = [
    'user_id' => $customer_id,
    'email' => $email,
    'iat' => $issued_at,
    'exp' => $expiration_time,
    'role'=> $role
];

$jwt = JWT::encode($payload, $secret_key, 'HS256');

$response['status']= 'success';
$response['message'] = 'User Add Successfully';
$response['user'] = [
    'customer'=> $customer_id,
    'email'=> $email,

];

echo json_encode($response);