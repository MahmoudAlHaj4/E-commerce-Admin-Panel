<?php

include('../connection.php');
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = 'your_secret_key'; 


$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    $jwt = str_replace('Bearer ', '', $headers['Authorization']);
    try {

        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        $userRole = $decoded->role; 
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Unauthorized access: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Token not provided.';
    echo json_encode($response);
    exit;
}



if ($userRole !== 'super-admin') {
    $response['status'] = 'error';
    $response['message'] = 'You do not have permission to delete this product.';
    echo json_encode($response);
    exit;
}

if(!empty($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $find_product = $mysqli->prepare('SELECT id FROM products WHERE id = ?');
    $find_product->bind_param('i', $product_id);
    $find_product->execute();
    $find_product->store_result();

    if($find_product->num_rows() == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Product not Found';
        echo json_encode($response);
        exit;
    }

    $delete_product = $mysqli->prepare('DELETE FROM products WHERE id = ?');
    $delete_product->bind_param('i', $product_id);

    if($delete_product->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Product deleted Successfully';
        echo json_encode($response);
        exit;
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid Input please provide product_id';
    echo json_encode($response);
    exit;
}
