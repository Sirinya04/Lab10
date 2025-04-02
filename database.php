<?php

class MyPDO extends PDO
{

    public function __construct()
    {

        $username = "root";
        $password = "";
        $host = "localhost";
        $dbname = "restaurant";

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        parent::__construct(
            $dsn,
            $username,
            $password
        );
    }
}

class DataMapper
{

    static $db;

    public $table = "";
    public $pk = "";
    public $fields = [];

    public $is_new = false;
    public $data = [];

    public function __construct($table, $pk, $fields)
    {
        $this->table = $table;
        $this->pk = $pk;
        $this->fields = $fields;
    }

    public static function init()
    {
        if (self::$db === null) {
            self::$db = new MyPDO();
        }
    }

    public static function select(
        $table_name,
        $condition = null,
        $parametor = null
    ) {
        $sql = "SELECT * FROM $table_name ";
        if (is_array($condition)) {
            // $condition = [ 'menu_name like "b%"', 'price<10' ] 
            // implode( ' AND ', $condition);
            // 'menu_name like 'b%' AND 'price<10'
            $sql .= " WHERE " . implode(' AND ', $condition);
        }
        $stmt = self::$db->prepare($sql);
        $result = $stmt->execute($parametor);
        if ($result) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function get($id)
    {
        // $sql = "SELECT * FROM $this->table WHERE :pk=:id ";
        $data = self::select(
            $this->table,
            ["$this->pk=:id"],
            ['id' => $id]
        );
        if ($data)
            return $data[0];
        return [];
    }

    public function list()
    {
        return self::select($this->table, );
    }

    public function add($data)
    {
        $sql = "INSERT INTO $this->table (" .
            implode(
                ',',
                $this->fields
            ) . ") VALUES (:" .
            implode(",:", $this->fields)
            . ") ";
        $stmt = self::$db->prepare($sql);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        // $data = ['menu_name' => 'bread', 'price' => 8];
        foreach ($data as $key => $value) {
            $data_key[] = "$key=:$key";
        }
        unset($data[$this->pk]);

        $sql = "UPDATE $this->table 
                SET " . implode(",", $data_key) .
            " WHERE $this->pk=:id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE $this->pk=:id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function save()
    {
        if ($this->is_new) {
            self::add($this->data);
            $this->is_new = false;
        } else {
            self::update($this->data[$this->pk], $this->data);
        }
    }
}

try {
    DataMapper::init();
} catch (Exception $e) {
    echo "" . $e->getMessage() . "";
}