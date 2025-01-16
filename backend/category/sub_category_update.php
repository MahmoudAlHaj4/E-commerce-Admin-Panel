<?php

include('../connection.php');


if(!empty($_POST['sub_category_id']) && !empty($_POST['name'])){

    $sub_category_id = $_POST['sub_category_id'];
    $name = $_POST['name'];

    $check_sub_category = $mysqli->prepare('SELECT id FROM categories WHERE id = ? AND parent_id IS NOT NULL');
    $check_sub_category->bind_param('i', $sub_category_id);
    $check_sub_category->execute();
    $check_sub_category->store_result();

    if($check_sub_category->num_rows == 0){

        $response['status'] = 'Error';
        $response['message'] = 'Subcategory Not Found';
        echo json_encode($response);
        exit;
    }

    $update_sub_category = $mysqli->prepare('UPDATE categories SET name = ? WHERE id = ?');
    $update_sub_category->bind_param('si',$name , $sub_category_id);

    if($update_sub_category->execute()){

        $response['status'] = 'success';
        $response['message'] = 'sub-category Updated successfully';
        echo json_encode($response);
        exit;
    }else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update subcategory';
        echo json_encode($response);
        exit;
    }


}else {

    $response['status'] = 'error';
    $response['message'] = 'Invalid input. Please provide subcategory_id and name.';
    echo json_encode($response);
    exit;
}