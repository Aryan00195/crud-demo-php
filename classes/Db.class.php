<?php
require_once 'credential.php';
// $servername = "localhost";

class DB
{
    private $con;
    public function __construct()
    {
        try {
            $this->con = new mysqli(SERVER, USER, PASS, DBNAME);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    // returns required data
    public function select($table, $fields = [], $conditions = [])
    {
        echo $table;
        $sql = "SELECT ";
        if (count($fields)) {
            $sql .= implode(",", $fields);
        } else {
            $sql .= " * ";
        }
        $sql .= " FROM $table";
        if (count($conditions)) {
            $sql .= " WHERE ";
            $i = 0;
            foreach ($conditions as $key => $value) {
                $i++;
                $sql .= $key . " = '" . $value . "'";
                if ($i != count($conditions)) {
                    $sql .= ", ";
                }
            }
        }
        $result  = $this->con->query($sql);

        if ($result) {
            if ($result->num_rows) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            } else {
                return [];
            }
        } else {
            echo $sql;
            die($this->con->error);
        }
    }
    // returns update query
    public function update($table, $updateVal = [], $conditions = [])
    {
        $sql = " UPDATE ";
        $sql .= "$table " . "SET ";
        if (count($updateVal)) {
            $i = 0;
            foreach ($updateVal as $key => $values) {
                $i++;
                $sql .= $key . " = '" . "$values" . "'";
                if ($i != count($updateVal)) {
                    $sql .= ", ";
                }
            }
        }
        if (count($conditions)) {
            $sql .= " WHERE ";
            $i = 0;
            foreach ($conditions as $key => $value) {
                $i++;
                $sql .= $key . " = '" . $value . "'";
                if ($i != count($conditions)) {
                    $sql .= "and ";
                }
            }
        }
        $result  = $this->con->query($sql);

        if ($result) {
            echo "Updated Successfully";
            return $result;
        } else {
            die($this->con->error);
        }
    }
    // Delete 
    public function delete($table, $conditions = [])
    {
        $sql = "DELETE From  $table ";
        if (count($conditions)) {
            $sql .= " WHERE ";
            $i = 0;
            foreach ($conditions as $key => $value) {
                $i++;
                $sql .= $key . " = '" . $value . "'";
                if ($i != count($conditions)) {
                    $sql .= "and ";
                }
            }
        }
        else{
            return ["status"=>false,"message"=>"Give Conditions !"];
        }
        $result  = $this->con->query($sql);
        if ($result) {
            echo "Deleted Successfully";
            return $result;
        } else {
            die($this->con->error);
        }
    }
    // Insert 
    public function insert($table, $column = [])
    {
        $sql = "INSERT INTO " . $table . " (";
        $sql .= implode(",", array_keys($column)) . ') VALUES (';
        $sql .= "'" . implode("','", array_values($column)) . "')";
        echo $sql;
        $result = $this->con->query($sql);
        if ($result) {
            echo "Inserted Successfully";
            return $result;
        } else {
            die($this->con->error);
        }
    }
}
