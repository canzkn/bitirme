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
    private $tables = ['questions', 'question_tags', 'users', 'answers'];

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Add a Question
    public function addQuestion()
    {
        // question query string
        $query = 'INSERT INTO '. $this->tables[0] .' (Title, Content, CreateDate, UpdateDate, UserID) VALUES (:Title, :Content, :CreateDate, :UpdateDate, :UserID)';
        // prepare statement
        $statement = $this->conn->prepare($query);
            
        // bind parameters
        $statement->bindParam(':Title', $this->getTitle());
        $statement->bindParam(':Content', $this->getContent());
        $statement->bindParam(':CreateDate', $this->getCreateDate());
        $statement->bindParam(':UpdateDate', $this->getCreateDate());
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

            return $LastQuestionID;
        }
        else
        {
            return $this->FAILED_CODE;
        }
    }

    // Get Last Questions
    public function getLastQuestions($filter, $page_id)
    {
        // question count per page
        $perPage = 10;
        
        if($filter == "hot")
        {
            // get total data
            $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . ' ORDER BY UpdateDate DESC';

            // Get Hot Question Query
            $QuestionQuery = 'SELECT * FROM ' . $this->tables[0] . ' ORDER BY UpdateDate DESC';
        }

        if($filter == "week")
        {
            // get total data
            $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . '  WHERE UpdateDate > DATE_SUB(NOW(), INTERVAL 1 WEEK) ORDER BY UpdateDate DESC';

            // Get Hot Question Query
            $QuestionQuery = 'SELECT * FROM ' . $this->tables[0] . '  WHERE UpdateDate > DATE_SUB(NOW(), INTERVAL 1 WEEK) ORDER BY UpdateDate DESC';
        }

        if($filter == "month")
        {
            // get total data
            $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . '  WHERE UpdateDate > DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY UpdateDate DESC';

            // Get Hot Question Query
            $QuestionQuery = 'SELECT * FROM ' . $this->tables[0] . '  WHERE UpdateDate > DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY UpdateDate DESC';
        }


        // prepare statement
        $statement = $this->conn->prepare($totalDataQuery);

        // execute query
        $statement->execute();
        $total_data = $statement->fetchColumn();

        // total page
        $total_page = ceil($total_data/$perPage);

        // current page
        $now = ($page_id * $perPage - $perPage);

        // prepare statement
        $statement = $this->conn->prepare($QuestionQuery . ' LIMIT ' . $now .',' .$perPage);
        // execute query
        $statement->execute();
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);

        $return_data = array();
        $return_data['total_page'] = $total_page;
        $return_data['data'] = $this->dataManipulation($row);

        return $return_data;
    }

    // Get All Question
    public function getAllQuestions($type, $filter, $page_id)
    {
        // question count per page
        $perPage = 10;

        // get new
        if($type == "new")
        {
             // get total data
             $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . ' ORDER BY CreateDate DESC';

             // Get New Question Query
             $QuestionQuery = 'SELECT * FROM ' . $this->tables[0] . ' ORDER BY CreateDate DESC';
        }

        // get active
        if($type == "active")
        {
             // get total data
             $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . ' ORDER BY UpdateDate DESC';

             // Get New Question Query
             $QuestionQuery = 'SELECT * FROM ' . $this->tables[0] . ' ORDER BY UpdateDate DESC';
        }

        // get unanswered
        if($type == "notanswer")
        {
             // get total data
             $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . ' WHERE AnswerUserID = 0 ORDER BY Reputation DESC';

             // Get New Question Query
             $QuestionQuery = 'SELECT * FROM ' . $this->tables[0] . ' WHERE AnswerUserID = 0 ORDER BY Reputation DESC';
        }

        if($type == "special")
        {
            /**
             * TODO : filtreleme işlemleri bu alanda yapılacak.
             */
        }

        // prepare statement
        $statement = $this->conn->prepare($totalDataQuery);

        // execute query
        $statement->execute();
        $total_data = $statement->fetchColumn();

        // total page
        $total_page = ceil($total_data/$perPage);

        // current page
        $now = ($page_id * $perPage - $perPage);

        // prepare statement
        $statement = $this->conn->prepare($QuestionQuery . ' LIMIT ' . $now .',' .$perPage);
        // execute query
        $statement->execute();
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);

        $return_data = array();
        $return_data['total_page'] = $total_page;
        $return_data['total_data'] = $total_data;
        $return_data['data'] = $this->dataManipulation($row);

        return $return_data;
    }

    // Data Manipulation
    public function dataManipulation($row)
    {
        for($i = 0; $i < count($row); $i++)
        {
            // get tags 
            $query = 'SELECT * FROM ' . $this->tables[1] . ' INNER JOIN tags ON question_tags.TagID = tags.TagID WHERE question_tags.QuestionID = :QuestionID';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind param
            $statement->bindParam(':QuestionID', $row[$i]['QuestionID']);
            // execute query
            $statement->execute();
            $tag_rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $row[$i]['Tags'] = $tag_rows;

            // insert username
            $query = 'SELECT Username FROM ' . $this->tables[2] . ' WHERE UserID = :UserID LIMIT 1';
            // prepare statement
            $statement = $this->conn->prepare($query);

            if($row[$i]['AnswerUserID'])
            {
                // answered

                // bind param
                $statement->bindParam(':UserID', $row[$i]['AnswerUserID']);
                // execute query
                $statement->execute();
                $username = $statement->fetchColumn();
                // set username
                $row[$i]['Username'] = $username;
                // set post date
                $row[$i]['PostDate'] = Functions::time_convert($row[$i]['UpdateDate']);
                $row[$i]['PostType'] = "cevaplandı";
            }
            else
            {
                // asked
                
                // bind param
                $statement->bindParam(':UserID', $row[$i]['UserID']);
                // execute query
                $statement->execute();
                $username = $statement->fetchColumn();
                // set username
                $row[$i]['Username'] = $username;
                // set post date
                $row[$i]['PostDate'] = Functions::time_convert($row[$i]['CreateDate']);
                $row[$i]['PostType'] = "soruldu";
            }

            $row[$i]['Content'] = htmlspecialchars(substr(strip_tags($row[$i]['Content']), 0, 296));

            // calculate answer count
            $query = 'SELECT COUNT(*) FROM ' . $this->tables[3] . ' WHERE QuestionID = :QuestionID LIMIT 1';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind param
            $statement->bindParam(':QuestionID', $row[$i]['QuestionID']);
            // execute query
            $statement->execute();
            $row[$i]['AnswerCount'] = $statement->fetchColumn();
        }

        return $row;
    }
}