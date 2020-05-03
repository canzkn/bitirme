<?php
/**
 * Profile Operations Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class Profile extends Core\User {
    // Error Codes
    private $FAILED_CODE = 0;
    private $SUCCESS_CODE = 1;

    // DB Stuff
    private $conn;
    private $tables = ['users', 'user_interest'];

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }
    
    // Add Interest
    public function addInterest($TagID)
    {
        // is added before?
        $query = 'SELECT COUNT(*) as inDb FROM ' . $this->tables[1] . ' WHERE UserID = :UserID AND TagID = :TagID LIMIT 1';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':UserID', $this->getUserID());
        $statement->bindParam(':TagID', $TagID);
        // execute query
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if(!$row['inDb'])
        {
            // create the user
            $query = 'INSERT INTO '. $this->tables[1] .' (UserID, TagID) VALUES (:UserID, :TagID)';

            // prepare statement
            $statement = $this->conn->prepare($query);
                
            // bind parameters
            $statement->bindParam(':UserID', $this->getUserID());
            $statement->bindParam(':TagID', $TagID);
            // execute query
            if($statement->execute())
            {
                return $this->SUCCESS_CODE;
            }
            else
            {
                return $this->FAILED_CODE;
            }
        }
    }

    // Update Interest Status
    public function setInterestStatus()
    {
        // create the user
        $query = 'UPDATE '. $this->tables[0] .' SET isInterest = 1 WHERE UserID = :UserID';

        // prepare statement
        $statement = $this->conn->prepare($query);
            
        // bind parameters
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        if($statement->execute())
        {
            return $this->SUCCESS_CODE;
        }
        else
        {
            return $this->FAILED_CODE;
        }
    }
}