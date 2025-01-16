<?php

include('../connection.php');

// Check if product_id is provided through GET
if (!empty($_GET['product_id'])) {
    $id = $_GET['product_id'];

    // Prepare the query to find the product
    $find_product = $mysqli->prepare('SELECT * FROM products WHERE id = ?');
    $find_product->bind_param('i', $id);
    $find_product->execute();
    $find_product->store_result();

    // Check if the product exists
    if ($find_product->num_rows == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Product Not Found';
        echo json_encode($response);
        exit;
    }

    // Bind the result to variables
    $find_product->bind_result($id, $name, $description, $price, $category_id, $stock_quantity, $created_at, $updated_at);
    $find_product->fetch();

    // Create a response array with the product details
    $response['status'] = 'success';
    $response['product'] = [
        'id' => $id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'category_id' => $category_id,
        'stock_quantity' => $stock_quantity,
        'created_at' => $created_at,
        'updated_at' => $updated_at
    ];

    // Output the response as JSON
    echo json_encode($response);
    exit;

} else {
    $find_product = $mysqli->prepare('SELECT * FROM products');
    $find_product->execute();
    $find_product->store_result();
    $find_product->bind_result($id, $name, $description, $price, $category_id, $stock_quantity, $created_at, $updated_at);

    $products = [];
    while($find_product->fetch()){
        $product = [
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $category_id,
            'stock_quantity' => $stock_quantity,
            'created_at' => $created_at,
            'updated_at' => $updated_at
        ];
        $products[] = $product;
    }

    $response['status'] = 'success';
    $response['message'] = 'products Found';
    $response['products'] = $products;
    echo json_encode($response);
    exit;
}
