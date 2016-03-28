<?php

require_once realpath(dirname(__FILE__)) . '/../models/ModelInfo.php';

abstract class DataService {

    //put your code here
    public $contects, $tableName;

    public abstract function mapToModel($row);

    function __construct(contects $contects, $tableName) {
        $this->contects = $contects;
        $this->tableName = $tableName;
    }

    public function GetAll() {
        $sql = 'select * from %1$s';
        $result = $this->selectQuery(sprintf($sql, $this->tableName));
        $modelResult = array();
        if ($result!= FALSE) {
            while ($row = mysqli_fetch_assoc($result)) {
                // var_dump($row);
                $modelResult[] = $this->mapToModel($row);
            }
        }
        return $modelResult;
    }

    public function getById($id) {
        $sql = 'select * from %1$s where `%1$s`.`Id` = %2$s';
        $result = $this->selectQuery(sprintf($sql, $this->tableName, $id));
        $row = ($result != FALSE) ? mysqli_fetch_assoc($result) : '';
        $modelResult = ($row > 0) ? $this->mapToModel($row) : '';
        return $modelResult;
    }

    protected function Add(ModelInfo $object) {
            echo 'ADD';
            $sql = $this->GetInsertString($object);
            var_dump($sql);
            echo 'End ADD';
            
            return $this->InsertionQuery($sql, TRUE);
        
    }

    public function Update(ModelInfo $object) {
        if (is_subclass_of($this, 'sqlModel')) {
            $sql = $this->GetUpdateString($object);
            return $this->InsertionQuery($sql);
        }
    }

    private function Query($sql, $isInsert = 0) {
        $conn = mysqli_connect($this->contects->dbhost, $this->contects->dbuser, $this->contects->dbpass, $this->contects->db);
        // Check connection
        var_dump($sql);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        if ($isInsert != 0) {
            mysqli_query($conn, $sql);
            return $conn->insert_id;
        }
        return mysqli_query($conn, $sql);
    }

    private function InsertionQuery($sql, $isInsert = 0) {
        return $this->Query($sql, $isInsert);
    }

    protected function selectQuery($sql) {

        $result = $this->Query($sql);
        // var_dump($result);
        if ($result != FALSE && mysqli_num_rows($result) > 0) {
            // output data of each row
            return $result;
        } else {
            return null;
        }
    }

}
