<?php

include('../connection.php');

if (!empty($_POST['name']) && !empty($_POST['description']) 
    && !empty($_POST['price']) && !empty($_POST['category_id']) && !empty($_POST['stock_quantity'])) {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $stock_quantity = $_POST['stock_quantity'];

    
    $check_category = $mysqli->prepare('SELECT parent_id FROM categories WHERE id = ?');
    $check_category->bind_param('i', $category_id);
    $check_category->execute();
    $check_category->store_result();

    if ($check_category->num_rows == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Category not found';
        echo json_encode($response);
        exit;
    }

    $check_category->bind_result($parent_id);
    $check_category->fetch();


    if ($parent_id === null) {
        $response['status'] = 'error';
        $response['message'] = 'The category is not a subcategory. Please use a valid subcategory.';
        echo json_encode($response);
        exit;
    }


    $check_product = $mysqli->prepare('SELECT id FROM products WHERE name = ?');
    $check_product->bind_param('s', $name);
    $check_product->execute();
    $check_product->store_result();

    if ($check_product->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Product already exists';
        echo json_encode($response);
        exit;
    } else {

        $add_product = $mysqli->prepare('INSERT INTO products (name, price, description, category_id, stock_quantity, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NULL)');
        $add_product->bind_param('sdssi', $name, $price, $description, $category_id, $stock_quantity);

        if ($add_product->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Product added successfully';
            $response['product_id'] = $mysqli->insert_id; 
            echo json_encode($response);
            exit;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to add product';
            echo json_encode($response);
            exit;
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid input. Please provide all required fields.';
    echo json_encode($response);
    exit;
}

