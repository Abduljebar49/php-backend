<?php
require_once('./initialize.php');
?>
<?php

require_once('./database.php');
$sql = " SELECT * FROM product ";

$products = Product::find_by_sql($sql);
$response = array();
if ($products) {
    $x = 0;
    while ($x < count($products)) {
        $response[$x] = $products[$x];
        $x++;
    }
    echo json_encode($response, JSON_PRETTY_PRINT);
}

?>