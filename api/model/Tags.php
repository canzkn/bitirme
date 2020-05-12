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
    private $tables = ['tags', 'questions', 'question_tags'];

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Get All Tags for Interest Page
    public function getTags()
    {
        // query string
        $query = 'SELECT * FROM ' . $this->tables[0] . ' ORDER BY TagName ASC';
        // prepare statement
        $statement = $this->conn->prepare($query);

        // execute query
        $statement->execute();
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
    // Tag Manipulation
    public function manipulation($row)
    {
        for($i = 0; $i < count($row); $i++)
        {
            // get question count for tags
            $query = 'SELECT COUNT(*) FROM ' . $this->tables[2] . ' WHERE TagID = :TagID';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind param
            $statement->bindParam(':TagID', $row[$i]['TagID']);
            // execute query
            $statement->execute();
            $questionCount = $statement->fetchColumn();
            $row[$i]['QuestionCount'] = $questionCount;

            // get today question count
            $query = 'SELECT COUNT(*) FROM ' . $this->tables[1] . ' INNER JOIN ' . $this->tables[2] . ' ON questions.QuestionID = question_tags.QuestionID WHERE DATE(`CreateDate`) = CURDATE() AND question_tags.TagID = :TagID';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind param
            $statement->bindParam(':TagID', $row[$i]['TagID']);
            // execute query
            $statement->execute();
            $todayQuestionCount = $statement->fetchColumn();
            $row[$i]['TodayCount'] = $todayQuestionCount;

            // get this week question count
            $query = 'SELECT COUNT(*) FROM ' . $this->tables[1] . ' INNER JOIN ' . $this->tables[2] . ' ON questions.QuestionID = question_tags.QuestionID WHERE CreateDate > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND question_tags.TagID = :TagID';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind param
            $statement->bindParam(':TagID', $row[$i]['TagID']);
            // execute query
            $statement->execute();
            $weekQuestionCount = $statement->fetchColumn();
            $row[$i]['WeekCount'] = $weekQuestionCount;
        }

        return $row;
    }
}