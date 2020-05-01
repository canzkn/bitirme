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
    private $host       = "srvc14.turhost.com";
    private $db_name    = "canozkan_kavramapp";
    private $db_user    = "canozkan_kavrama";
    private $db_pass    = "4eD!e!5K";
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