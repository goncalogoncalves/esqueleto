<?php

use Devgo\Utils;

/**
 * Model of the controller Page
 */
class PageModel
{

    public $db = null;

    /**
     * Prepare access to the db
     */
    public function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    /**
     * Responsible to get a page from the db
     * @return array
     */
    public function getPageById($id)
    {
        $this->db->query('SELECT * FROM page WHERE page_id = :id_page AND private = 0');
        $this->db->bind(':id_page', $id);
        $row = $this->db->single();

        return $row;
    }


}
