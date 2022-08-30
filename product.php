
<?php

require_once('./database.php');

class Product extends ProductBase
{
    private $height;
    private $width;
    private $length;
    private $size;
    private $weight;

    public function __construct($height = 0, $width = 0, $length = 0, $size = 0, $weight = 0)
    {
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
        $this->size = $size;
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public static function count_all()
    {
        global $database;
        $sql = "SELECT COUNT(*) FROM " . self::$table_name;
        $result_set = $database->query($sql);
        $row = $database->fetch_array($result_set);
        return array_shift($row);
    }


    public function create()
    {
        global $database;
        $attributes = $this->sanitized_atributes();
        $sql = "INSERT INTO " . self::$table_name . " (";
        $sql .= "id, sku, name, price, type, size, height, width, length, weight";
        $sql .= ") values ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";

        $database->query($sql) ? $this->successfullyInserted($database) : $this . send_error();
    }

    public function send_error()
    {
        echo 'there was an error';
    }

    public function successfullyInserted($database)
    {
        $this->id = $database->insert_id();
        return true;
    }

    public function delete()
    {

        global $database;

        $sql = "DELETE FROM " . self::$table_name . " ";
        $sql .= " WHERE ID = " . $database->escape_value($this->id);
        $sql .= " LIMIT 1";

        $database->query($sql);
        return ($database->affected_rows() == 1) ? 'true' : 'false';
    }


    public function update()
    {

        global $database;

        $attributes = $this->sanitized_atributes();
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " . self::$table_name . " SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE id=" . $database->escape_value($this->id);
        $database->query($sql);
        return ($database->affected_rows() == 1) ? 'true' : 'false';
    }



    private function has_attribute($attribute)
    {
        $object_vars = get_object_vars($this);
        return array_key_exists($attribute, $object_vars);
    }


    protected function attributes()
    {

        $attributes = array();
        foreach (self::$db_fields as $field) {
            # code...
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }

        return $attributes;
    }

    protected function sanitized_atributes()
    {
        global $database;

        $clean_attributes = array();

        foreach ($this->attributes() as $key => $value) {
            # code...
            $clean_attributes[$key] = $database->escape_value($value);
        }

        return $clean_attributes;
    }
}

?>