<?php

class PDO1
{
    private $db;
    private $table = 'user';
    private $fields = ['name', 'email', 'password'];
    public function __construct()
    {
        $this->db = new DB2();
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
            return $this->db->update($this->table, $updateVal, $conditions);
        }
    }
    public function deleteQuery($conditions = [])
    {
        return $this->db->delete($this->table, $conditions);
    }
    public function searchQuery($conditions = [])
    {
        return $this->db->search($this->table, $conditions);
    }
    public function upload($image)
    {
        $target_dir = __DIR__ . '/uploads/';
        // if (!file_exists($target_dir)) {
        //     mkdir($target_dir);
        // }
        $target_file = $target_dir . $image['name'];
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            return ["status" => true, "message" => "File Uploaded"];
        } else {
            return ["status" => false, "message" => "File upload unsuccessful"];
        }
    }
    public function getupload()
    {
        $target_dir = "uploads/Lord-Ganesh-Images-Photos-HD-Wallpapers.jpg";
        $response = 'http://localhost/crud-demo-php/classes/' . $target_dir;
        return $response;
    }

    public function downloadImg($name)
    {
        if (file_exists(__DIR__ . '/uploads/' . $name)) {
            $target_file = __DIR__ . '/uploads/' . $name;

            header("Content-Type: image/jpg");
            header("Content-Disposition:attachement;filename='download_image.jpg'");
            header("Content-Description: File Transfer");
            header("Content-Length" . filesize($target_file));
            return readfile($target_file);
        } else {
            return ["status" => false, "message" => "Something went wrong"];
        }
    }
    public function insertFunc($column = [])
    {
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
        // if (count($errors)) {
        //     return ['status' => false, "message" => $errors];
        // } else {
        //     $checkUser = $this->checkUser($column['email']);
        //     if ($checkUser) {
        //         return ['status' => false, "message" => "Email already used"];
        //     } else {
        //     }
        // }
        return $this->db->insert($this->table, $validated);
    }
    public function validateField($field, $data)
    {
        return (!isset($data[$field]) || (isset($data[$field]) && trim($data[$field], "")));
    }
    public function checkUser($email)
    {
        $result = $this->db->select($this->table, [], ['email' => $email]);
        return count($result);
    }
}
