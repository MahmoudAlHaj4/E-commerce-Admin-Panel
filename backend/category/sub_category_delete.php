<?php 

include('../connection.php');

if (!empty($_POST['category_id'])) {

    $id = $_POST['category_id'];


    $check_category = $mysqli->prepare('SELECT id FROM categories WHERE id = ?');
    $check_category->bind_param('i', $id);
    $check_category->execute();
    $check_category->store_result();

    if ($check_category->num_rows == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Category Not Found';
        echo json_encode($response);
        exit;
    }

    $delete_subcategories = $mysqli->prepare('DELETE FROM categories WHERE parent_id = ?');
    $delete_subcategories->bind_param('i', $id);
    $delete_subcategories->execute();


    $delete_products = $mysqli->prepare('DELETE FROM products WHERE category_id = ?');
    $delete_products->bind_param('i', $id);
    $delete_products->execute();


    $response['status'] = 'success';
    $response['message'] = 'Subcategories and associated products deleted successfully';
    echo json_encode($response);
    exit;

} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid input';
    echo json_encode($response);
    exit;
}
