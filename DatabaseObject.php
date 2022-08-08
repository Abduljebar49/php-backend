
<?php

class DatabaseObject{


    
    public static function find_all(){
        
        $result_set = static::find_by_sql('SELECT * FROM '.static::$table_name);
        return $result_set;
    }

    public static function find_by_id($id = 0){

        global $database;    
        $result_array = static::find_by_sql('SELECT * FROM '.static::$table_name." where id = ".$database->escape_value($id));
        
        return !empty($result_array)?array_shift($result_array):false;
    }

    public static function find_by_sql($sql = ''){
        global $database;
        
        $result_set = $database->query($sql);
        if($result_set){

            $object_array = array();
            while($row=$database->fetch_array( $result_set)){
                $object_array[] = static::instantiate($row);
            }

            return $object_array;
        }
        else{
            return $result_set;
        }
    }


    private static function instantiate($record){

        $object = new static;
        // $object->id         = $record['id'];
        // $object->username   = $record['username'];
        // $object->password   = $record['password'];
        // $object->first_name = $record['first_name'];
        // $object->last_name  = $record['last_name'];
        foreach ($record as $attribute=>$value) {
            if($object->has_attribute($attribute)){
                $object->$attribute = $value;
            }
        }
        return $object;
    }
    private function has_attribute($attribute){
        $object_vars = get_object_vars($this);
        return array_key_exists($attribute,$object_vars);
    }


    protected function attributes(){

        $attributes = array();
        foreach (self::$db_fields as $field) {
            # code...
            if(property_exists($this, $field)){
                $attributes[$field] = $this->$field;
            }
        }

        return $attributes;
    }

    protected function sanitized_atributes(){
        global $database;

        $clean_attributes = array();

        foreach ($this->attributes() as $key => $value) {
            # code...
            $clean_attributes[$key] = $database->escape_value($value);
        }

        return $clean_attributes;
    }

    public function create(){
        global $database;


        $attributes = $this->sanitized_atributes();

        $sql = "INSERT INTO ".self::$table_name." (";
        $sql .= "id,username , password, first_name, last_name ";
        $sql .= ") values (NULL,'";
        $sql .= join(", ",array_values($attributes));
        $sql .= "')";
        if($database->query($sql)){
            $this->id = $database->insert_id();
            return true;
        }else{
            return false;
        }



    }

    public function delete(){

        global $database;

        $sql = "DELETE FROM ".self::$table_name." ";
        $sql .=" WHERE ID = ".$database->escape_value($this->id);
        $sql .=" LIMIT 1";

        $database->query($sql);
        return ($database->affected_rows()==1)?'true':'false';
    }

    public function save(){
        return isset($this->id)?$this->update():$this->delete();
    }

    public function update(){

        global $database;

        $attributes = $this->sanitized_atributes();
        $attribute_pairs = array();
        foreach ($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE ".self::$table_name." SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE id=".$database->escape_value($this->id);
        // $sql .= "username ='".$database->escape_value($this->username)."',";
        // $sql .= " password='".$database->escape_value($this->password)."',";
        // $sql .= " first_name='".$database->escape_value($this->first_name)."',";
        // $sql .= " last_name='".$database->escape_value($this->last_name)."'";

        $database->query($sql);
        return ($database->affected_rows()==1)? 'true':'false'; 
    }

}
?>