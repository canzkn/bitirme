<?php
/**
 * Database Connection Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class Database {
    // Database properties
    private $host       = "localhost";
    private $db_name    = "kavramapp";
    private $db_user    = "root";
    private $db_pass    = "";
    private $connection;

    // Connection function.
    public function connect()
    {
        $this->connection = null;

        try
        {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
            $this->connection->exec("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
            $this->connection->exec("SET CHARACTER SET 'utf8'");
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }
        catch(\Exception $e)
        {
            echo "<b>Connection Error:</b> " . $e->getMessage();
        }

        return $this->connection;
    }
}