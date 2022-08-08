
<?php

require_once('./database.php');

class Product extends DatabaseObject
{
    protected static $table_name = "product";
    protected static $db_fields = array(
        'id', 'sku', 'name', 'price', 'type', 'size', 'height', 'width', 'length', 'weight'
    );

    public $id;
    public $sku;
    public $name;
    public $price;
    public $size;
    public $type;
    public $height;
    public $width;
    public $length;
    public $weight;
    private $temp_path;


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
        if ($database->query($sql)) {
            $this->id = $database->insert_id();
            return true;
        } else {
            return false;
        }
    }


    public function save()
    {

        if (isset($this->id)) {
            $this->update();
        } else {

            if (!empty($this->errors)) {
                return false;
            }
            if (strlen($this->caption) > 255) {
                $this->errors[] = "The caption can only be less or equal to 255 ";
                return false;
            }

            $target_path = SITE_ROOT . DB . 'public' . DB . $this->upload_dir . DB . $this->filename;
            if (file_exists($target_path)) {
                $this->errors[] = "The file {$this->filename} already exits";
                return false;
            }
            if (move_uploaded_file(
                $this->temp_path,
                $target_path
            )) {

                if ($this->create()) {
                    unset($this->temp_path);
                    return true;
                }
            } else {
                $this->errors[] = "The file upload failed,
				possibily due to incorrect permissions on the 
				upload folder.";
                return false;
            }
        }
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
        // $sql .= "username ='".$database->escape_value($this->username)."',";
        // $sql .= " password='".$database->escape_value($this->password)."',";
        // $sql .= " first_name='".$database->escape_value($this->first_name)."',";
        // $sql .= " last_name='".$database->escape_value($this->last_name)."'";

        $database->query($sql);
        return ($database->affected_rows() == 1) ? 'true' : 'false';
    }
}

?>






















