<?php

class User
{
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }

    // public function getAll()
    // {
    //     return $this->db->query('user', ['name', 'email'], ['name' => 'Aaryan']);
    // }
    // public function updateQuery()
    // {
    //     return $this->db->update('user',['name'=>'Aryan'],['id'=>'1']);
    // }
    public function deleteQuery()
    {
        return $this->db->delete('user',['name'=>'Khushal']);
    }
}