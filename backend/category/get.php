<?php

include('../connection.php');

if (!empty($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $find_category = $mysqli->prepare('SELECT categories.id, categories.name, COUNT(products.id) AS product_count 
                                        FROM categories 
                                        LEFT JOIN products ON categories.id = products.category_id 
                                        WHERE categories.id = ?
                                        GROUP BY categories.id');
    $find_category->bind_param('i', $category_id);
    $find_category->execute();
    $find_category->store_result();

    if ($find_category->num_rows == 0) {
        $response['status'] = 'error';
        $response['message'] = 'Category not found';
        echo json_encode($response);
        exit;
    }

    $find_category->bind_result($id, $name, $product_count);
    $find_category->fetch();


    $find_subcategories = $mysqli->prepare('SELECT categories.id, categories.name, COUNT(products.id) AS product_count 
                                            FROM categories 
                                            LEFT JOIN products ON categories.id = products.category_id 
                                            WHERE categories.parent_id = ?
                                            GROUP BY categories.id');
    $find_subcategories->bind_param('i', $category_id);
    $find_subcategories->execute();
    $find_subcategories->store_result();
    $find_subcategories->bind_result($sub_id, $sub_name, $sub_product_count);

    $subcategories = [];
    while ($find_subcategories->fetch()) {
        $subcategories[] = [
            'id' => $sub_id,
            'name' => $sub_name,
            'num_of_products' => $sub_product_count
        ];
    }

    $response['status'] = 'success';
    $response['category'] = [
        'id' => $id,
        'name' => $name,
        'num_of_products' => $product_count
    ];
    $response['subcategories'] = $subcategories;
    echo json_encode($response);
    exit;

} else {

    $find_categories = $mysqli->prepare('SELECT categories.id, categories.name, COUNT(products.id) AS product_count
                                         FROM categories
                                         LEFT JOIN products ON categories.id = products.category_id
                                         WHERE categories.parent_id IS NULL
                                         GROUP BY categories.id');
    $find_categories->execute();
    $find_categories->store_result();
    $find_categories->bind_result($id, $name, $product_count);

    $categories = [];
    while ($find_categories->fetch()) {

        $find_subcategories = $mysqli->prepare('SELECT categories.id, categories.name, COUNT(products.id) AS product_count 
                                                FROM categories 
                                                LEFT JOIN products ON categories.id = products.category_id 
                                                WHERE categories.parent_id = ?
                                                GROUP BY categories.id');
        $find_subcategories->bind_param('i', $id);
        $find_subcategories->execute();
        $find_subcategories->store_result();
        $find_subcategories->bind_result($sub_id, $sub_name, $sub_product_count);

        $subcategories = [];
        while ($find_subcategories->fetch()) {
            $subcategories[] = [
                'id' => $sub_id,
                'name' => $sub_name,
                'num_of_products' => $sub_product_count
            ];
        }

        $categories[] = [
            'id' => $id,
            'name' => $name,
            // 'num_of_products' => $product_count,
            'subcategories' => $subcategories
        ];
    }

    $response['status'] = 'success';
    $response['categories'] = $categories;
    echo json_encode($response);
    exit;
}
