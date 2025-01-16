<?php

include('../connection.php');


$find_categories = $mysqli->prepare('SELECT id, name, created_at FROM categories WHERE parent_id IS  NULL');
$find_categories->execute();
$find_categories->store_result();

$find_categories->bind_result($id, $name, $created_at);

$categories = [];
while ($find_categories->fetch()) {
    $categories[] = [
        'id' => $id,
        'name' => $name,
        'created_at' => $created_at 
    ];
}

$response['status'] = 'success';
$response['categories'] = $categories;

echo json_encode($response);
exit;



