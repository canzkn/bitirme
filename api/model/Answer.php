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

    // DB Stuff
    private $conn;
    private $tables = ['questions', 'users', 'answers'];

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
            return $this->SUCCESS_CODE;
        }
        else
        {
            return $this->FAILED_CODE;
        }
    }
}