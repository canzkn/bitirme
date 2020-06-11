<?php
/**
 * Group Conversation Operations Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class GroupConversationOperations extends Core\GroupConversation { 
    // Return Codes
    private $FAILED_CODE = 0;
    private $SUCCESS_CODE = 1;

    // DB Stuff
    private $conn;
    private $tables = ['group_conversation', 'group_conversation_messages', 'group_conversation_users'];

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // create group
    public function create()
    {
        // query string
        $query = 'INSERT INTO ' . $this->tables[0] . ' (GroupName, CreatorID, CreateDate) VALUES (:GroupName, :CreatorID, :CreateDate)';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':GroupName', $this->getGroupName());
        $statement->bindParam(':CreatorID', $this->getCreatorID());
        $statement->bindParam(':CreateDate', date("Y-m-d H:i:s"));

        // execute query
        if($statement->execute())
        {
            // conversation id
            $ConversationID = $this->conn->lastInsertId();

            foreach($this->getUsers() as $user_id)
            {
                // query string
                $query = 'INSERT INTO ' . $this->tables[2] . ' (GroupID, UserID) VALUES (:GroupID, :UserID)';
                // prepare statement
                $statement = $this->conn->prepare($query);
                // bind parameters
                $statement->bindParam(':GroupID', $ConversationID);
                $statement->bindParam(':UserID', $user_id);
                // execute query
                $statement->execute();
            }

            return $ConversationID;
        }

        return false;
    }

    // get groups for request user
    public function getGroups()
    {
        // query string
        $query = 'SELECT * FROM '.$this->tables[2].' INNER JOIN '.$this->tables[0].' ON '.$this->tables[0].'.GroupID = '.$this->tables[2].'.GroupID WHERE '.$this->tables[2].'.UserID = :UserID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':UserID', $this->getUserID());
        // execute query
        $statement->execute();
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    // Send Message
    public function sendMessage()
    {
        // query string
        $query = 'INSERT INTO ' . $this->tables[1] . ' (Message, GroupID, SenderID, MessageDate) VALUES (:Message, :GroupID, :SenderID, :MessageDate)';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':Message', $this->getMessage());
        $statement->bindParam(':SenderID', $this->getSenderID());
        $statement->bindParam(':GroupID', $this->getGroupID());
        $statement->bindParam(':MessageDate', $this->getMessageDate());
        // execute query
        if($statement->execute())
        {
            // conversation id
            $MessageID = $this->conn->lastInsertId();
            // query string
            $query = 'UPDATE ' . $this->tables[0] . ' SET LatestMessage = :LatestMessage, LatestUserID = :LatestUserID WHERE GroupID = :GroupID';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind parameters
            $statement->bindParam(':LatestUserID', $this->getSenderID());
            $statement->bindParam(':LatestMessage', $this->getMessage());
            $statement->bindParam(':GroupID', $this->getGroupID());

            // execute query
            if($statement->execute())
            {
                // query string
                $query = 'SELECT * FROM ' . $this->tables[1] . ' INNER JOIN users ON group_conversation_messages.SenderID = users.UserID WHERE MessageID = :MessageID';
                // prepare statement
                $statement = $this->conn->prepare($query);
                // bind parameters
                $statement->bindParam(':MessageID', $MessageID);
                // execute query
                $statement->execute();
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                return $row;
            }
        }
        return false;
    }

    // Get Conversation Messages
    public function getMessages()
    {
        // query string
        $query = 'SELECT * FROM ' . $this->tables[1] . ' INNER JOIN users ON group_conversation_messages.SenderID = users.UserID WHERE GroupID = :GroupID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':GroupID', $this->getGroupID());
        // execute query
        $statement->execute();
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }
}