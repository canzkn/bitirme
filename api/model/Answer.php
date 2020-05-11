<?php
/**
 * Answer Operations Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class AnswerOperations extends Core\Answer {
    // Error Codes
    private $FAILED_CODE = 0;
    private $SUCCESS_CODE = 1;
    private $NOT_FOUND = 404;
    private $REPUTED_BEFORE = 5;

    // DB Stuff
    private $conn;
    private $tables = ['questions', 'users', 'answers', 'reputation_log'];

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Add Answer to Question
    public function addAnswer()
    {
        // question query string
        $query = 'INSERT INTO '. $this->tables[2] .' (Content, UserID, QuestionID, CreateDate) VALUES (:Content, :UserID, :QuestionID, :CreateDate)';
        // prepare statement
        $statement = $this->conn->prepare($query);
            
        // bind parameters
        $statement->bindParam(':Content', $this->getContent());
        $statement->bindParam(':UserID', $this->getUserID());
        $statement->bindParam(':QuestionID', $this->getQuestionID());
        $statement->bindParam(':CreateDate', $this->getCreateDate());
        // execute query
        if($statement->execute())
        {
            $updateQuery = 'UPDATE '. $this->tables[0] .' SET UpdateDate = :UpdateDate, AnswerUserID = :AnswerUserID WHERE QuestionID = :QuestionID';
            // prepare statement
            $statement = $this->conn->prepare($updateQuery);
            // bind parameters
            $statement->bindParam(':AnswerUserID', $this->getUserID());
            $statement->bindParam(':QuestionID', $this->getQuestionID());
            $statement->bindParam(':UpdateDate', $this->getCreateDate());
            $statement->execute();

            return $this->SUCCESS_CODE;
        }
        else
        {
            return $this->FAILED_CODE;
        }
    }

    // Increase Answer
    public function numericalOperations($type, $column)
    {
        // get total data
        $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[2] . '  WHERE AnswerID = :AnswerID LIMIT 1';

        // prepare statement
        $statement = $this->conn->prepare($totalDataQuery);
        // bind param
        $statement->bindParam(':AnswerID', $this->getAnswerID());
        // execute query
        $statement->execute();
        $total_data = $statement->fetchColumn();

        if(!$total_data)
        {
            return $this->NOT_FOUND;
        }

        // reputed before?
        $reputedQuery = 'SELECT COUNT(*) FROM ' . $this->tables[3] . ' WHERE ID = :AnswerID AND UserID = :UserID AND Type = \'answer\' LIMIT 1';
        
        // prepare statement
        $statement = $this->conn->prepare($reputedQuery);
        // bind param
        $statement->bindParam(':AnswerID', $this->getAnswerID());
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $reputed = $statement->fetchColumn();
        
        if($reputed > 0)
        {
            return $this->REPUTED_BEFORE;
        }


        if($type == "increase")
        {
            $operator = '+ 1';
        }
        
        if($type == "descrease")
        {
            $operator = '- 1';
        }

        // Get Answer Query
        $QuestionQuery = 'UPDATE ' . $this->tables[2] . ' SET '.$column.' = ' .$column . ' '. $operator . ' WHERE AnswerID = :AnswerID';
        
        // prepare statement
        $statement = $this->conn->prepare($QuestionQuery);
        // bind param
        $statement->bindParam(':AnswerID', $this->getAnswerID());
        // execute query
        if($statement->execute())
        {
            // reputating
            $reputedQuery = 'INSERT INTO ' . $this->tables[3] . ' (ID, Type, CreaseType, UserID, ReputationDate) VALUES (:ID, \'answer\', :CreaseType, :UserID, :ReputationDate)';
            
            // prepare statement
            $statement = $this->conn->prepare($reputedQuery);
            // bind param
            $statement->bindParam(':ID', $this->getAnswerID());
            $statement->bindParam(':CreaseType', $type);
            $statement->bindParam(':UserID', $this->getUserID());
            $statement->bindParam(':ReputationDate', date("Y-m-d H:i:s"));
            // execute query
            $statement->execute();
            return $this->SUCCESS_CODE;
        }

        return $this->FAILED_CODE;
    }

    // mark solved answer
    public function markSolved()
    {
        // query string
        $query = 'UPDATE ' . $this->tables[2] . ' SET Status = 1 WHERE AnswerID = :AnswerID AND QuestionID = :QuestionID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind param
        $statement->bindParam(':AnswerID', $this->getAnswerID());
        $statement->bindParam(':QuestionID', $this->getQuestionID());
        // execute query
        if($statement->execute())
        {
            // query string
            $query = 'UPDATE ' . $this->tables[0] . ' SET Status = 1 WHERE QuestionID = :QuestionID';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind param
            $statement->bindParam(':QuestionID', $this->getQuestionID());
            $statement->execute();

            // Add 100 Point to Answer User
            // query string
            $query = 'UPDATE ' . $this->tables[1] . ' SET Reputation = Reputation + 100 WHERE UserID = :UserID';
            // prepare statement
            $statement = $this->conn->prepare($query);
            //bind param and execute 
            $statement->bindParam(':UserID', $this->getUserIDfromAnswerID());
            $statement->execute();
            return $this->SUCCESS_CODE;
        }

        return $this->FAILED_CODE;
    }

    // get UserID from AnswerID
    public function getUserIDfromAnswerID()
    {
        // query string
        $query = 'SELECT UserID FROM ' . $this->tables[2] . ' WHERE AnswerID = :AnswerID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind param
        $statement->bindParam(':AnswerID', $this->getAnswerID());
        // execute query
        $statement->execute();

        $UserID = $statement->fetchColumn();

        return $UserID;
    }
}