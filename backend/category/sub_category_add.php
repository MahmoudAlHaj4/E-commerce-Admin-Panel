<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
include('../connection.php');


if (!empty($_POST['name']) && !empty($_POST['category_id'])) {
    
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];


    $check_category = $mysqli->prepare('SELECT id, parent_id FROM categories WHERE id = ?');
    $check_category->bind_param('i', $category_id);
    $check_category->execute();
    $check_category->store_result();


    if ($check_category->num_rows == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Main category not found';
        echo json_encode($response);
        exit;
    }



    $check_category->bind_result($category_id, $parent_id);
    $check_category->fetch();

    if (!is_null($parent_id)) {
        $response['status'] = 'error';
        $response['message'] = 'Subcategories cannot have subcategories';
        echo json_encode($response);
        exit;
    }

    $add_subcategory = $mysqli->prepare('INSERT INTO categories (name, parent_id, created_at) VALUES (?, ?, NOW()) ');
    $add_subcategory->bind_param('si', $name, $category_id);


    if ($add_subcategory->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Subcategory added successfully';
        $response['subcategory_id'] = $mysqli->insert_id;  
        $response['subcategory_name'] = $name;  
        $response['main_category'] = [
            'id' => $category_id,  
            
        ];

        echo json_encode($response);
        exit;
    } else {

        $response['status'] = 'error';
        $response['message'] = 'Failed to add subcategory';
        echo json_encode($response);
        exit;
    }

} else {

    $response['status'] = 'error';
    $response['message'] = 'Invalid input. Please provide category_id and name for subcategory';
    echo json_encode($response);
    exit;
}
