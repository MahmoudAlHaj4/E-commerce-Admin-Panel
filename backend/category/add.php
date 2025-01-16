<?php

include('../connection.php');


if(!empty($_POST['name'])){
    $name = $_POST['name'];


    $check_category = $mysqli->prepare('SELECT id FROM categories WHERE name =?');
    $check_category->bind_param('s', $name);
    $check_category->execute();
    $check_category->store_result();

    if($check_category->num_rows > 0){
        $response['status'] = 'error';
        $response['message'] = 'category already exists';
        echo json_encode($response);
        exit;
    }else{

        $add_category = $mysqli->prepare('INSERT INTO categories (name, created_at) VALUES (?, NOW())');
        $add_category->bind_param('s', $name);

        if($add_category->execute()){
            $response['status'] = 'success';
            $response['message'] = 'category added successfully';
            $response['category_id'] = $mysqli->insert_id;
            
            echo json_encode($response);
            exit;
        }else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to create category';
            echo json_encode($response);
            exit;
        }
    }
}else {

    $response['status'] = 'error';
    $response['message'] = 'Invalid input. Please provide a category name';
    echo json_encode($response);
    exit; }