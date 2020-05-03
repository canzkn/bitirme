<?php
/**
 * Authentication Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class Authentication extends Core\User {
    // Error Codes
    private $FAILED_CODE = 0;
    private $SUCCESS_CODE = 1;
    private $BLANK_CODE = 2;
    private $AVAILABLE_CODE = 3;
    private $ALREADY_LOGGED = 4;
    private $TOKEN_CREATE_FAILED = 5;
    private $INVALID_EMAIL_FORMAT = 6;

    // Access Token
    private $token;

    // DB Stuff
    private $conn;
    private $table = 'users';

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Set Token
    public function setAccessToken($token)
    {
        $this->token = $token;

        return $this;
    }

    // Get Token
    public function getAccessToken()
    {
        return $this->token;
    }

    // Delete previous tokens
    public function deletePreviousTokens()
    {
        $query = 'DELETE FROM tokens WHERE TokenUserID = :TokenUserID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':TokenUserID', $this->getUserID());
        // execute
        if($statement->execute())
        {
            return true;
        }
        
        return false;
    }

    // Create user
    public function create()
    {
        // check blank fields.
        if( !$this->getUsername() || !$this->getPassword() || !$this->getEmail() )
        {
            return $this->BLANK_CODE;
        }

        if(!Functions::checkEmail($this->getUEmail()))
        {
            return $this->INVALID_EMAIL_FORMAT;
        }
       
        // is there a user in the db?
        $query = 'SELECT COUNT(*) as inDb FROM ' . $this->table . ' WHERE Username = :Username OR Email = :Email LIMIT 1';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':Username', $this->getUsername());
        $statement->bindParam(':Email', $this->getEmail());
        // execute query
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if($row['inDb'])
        {
            return $this->AVAILABLE_CODE;
        }
        else
        {
            // create the user
            $query = 'INSERT INTO '. $this->table .' (Username, Email, Password, RegisterDate) VALUES (:Username, :Email, :Password, :RegisterDate)';
            
            // prepare statement
            $statement = $this->conn->prepare($query);
                
            // bind parameters
            $statement->bindParam(':Username', $this->getUsername());
            $statement->bindParam(':Email', $this->getEmail());
            $statement->bindParam(':RegisterDate', $this->getRegisterDate());
            $statement->bindParam(':Password', Functions::hashPassword($this->getPassword()));
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
        return $this;
    }

    // Login
    public function login()
    {
        // check blank fields.
        if(!$this->getUsername() || !$this->getPassword())
        {
            return $this->BLANK_CODE;
        }
        // check login status and valid token.
        if($this->checkLogin())
        {
            return $this->ALREADY_LOGGED;
        }
        // login
        $query = 'SELECT COUNT(*) as result FROM ' . $this->table . ' WHERE (Username = :Username OR Email = :Email) AND Password = :Password LIMIT 1';
        
        
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':Username', $this->getUsername());
        $statement->bindParam(':Email', $this->getUsername());
        $statement->bindParam(':Password', Functions::hashPassword($this->getPassword()));
        // execute query
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        
        if($row['result'] > 0)
        {
            // Get User ID
            $query = 'SELECT UserID FROM ' . $this->table . ' WHERE Username = :Username OR Email = :Email LIMIT 1';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind parameters
            $statement->bindParam(':Username', $this->getUsername());
            $statement->bindParam(':Email', $this->getUsername());
            // execute query
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            
            // set user id
            $this->setUserID($row['UserID']);

            // set token
            $token = Functions::createToken();
            $tokenEndTime = 3600 * 24; // seconds 
            $tokenEndDate = date('Y-m-d H:i:s', time() + $tokenEndTime);
            $this->setAccessToken($token);

            // delete previous tokens
            $this->deletePreviousTokens();

            // insert token to database
            $query = 'INSERT INTO tokens (Token, TokenUserID, TokenEndDate) VALUES (:Token, :TokenUserID, :TokenEndDate)';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind parameters
            $statement->bindParam(':Token', $this->getAccessToken());
            $statement->bindParam(':TokenUserID', $this->getUserID());
            $statement->bindParam(':TokenEndDate', $tokenEndDate);
            // execute query
            if($statement->execute())
            {
                return $this->SUCCESS_CODE;
            }
            else
            {
                return $this->TOKEN_CREATE_FAILED;
            }
        }

        return $this->FAILED_CODE;
    }

    // Check Login
    public function checkLogin()
    {
        if($this->getAccessToken())
        {
            // check token end date
            $query = 'SELECT TokenEndDate FROM tokens WHERE TokenUserID = :TokenUserID AND Token = :Token';
            // prepare statement
            $statement = $this->conn->prepare($query);
                
            // bind parameters
            $statement->bindParam(':TokenUserID', $this->getUserID());
            $statement->bindParam(':Token', $this->getAccessToken());
            // execute query
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            $TokenEndDate = $row['TokenEndDate'];
            if(time() > strtotime($TokenEndDate))
            {
                return false;
            }
            return true;
        }
        return false;
    }

    // Logout
    public function logout()
    {
        // delete previous tokens
        if($this->deletePreviousTokens())
        {
            return $this->SUCCESS_CODE;
        }
        
        return $this->FAILED_CODE;
    }

    // Get Username from Database
    public function getDBUsername()
    {
        // query string
        $query = 'SELECT Username FROM ' . $this->table . ' WHERE UserID = :UserID';

        // prepare statement
        $statement = $this->conn->prepare($query);
                
        // bind parameters
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        
        return $row['Username'];
    }

    // Get Email from Database
    public function getDBEmail()
    {
        // query string
        $query = 'SELECT Email FROM ' . $this->table . ' WHERE UserID = :UserID';

        // prepare statement
        $statement = $this->conn->prepare($query);
                
        // bind parameters
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        
        return $row['Email'];
    }

    // is Interest Filled?
    public function isInterest()
    {
        // query string
        $query = 'SELECT isInterest FROM ' . $this->table . ' WHERE UserID = :UserID';

        // prepare statement
        $statement = $this->conn->prepare($query);
                
        // bind parameters
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        
        return $row['isInterest'];
    }
}