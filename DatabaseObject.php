
<?php

class DatabaseObject
{



    public static function find_all()
    {

        $result_set = static::find_by_sql('SELECT * FROM ' . static::$table_name);
        return $result_set;
    }

    public static function find_by_id($id = 0)
    {

        global $database;
        $result_array = static::find_by_sql('SELECT * FROM ' . static::$table_name . " where id = " . $database->escape_value($id));

        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_sql($sql = '')
    {
        global $database;

        $result_set = $database->query($sql);
        $object_array = array();
        while ($row = $database->fetch_array($result_set)) {
            $object_array[] = static::instantiate($row);
        }
        return $object_array;

    }
    private static function instantiate($record)
    {

        $object = new ProductModel;

        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
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
            $clean_attributes[$key] = $database->escape_value($value);
        }

        return $clean_attributes;
    }
}
?>