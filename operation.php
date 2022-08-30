
<?php

class Operation
{

    function create($data)
    {
        $product = new Product();
        $product->setName($data['name']);
        $product->setSku($data['sku']);
        $product->setPrice($data['price']);
        $product->setType($data['type']);
        $type = $data['type'];
        $type == 'furniture' ? $this->createFurniture($product,$data) : ($type == 'dvd' ? $this->createDvd($product,$data) : ($type == 'book' ? $this->createBook($product,$data) : $this->invalidType()));
    }

    function read()
    {
        $sql = " SELECT * FROM product ";
        $product = new Product();
        $res = $product->find_by_sql($sql);
        $res[0] ? $this->send_data($res) : $this->response_error();
    }



    function delete($data)
    {
        $product = new Product();
        $product->id = $data['id'];
        $product->delete();
        echo json_encode(['message'=>'data successfully deleted']);
    }

    function send_data($res)
    {
        $x = 0;
        while ($x < count($res)) {
            $response[$x] = $res[$x];
            $x++;
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    function response_error()
    {
        return "There was an error";
    }

    function createFurniture($product,$data)
    {
        $product->setHeight($_POST['height']);
        $product->setWidth($_POST['width']);
        $product->setLength($_POST['length']);
        $product->create();
        echo json_encode(['message'=>'data successfully inserted']);
    }

    function createDvd($product,$data)
    {
        $product->setSize($_POST['size']);
        $product->create();
        echo json_encode(['message'=>'data successfully inserted']);
    }

    function createBook($product,$data)
    {
        $product->setWeight($_POST['weight']);
        $product->create();
        echo json_encode(['message'=>'data successfully inserted']);
    }

    function invalidType()
    {
        echo json_encode(['message' => 'type not found!']);
    }
}

?>