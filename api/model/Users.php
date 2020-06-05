<?php
/**
 * User Operations Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class UserOperations extends Core\User {
    // Error Codes
    private $FAILED_CODE = 0;
    private $SUCCESS_CODE = 1;

    // DB Stuff
    private $conn;
    private $tables = ['users', 'questions', 'answers', 'question_tags', 'tags'];

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Get All Users
    public function getAllUsers($sort)
    {
        if($sort == "popular")
        {
            $sortQuery = ' ORDER BY Reputation DESC';
        }
        elseif($sort == "asc")
        {
            $sortQuery = ' ORDER BY Fullname ASC';
        }
        elseif($sort == "new")
        {
            $sortQuery = ' ORDER BY RegisterDate DESC';
        }

        // query string
        $query = 'SELECT * FROM ' . $this->tables[0] . $sortQuery;
        // prepare statement
        $statement = $this->conn->prepare($query);
        // execute query
        $statement->execute();
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($row); $i++)
        {
            unset($row[$i]['Password']);
        }
        
        return $row;
    }

    // Get Single User
    public function getUser($filter)
    {
        // query string
        $query = 'SELECT * FROM ' . $this->tables[0] .' WHERE UserID = :UserID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind param
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        unset($row['Password']); // not necessary

        // start manipulation
        $row['RegisterDate'] = Functions::time_convert($row['RegisterDate']);
        $row['LastSeen'] = Functions::time_convert($row['LastSeen']);

        // ask count
        // query string
        $query = 'SELECT COUNT(*) FROM ' . $this->tables[1] .' WHERE UserID = :UserID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind param
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $qCount = $statement->fetchColumn();
        $row['AskedCount'] = $qCount;

        // answer count
        // query string
        $query = 'SELECT COUNT(*) FROM ' . $this->tables[2] .' WHERE UserID = :UserID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind param
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $aCount = $statement->fetchColumn();
        $row['AnswerCount'] = $aCount;

        // get all asked question id
        // query string
        $query = 'SELECT QuestionID FROM ' . $this->tables[1] .' WHERE UserID = :UserID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind param
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $AskedQuestions = $statement->fetchAll(PDO::FETCH_ASSOC);
        $AskedQuestionIDs = array();

        foreach($AskedQuestions as $data)
        {
            $AskedQuestionIDs[] = $data['QuestionID'];
        }

        // get all answered question id
        // query string
        $query = 'SELECT DISTINCT QuestionID FROM ' . $this->tables[2] .' WHERE UserID = :UserID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind param
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $AnsweredQuestions = $statement->fetchAll(PDO::FETCH_ASSOC);
        $AnsweredQuestionIDs = array();

        foreach($AnsweredQuestions as $data)
        {
            $AnsweredQuestionIDs[] = $data['QuestionID'];
        }


        // filter operation
        if($filter->sort == "popular")
        {
            $filterSort = ' ORDER BY Reputation DESC';
        }
        else
        {
            $filterSort = ' ORDER BY CreateDate DESC';
        }

        if($filter->type == "all")
        {
            $QuestionIDs = array_merge($AskedQuestionIDs, $AnsweredQuestionIDs);
        }
        elseif($filter->type == "asked")
        {
            $QuestionIDs = $AskedQuestionIDs;
        }
        elseif($filter->type == "answered")
        {
            $QuestionIDs = $AnsweredQuestionIDs;
        }

        $QuestionIDQueryString = '(' . implode(',', $QuestionIDs) .')';
        $TagQueryString = '(' . implode(',', array_merge($AskedQuestionIDs, $AnsweredQuestionIDs)) .')';

        // questions operation

        // get total data question
        $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[1] . '  WHERE QuestionID IN ' . $QuestionIDQueryString;
        // prepare statement
        $statement = $this->conn->prepare($totalDataQuery);
        // execute query
        $statement->execute();
        $QuestionCount = $statement->fetchColumn();
        if($QuestionCount == false)
        {
            $QuestionCount = 0;
        }
        $row['QuestionCount'] = $QuestionCount;
        // fetch questions
        $query = 'SELECT QuestionID, Title, Reputation, Status, UpdateDate FROM ' . $this->tables[1] . ' WHERE QuestionID IN ' . $QuestionIDQueryString . $filterSort;
        // prepare statement
        $statement = $this->conn->prepare($query);
        // execute query
        $statement->execute();
        $fetchQuestions = $statement->fetchAll(PDO::FETCH_ASSOC);

        for($i=0; $i<count($fetchQuestions); $i++)
        {
            $fetchQuestions[$i]['UpdateDateString'] = Functions::time_convert2($fetchQuestions[$i]['UpdateDate']);
        }

        $row['Questions'] = $fetchQuestions;

        // fetch tags
        $query = 'SELECT DISTINCT tags.TagID, tags.TagName FROM question_tags INNER JOIN tags ON question_tags.TagID = tags.TagID WHERE QuestionID IN ' . $TagQueryString;
        // prepare statement
        $statement = $this->conn->prepare($query);
        // execute query
        $statement->execute();
        $fetchQuestions = $statement->fetchAll(PDO::FETCH_ASSOC);
        $row['TagCount'] = count($fetchQuestions);
        $row['Tags'] = $fetchQuestions;
        return $row;
    }
}