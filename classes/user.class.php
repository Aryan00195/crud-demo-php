<?php

class USER
{
    private $db;
    private $table = 'user';
    private $fields = ['name', 'email', 'contact'];
    public function __construct()
    {
        $this->db = new DB();
    }

    public function getAll($fields = [], $conditions = [])
    {
        return $this->db->select($this->table, $fields, $conditions);
    }
    public function updateQuery($updateVal = [], $conditions = [])
    {
        $validated = [];
        $key = array_keys($updateVal);
        foreach ($key as $field) {
            if (!in_array($field, $this->fields)) {
                unset($updateVal[$field]);
            }
        }
        $checkUser = $this->checkUser($updateVal['email']);
        if ($checkUser) {
            return ['status' => false, "message" => "Email already used"];
        } else {
            // insert
            // echo "HELLO";
            return $this->db->update($this->table, $updateVal, $conditions);
        }

    }
    public function deleteQuery($conditions = [])
    {
        return $this->db->delete($this->table, $conditions);
    }
    // public function searchQuery($conditions=[])
    // {
    //     return $this->db->search($this->table,$conditions);
    // }
    public function insertFunc($column = [])
    {
        print_r($column);
        $errors = [];
        $validated = [];

        $key = array_keys($column);
        foreach ($key as $field) {
            if (!in_array($field, $this->fields)) {
                unset($column[$field]);
            }
            if ($this->validateField($field, $column)) {
                $validated[$field] = $column[$field];
            } else {
                $errors[] = $field . ' is required.';
            }
        }
        if (count($errors)) {
            return ['status' => false, "message" => $errors];
        } else {
            // check user
            $checkUser = $this->checkUser($column['email']);
            if ($checkUser) {
                return ['status' => false, "message" => "Email already used"];
            } else {
                // insert
                // echo "HELLO";
                return $this->db->insert($this->table, $validated);
            }
        }
    }
    public function validateField($field, $data)
    {
        // echo "HELLO";
        // echo $field;
        // $test = (!isset($data[$field]) || (isset($data[$field]) && trim($data[$field],"")));
        // echo $test;
        return (!isset($data[$field]) || (isset($data[$field]) && trim($data[$field], "")));
    }
    public function checkUser($email)
    {
        $result = $this->db->select($this->table, [], ['email' => $email]);
        return count($result);
    }
}
