<?php


require_once __DIR__. '/../models/ModelInfo.php';

class DataService {

    //put your code here
    public $contects, $tableName;

    function __construct(contects $contects, $tableName) {
        $this->contects = $contects;
        $this->tableName = $tableName;
    }

    public function GetAll() {
        $sql = 'select * from %1$s';
        return $this->selectQuery(sprintf($sql, $this->tableName));
    }

    public function getById($id) {
        $sql = 'select * from %1$s where `%1$s`.`Id` = %2$s';
        return $this->selectQuery(sprintf($sql, $this->tableName, $id));
    }

    protected function Add(ModelInfo $object) {
        if (is_subclass_of($this, 'sqlModel')) {
            $sql = $this->GetInsertString($object);
            return $this->InsertionQuery($sql,TRUE);
        }
    }

    public function Update(ModelInfo $object) {
        if (is_subclass_of($this, 'sqlModel')) {
            $sql = $this->GetUpdateString($object);
            return $this->InsertionQuery($sql);
        }
    }

    private function Query($sql,$isInsert =0) {
        $conn = mysqli_connect($this->contects->dbhost, $this->contects->dbuser, $this->contects->dbpass, $this->contects->db);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        if($isInsert!=0){
            mysqli_query($conn, $sql); 
            return $conn->insert_id;
        }
        return mysqli_query($conn, $sql);
    }

    private function InsertionQuery($sql,$isInsert =0) {
        return $this->Query($sql,$isInsert);
    }

    private function selectQuery($sql) {

        $result = $this->Query($sql);
        // var_dump($result);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            return $result;
        } else {
            return null;
        }
    }

}
