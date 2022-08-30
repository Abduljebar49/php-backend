<?php 

abstract class ProductBase extends DatabaseObject
{
    protected static $table_name = "product";
    protected static $db_fields = array(
        'id', 'sku', 'name', 'price', 'type', 'size', 'height', 'width', 'length', 'weight'
    );
    public $id;
    public $sku;
    public $name;
    public $price;
    public $type;
    public function __construct($sku = "", $name="", $price="",  $type="")
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }
    abstract public function create();
    abstract public function delete();
    abstract public function update();
    abstract public static function count_all();
}
