<?php 

include('../connection.php');
require '../vendor/autoload.php';
use Firebase\JWT\JWT;

$secret_key = "";

if(!empty($_POST['username']) && !empty(['email']) && !empty([$_POST['password']])){

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

}else{

}
$check_username_availability_in_admin = $mysqli->prepare('SELECT username FROM admins WHERE username = ?');
$check_username_availability_in_admin->bind_param('s', $username);
$check_username_availability_in_admin->execute();
$check_username_availability_in_admin->store_result();


if($check_username_availability_in_admin->num_rows()>0){
    $response['status'] = 'error';
    $response['message'] = 'Username already taken';
    echo json_encode($response);
    exit;
}


$check_email_availability_in_admin = $mysqli->prepare('SELECT email FROM admins WHERE email = ?');
$check_email_availability_in_admin->bind_param('s',$email);
$check_email_availability_in_admin->execute();
$check_email_availability_in_admin->store_result();

if($check_email_availability_in_admin->num_rows >0){
    $response['status'] = 'error';
    $response['message'] = 'Email Already Taken';
    echo json_encode($response);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$add_user = $mysqli->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
$add_user->bind_param('sss',$username,$email, $hashed_password);
$add_user->execute();
$add_user->close();

$admin_id = $mysqli->insert_id;

$issued_at = time();
$expiration_time = $issued_at + 3600; 
$payload = [
    'user_id' => $admin_id,
    'email' => $email,
    'iat' => $issued_at,
    'exp' => $expiration_time
];

$jwt = JWT::encode($payload, $secret_key, 'HS256');

$response['status']= 'success';
$response['message'] = 'User Add Successfully';
$response['user'] = [
    'admin'=> $admin_id,
    'email'=> $email,
    'token'=> $jwt,
];

echo json_encode($response);
