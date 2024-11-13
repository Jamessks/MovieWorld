<?php

namespace Http\models;

use Core\App;
use Core\Database;
use Http\models\interfaces\BasicQueryable;

class User implements BasicQueryable
{
    protected $db;

    public function __construct()
    {
        $this->db = App::resolve(Database::class);
    }

    public function fetchUsername(int $id)
    {
        $results = $this->db->query('SELECT username FROM users WHERE id = :id', [
            'id' => $id
        ])->find();

        return $results;
    }

    public function exists($id)
    {
        $results = $this->db->query("SELECT COUNT(id) as count FROM users WHERE id =:id", [
            'id' => $id
        ])->find()['count'];

        return $results > 0;
    }
}
