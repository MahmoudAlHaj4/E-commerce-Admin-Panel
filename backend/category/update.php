<?php 

include('../connection.php');

if(!empty($_POST['category_id']) && !empty($_POST['name'])){

    $id = $_POST['category_id'];
    $name = $_POST['name'];

    $check_category = $mysqli->prepare('SELECT id FROM categories WHERE id =?');
    $check_category->bind_param('i', $id);
    $check_category->execute();
    $check_category->store_result();

    if($check_category->num_rows == 0){
        $response['status'] = 'error';
        $response['message'] = 'Category Not Found';
        echo json_encode($response);
        exit;
    }

    $update_category = $mysqli->prepare('UPDATE categories SET name = ? WHERE id = ? ');
    $update_category->bind_param('si', $name,$id);

    if($update_category->execute()){
        $response['status'] = 'success';
        $response['message'] = 'category updated successfully';
        echo json_encode($response);
        exit;
    }else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to update category';
        echo json_encode($response);
        exit;
    }

}else{
     $response['status'] = 'error';
     $response['message'] = 'Invalid Input';
     echo json_encode($response);
     exit;
}