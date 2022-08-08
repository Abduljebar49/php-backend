<?php
require_once('./initialize.php');
?>
<?php

require_once('./database.php');

$product = new Product();
//        'id', 'sku', 'name', 'price', 'type', 'size', 'height', 'width', 'length', 'weight'
print_r($_POST);
$product->name = $_POST['name'];;
$product->sku = $_POST['sku'];;
$product->price = $_POST['price'];;
$product->type = $_POST['type'];

if ($product->type == "dvd") {
    $product->size = $_POST['size'];;
    $product->height = 0;
    $product->width = 0;
    $product->length  = 0;
    $product->weight = 0;
} else if ($product->type == "furniture") {
    $product->size = 0;
    $product->height = $_POST['height'];;
    $product->width = $_POST['width'];;
    $product->length  = $_POST['length'];;
    $product->weight = 0;
} else {
    $product->size = 0;
    $product->height = 0;
    $product->width = 0;
    $product->length  = 0;
    $product->weight = $_POST['weight'];;
}

if ($product->create()) {
    echo json_encode(['message'=>'data successfully entered']);
} else {
    echo 'product not created';
}

?>
