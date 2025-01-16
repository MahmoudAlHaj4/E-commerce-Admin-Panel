<?php

include('../connection.php');

if(!empty($_POST['user_id']) && !empty($_POST['email']) 
&& !empty($_POST['phone']) && !empty($_POST['address'])){

    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];


    $find_user = $mysqli->prepare('SELECT id, email FROM customers WHERE id = ?');
    $find_user->bind_param('i', $user_id);
    $find_user->execute();
    $find_user->store_result();

    if ($find_user->num_rows == 0) {
        $response['status'] = 'error';
        $response['message'] = 'User Not Found';
        echo json_encode($response);
        exit;
    }

    
    $find_user->bind_result($fetched_id, $current_email);
    $find_user->fetch();


    if ($email !== $current_email) {
        $check_email = $mysqli->prepare('SELECT email FROM customers WHERE email = ? AND id != ?');
        $check_email->bind_param('si', $email, $user_id);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Email already taken';
            echo json_encode($response);
            exit;
        }
    }

    $check_phone_number = $mysqli->prepare('SELECT phone FROM customers WHERE phone =?');
    $check_phone_number->bind_param('s',$phone);
    $check_phone_number->execute();
    $check_phone_number->store_result();

    if($check_phone_number->num_rows > 0){
        $response['status'] = 'error';
        $response['message'] = 'Phone number already used';
        echo json_encode($response);
        exit;
    }


    $update_user = $mysqli->prepare("UPDATE customers SET email = ?, phone = ?, address = ? WHERE id = ?");
    $update_user->bind_param('sssi', $email, $phone, $address, $user_id);
    
    if($update_user->execute()){
        $response['status'] = 'success';
        $response['message'] = 'User Updated Successfully';
        echo json_encode($response);
        exit;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to Update User';
        echo json_encode($response);
        exit;
    }

}else {
    $response['status'] = 'error';
    $response['message'] = 'All Fields Required';
}

echo json_encode($response);