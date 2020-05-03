<?php
/**
 * Tags Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class Tags extends Core\Tag {
    // DB Stuff
    private $conn;
    private $table = 'tags';

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Get All Tags
    public function getTags()
    {
        // query string
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY TagName ASC';

        // prepare statement
        $statement = $this->conn->prepare($query);

        // execute query
        $statement->execute();
        $row = $statement->fetchAll();
        return $row;
    }
}