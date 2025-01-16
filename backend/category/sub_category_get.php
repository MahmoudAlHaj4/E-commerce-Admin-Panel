<?php

include('../connection.php');

if(!empty($_GET['category_id'])){

    $id = $_GET['category_id'];


    $find_sub_categories = $mysqli->prepare('SELECT id, name FROM categories WHERE id = ? AND parent_id IS NOT NULL');
    $find_sub_categories->bind_param('i',$id);
    $find_sub_categories->execute();
    $find_sub_categories->store_result();
    $find_sub_categories->bind_result($sub_id,$sub_name);

    error_log("Number of rows found: " . $find_sub_categories->num_rows);

    $subcategories = [];
    while ($find_sub_categories->fetch()) {
        $subcategories[] = [
            'id' => $sub_id,
            'name' => $sub_name
        ];
    
    }
    if (count($subcategories) > 0) {
        $response['status'] = 'success';
        $response['subcategories'] = $subcategories;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No subcategories found for the given category';
    }

    
    echo json_encode($response);
    exit;
}else {
    $find_sub_category = $mysqli->prepare(
        'SELECT c.id, c.name, p.name AS parent_name, c.created_at
         FROM categories c
         LEFT JOIN categories p ON c.parent_id = p.id
         WHERE c.parent_id IS NOT NULL'
    );
    $find_sub_category->execute();
    $find_sub_category->store_result();
    $find_sub_category->bind_result($id, $name, $parent_name, $created_at);

    $subcategories = [];
    while ($find_sub_category->fetch()) {
        $subcategory = [
            'id' => $id,
            'name' => $name,
            'parent_name' => $parent_name,
            'created_at' => $created_at,
        ];
        $subcategories[] = $subcategory;
    }

    $response['status'] = 'success';
    $response['message'] = 'Subcategories Found';
    $response['subcategories'] = $subcategories;
    echo json_encode($response);
    exit;
    
}