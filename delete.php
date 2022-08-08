<?php
require_once('./initialize.php');
?>
<?php

require_once('./database.php');

$product = new Product();

$product->id = $_POST['id'];


if ($product->delete()) {
    echo json_encode(['message'=>'data successfully deleted']);
} else {
    echo 'product not created';
}

?>
