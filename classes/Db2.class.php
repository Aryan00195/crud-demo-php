<?php
include_once 'credential.php';
class DB2
{
    private $con;
    public function __construct()
    {
        try {
            $this->con = new PDO("mysql:host=" . SERVER . ";dbname=" . DBNAME, USER, PASS);
            // set  PDO error exception
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    public function select($table, $fields = [], $conditions = [])
    {
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
        $response = $this->con->prepare($sql);
        $result = $response->execute();
        if ($result) {
            $result = $response->fetchAll();
            if (count($result))
                return ["status" => true, "Data" => $result];
            else
                return ["status" => false, "message" => "Record not Found !"];
        } else {
            echo $sql;
            die("Something went wrong");
        }
    }
    public function search($table, $fields = [], $conditions = [])
    {
        $sql = "SELECT ";
        if (count($fields)) {
            $sql .= implode(",", $fields);
        } else {
            $sql .= " Name";
        }
        $sql .= " FROM $table";
        if (count($conditions)) {
            $sql .= " WHERE Name like '%Name%'";
            // $i = 0;
            // foreach ($conditions as $key => $value) {
            //     $i++;
            //     $sql .= $key . " = '" . $value . "'";
            //     if ($i != count($conditions)) {
            //         $sql .= ", ";
            //     }
            // }
        }
        $response = $this->con->prepare($sql);
        $result = $response->execute();
        if ($result) {
            $result = $response->fetchAll();
            if (count($result))
                return ["status" => true, "Data" => $result];
            else
                return ["status" => false, "message" => "Record not Found !"];
        } else {
            echo $sql;
            die("Something went wrong");
        }
    }
    // returns update query
    public function update($table, $updateVal = [], $conditions = [])
    {
        $sql = " UPDATE " . "$table " . "SET ";
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
        $response = $this->con->prepare($sql);
        $result = $response->execute();
        if ($result) {
            echo "Updated Successfully ";
            return $result . "row";
        } else {
            die($this->con->errorinfo());
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
        $response = $this->con->prepare($sql);
        $result = $response->execute();
        if ($result) {
            echo "Deleted Successfully";
            return $result;
        } else {
            die($this->con->errorinfo());
        }
    }
    // Insert 
    public function insert($table, $column = [])
    {
        $sql = "INSERT INTO " . $table . " (";
        $sql .= implode(",", array_keys($column)) . ') VALUES (';
        $sql .= "'" . implode("','", array_values($column)) . "')";
        echo $sql;
        $response = $this->con->prepare($sql);
        $result = $response->execute();
        if ($result) {
            echo "Inserted Successfully";
            return $result;
        } else {
            die($this->con->errorinfo());
        }
    }
}
