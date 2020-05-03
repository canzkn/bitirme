<?php
/**
 * Question Operations Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class QuestionOperations extends Core\Question {
    // Error Codes
    private $FAILED_CODE = 0;
    private $SUCCESS_CODE = 1;

    // DB Stuff
    private $conn;
    private $tables = ['questions', 'question_tags'];

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Add a Question
    public function addQuestion()
    {
        // question query string
        $query = 'INSERT INTO '. $this->tables[0] .' (Title, Content, CreateDate, UserID) VALUES (:Title, :Content, :CreateDate, :UserID)';
        // prepare statement
        $statement = $this->conn->prepare($query);
            
        // bind parameters
        $statement->bindParam(':Title', $this->getTitle());
        $statement->bindParam(':Content', $this->getContent());
        $statement->bindParam(':CreateDate', $this->getCreateDate());
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        if($statement->execute())
        {
            // question id
            $LastQuestionID = $this->conn->lastInsertId();

            // add tag query
            $query = 'INSERT INTO '. $this->tables[1] .' (QuestionID, TagID) VALUES (:QuestionID, :TagID)';
            // define tags
            $tags = $this->getTags();
            foreach($tags as $tag)
            {
                // prepare statement
                $statement = $this->conn->prepare($query);
                // bind parameters
                $statement->bindParam(':QuestionID', $LastQuestionID);
                $statement->bindParam(':TagID', $tag->value);

                // execute query
                $statement->execute();
            }

            return $this->SUCCESS_CODE;
        }
        else
        {
            return $this->FAILED_CODE;
        }
    }
}