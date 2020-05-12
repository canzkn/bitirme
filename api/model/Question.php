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
    private $NOT_FOUND = 404;
    private $REPUTED_BEFORE = 5;

    // DB Stuff
    private $conn;
    private $tables = ['questions', 'question_tags', 'users', 'answers', 'user_interest', 'tags', 'reputation_log'];

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

            

            $filterQueries = array();
            $unanswered = $filter->unanswered; // +
            $acceptedanswer = $filter->acceptedanswer; // +
            $sortCheck = $filter->sortCheck; // +
            $followCheck = $filter->followCheck; //+
            $tags = $filter->tags; // +

            // unanswered check
            if($unanswered)
            {
                $unansweredQuery = 'AnswerUserID = 0';
                $filterQueries[] = $unansweredQuery;
            }

            // acceptedanswer check
            if($acceptedanswer)
            {
                $acceptedanswerQuery = 'Status = 0';
                $filterQueries[] = $acceptedanswerQuery;
            }

            // sort query
            if($sortCheck == 0)
            {
                $sortQuery = 'ORDER BY CreateDate DESC';
            }
            elseif($sortCheck == 1)
            {
                $sortQuery = 'ORDER BY UpdateDate DESC';
            }
            elseif($sortCheck == 2)
            {
                $sortQuery = 'ORDER BY Reputation DESC';
            }
            elseif($sortCheck == 3)
            {
                $sortQuery = 'ORDER BY View DESC';
            }
            else
            {
                $sortQuery = '';
            }

            // follow check
            if($followCheck == 0)
            {
                // get my follow tags
                $myFollowQuery = 'SELECT TagID FROM ' .  $this->tables[4] . ' WHERE UserID = :UserID';
                // prepare statement
                $statement = $this->conn->prepare($myFollowQuery);
                // bind param
                $statement->bindParam(':UserID', $this->getUserID());
                // execute query
                $statement->execute();
                $datas = $statement->fetchAll(PDO::FETCH_ASSOC);
                
                $myTags = array();
                foreach($datas as $data)
                {
                    $myTags[] = $data['TagID'];
                }
                // get questions id by my tags
                $myFollowQuestionQuery = 'SELECT DISTINCT QuestionID FROM ' . $this->tables[1] . ' WHERE TagID in (' . implode(',', $myTags) . ')';
                           
                // prepare statement
                $statement = $this->conn->prepare($myFollowQuestionQuery);
                // execute query
                $statement->execute();
                $datas = $statement->fetchAll(PDO::FETCH_ASSOC);
                $QuestionIds = array();
                foreach($datas as $data)
                {
                    $QuestionIds[] = $data['QuestionID'];
                }

                $followCheckQuery = 'QuestionID in (' . implode(',', $QuestionIds) . ')';
            }
            elseif($followCheck == 1)
            {
                $myTags = array();
                foreach($tags as $tag)
                {
                    $myTags[] = $tag->value;
                }
                // get questions id by my tags
                $myFollowQuestionQuery = 'SELECT DISTINCT QuestionID FROM ' . $this->tables[1] . ' WHERE TagID in (' . implode(',', $myTags) . ')';
                // prepare statement
                $statement = $this->conn->prepare($myFollowQuestionQuery);
                // execute query
                $statement->execute();
                $datas = $statement->fetchAll(PDO::FETCH_ASSOC);
                $QuestionIds = array();
                foreach($datas as $data)
                {
                    $QuestionIds[] = $data['QuestionID'];
                }

                $followCheckQuery = 'QuestionID in (' . implode(',', $QuestionIds) . ')';
            }
            else
            {
                $followCheckQuery = ''; 
            }

            if($followCheckQuery)
            {
                $filterQueries[] = $followCheckQuery;
            }

            if(count($filterQueries) > 0)
            {
                $filterQueryCondition = 'WHERE ' . implode(' AND ', $filterQueries);
            }
            else
            {
                $filterQueryCondition = '';
            }

            // get total data
            $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . ' ' .  $filterQueryCondition . ' ' . $sortQuery;

            // Get New Question Query
            $QuestionQuery = 'SELECT * FROM ' . $this->tables[0] . ' ' .  $filterQueryCondition . ' ' . $sortQuery;
            
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
        if($total_data == false)
        {
            $total_data = 0;
        }
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

    // Answer Manipulation
    public function answerManipulation($row)
    {
        for($i = 0; $i < count($row); $i++)
        {
            $row[$i]['CreateDateString'] = Functions::time_convert2($row[$i]['CreateDate']);

            // insert username
            $query = 'SELECT Username, Fullname, AvatarImage, Reputation FROM ' . $this->tables[2] . ' WHERE UserID = :UserID LIMIT 1';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind param
            $statement->bindParam(':UserID', $row[$i]['UserID']);
            // execute query
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            $user['UserID'] = $row[$i]['UserID'];
            $row[$i]['User'] = $user;
            unset($row[$i]['UserID']);

        }

        return $row;
    }

    // Get Single Question
    public function getQuestion()
    {
        // get total data
        $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . '  WHERE QuestionID = :QuestionID LIMIT 1';

        // prepare statement
        $statement = $this->conn->prepare($totalDataQuery);
        // bind param
        $statement->bindParam(':QuestionID', $this->getQuestionID());
        // execute query
        $statement->execute();
        $total_data = $statement->fetchColumn();

        if(!$total_data)
        {
            return $this->NOT_FOUND;
        }

        // Get Hot Question Query
        $QuestionQuery = 'SELECT * FROM ' . $this->tables[0] . '  WHERE QuestionID = :QuestionID LIMIT 1';
        // prepare statement
        $statement = $this->conn->prepare($QuestionQuery);
        // bind param
        $statement->bindParam(':QuestionID', $this->getQuestionID());
        // execute query
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        // question manipulation
        $result['CreateDateString'] = Functions::time_convert2($result['CreateDate']);
        $result['CreateDate'] = Functions::time_convert($result['CreateDate']);
        $result['UpdateDate'] = Functions::time_convert($result['UpdateDate']);
        

        // unset($result['Status']);
        unset($result['AnswerUserID']);

        // get user info
        $userQuery = 'SELECT UserID, Fullname, AvatarImage, Reputation FROM ' . $this->tables[2] . '  WHERE UserID = :UserID LIMIT 1';
        // prepare statement
        $statement = $this->conn->prepare($userQuery);
        // bind param
        $statement->bindParam(':UserID', $result['UserID']);
        // execute query
        $statement->execute();
        $user_data = $statement->fetch(PDO::FETCH_ASSOC);
        $result['User'] = $user_data;
        unset($result['UserID']);

        // get tags
        $tagsQuery = 'SELECT tags.TagID, tags.TagName FROM ' . $this->tables[1] . ' INNER JOIN '. $this->tables[5] . ' ON question_tags.TagID = tags.TagID WHERE question_tags.QuestionID = :QuestionID';
        // prepare statement
        $statement = $this->conn->prepare($tagsQuery);
        // bind param
        $statement->bindParam(':QuestionID', $this->getQuestionID());
        // execute query
        $statement->execute();
        $tags_data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $result['Tags'] = $tags_data;

        //get answers
        $totalAnswersQuery = 'SELECT COUNT(*) FROM ' . $this->tables[3] . ' WHERE QuestionID = :QuestionID';
        // prepare statement
        $statement = $this->conn->prepare($totalAnswersQuery);
        // bind param
        $statement->bindParam(':QuestionID', $this->getQuestionID());
        // execute query
        $statement->execute();
        $answerCount = $statement->fetchColumn();
        $result['AnswerCount'] = $answerCount;
        $answers = array();
        if($answerCount > 0)
        {
            if($result['Status'] > 0)
            {
                // get solved answer first
                $solvedAnswerQuery = 'SELECT * FROM ' . $this->tables[3] . ' WHERE QuestionID = :QuestionID AND Status > 0';
                // prepare statement
                $statement = $this->conn->prepare($solvedAnswerQuery);
                // bind param
                $statement->bindParam(':QuestionID', $this->getQuestionID());
                // execute query
                $statement->execute();
                $solvedAnswer = $statement->fetch(PDO::FETCH_ASSOC);
                
                $AnswersQuery = 'SELECT * FROM ' . $this->tables[3] . ' WHERE QuestionID = :QuestionID AND AnswerID NOT IN ('.$solvedAnswer['AnswerID'].') ORDER BY CreateDate DESC';
                // prepare statement
                $statement = $this->conn->prepare($AnswersQuery);
                // bind param
                $statement->bindParam(':QuestionID', $this->getQuestionID());
                // execute query
                $statement->execute();
                $others = $statement->fetchAll(PDO::FETCH_ASSOC);
                $answers = $others;
                array_unshift($answers, $solvedAnswer);
            }
            else
            {
                $AnswersQuery = 'SELECT * FROM ' . $this->tables[3] . ' WHERE QuestionID = :QuestionID ORDER BY CreateDate DESC';
                // prepare statement
                $statement = $this->conn->prepare($AnswersQuery);
                // bind param
                $statement->bindParam(':QuestionID', $this->getQuestionID());
                // execute query
                $statement->execute();
                $others = $statement->fetchAll(PDO::FETCH_ASSOC);
                $answers = $others;
            }
        }

        $answers = $this->answerManipulation($answers);
        
        
        $result['Answers'] = $answers;
        
        return $result;
    }

    // Increase Question
    public function numericalOperations($type, $column)
    {
        // get total data
        $totalDataQuery = 'SELECT COUNT(*) FROM ' . $this->tables[0] . '  WHERE QuestionID = :QuestionID LIMIT 1';

        // prepare statement
        $statement = $this->conn->prepare($totalDataQuery);
        // bind param
        $statement->bindParam(':QuestionID', $this->getQuestionID());
        // execute query
        $statement->execute();
        $total_data = $statement->fetchColumn();

        if(!$total_data)
        {
            return $this->NOT_FOUND;
        }

        // reputed before?
        $reputedQuery = 'SELECT COUNT(*) FROM ' . $this->tables[6] . ' WHERE ID = :QuestionID AND UserID = :UserID AND Type = \'question\' LIMIT 1';
        
        // prepare statement
        $statement = $this->conn->prepare($reputedQuery);
        // bind param
        $statement->bindParam(':QuestionID', $this->getQuestionID());
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

        // Get Hot Question Query
        $QuestionQuery = 'UPDATE ' . $this->tables[0] . ' SET '.$column.' = ' .$column . ' '. $operator . ' WHERE QuestionID = :QuestionID';
        
        // prepare statement
        $statement = $this->conn->prepare($QuestionQuery);
        // bind param
        $statement->bindParam(':QuestionID', $this->getQuestionID());
        // execute query
        if($statement->execute())
        {
            // reputating
            $reputedQuery = 'INSERT INTO ' . $this->tables[6] . ' (ID, Type, CreaseType, UserID, ReputationDate) VALUES (:ID, \'question\', :CreaseType, :UserID, :ReputationDate)';
            
            // prepare statement
            $statement = $this->conn->prepare($reputedQuery);
            // bind param
            $statement->bindParam(':ID', $this->getQuestionID());
            $statement->bindParam(':CreaseType', $type);
            $statement->bindParam(':UserID', $this->getUserID());
            $statement->bindParam(':ReputationDate', date("Y-m-d H:i:s"));
            // execute query
            $statement->execute();
            return $this->SUCCESS_CODE;
        }

        return $this->FAILED_CODE;
    }
}