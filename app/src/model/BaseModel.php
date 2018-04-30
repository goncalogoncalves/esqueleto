<?php

namespace Esqueleto\Model;

use Esqueleto\Classes\Database;

class BaseModel
{
    public $db = null;

    public function __construct()
    {
        $this->openDatabaseConnection();
    }

    /**
     * Responsible for creating a database instanace.
     */
    private function openDatabaseConnection()
    {
        if ($this->db == null) {
            try {
                $this->db = new Database();
            } catch (PDOException $e) {
                exit('Database connection could not be established.');
            }
        }
    }

}
