<?php
/**
 * Conversation Operations Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class ConversationOperations extends Core\Conversation { 
    // Return Codes
    private $FAILED_CODE = 0;
    private $SUCCESS_CODE = 1;

    // DB Stuff
    private $conn;
    private $tables = ['conversation', 'conversation_messages', 'users'];

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Is Conversation Exist?
    public function isConversationExist()
    {
        // query string
        $query = 'SELECT ConversationID FROM ' . $this->tables[0] . ' WHERE (SenderID = :SenderID AND ReceiverID = :ReceiverID) OR (SenderID = :ReceiverID AND ReceiverID = :SenderID) LIMIT 1';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':SenderID', $this->getSenderID());
        $statement->bindParam(':ReceiverID', $this->getReceiverID());
        // execute query
        $statement->execute();
        $row = $statement->fetchColumn();

        return $row;
    }

    // Get Conversations
    public function getConversations()
    {
        // query string
        $query = 'SELECT * FROM ' . $this->tables[0] . ' WHERE SenderID = :SenderID OR ReceiverID = :SenderID ORDER BY MessageDate DESC';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':SenderID', $this->getSenderID());
        // execute query
        $statement->execute();
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);

        // data manipulation
        for($i=0; $i < count($row); $i++)
        {
            $row[$i]['LatestMessage'] = substr($row[$i]['LatestMessage'], 0, 50);
            if($row[$i]['SenderID'] == $this->getSenderID())
            {
                $TargetUserID = $row[$i]['ReceiverID'];
            }
            else
            {
                $TargetUserID = $row[$i]['SenderID'];
            }

            // get userdata
            $query = 'SELECT UserID, Username, Fullname, AvatarImage FROM ' . $this->tables[2] . ' WHERE UserID = :UserID LIMIT 1';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind parameters
            $statement->bindParam(':UserID', $TargetUserID);
            // execute query
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            $row[$i]['User'] = $user;
        }

        return $row;
    }

    // Get Conversation Messages
    public function getMessages()
    {
        if(!$this->isConversationExist())
        {
            return false;
        }

        // query string
        $query = 'SELECT * FROM ' . $this->tables[1] . ' WHERE ConversationID = :ConversationID';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':ConversationID', $this->isConversationExist());
        // execute query
        $statement->execute();
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    // Create a Conversation
    public function create()
    {
        // query string
        $query = 'INSERT INTO ' . $this->tables[0] . ' (SenderID, ReceiverID, MessageDate, LatestMessage, Status) VALUES (:SenderID, :ReceiverID, :MessageDate, :LatestMessage, 1)';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':SenderID', $this->getSenderID());
        $statement->bindParam(':ReceiverID', $this->getReceiverID());
        $statement->bindParam(':MessageDate', $this->getMessageDate());
        $statement->bindParam(':LatestMessage', $this->getMessage());

        // execute query
        if($statement->execute())
        {
            // conversation id
            $ConversationID = $this->conn->lastInsertId();
            return $ConversationID;
        }

        return false;
    }

    // Send Message
    public function sendMessage()
    {
        
        // conversation is exist?
        if(!$this->isConversationExist())
        {
            // create a conversation and set conversation id
            $this->setConversationID($this->create());
        }
        else
        {
            $this->setConversationID($this->isConversationExist());
        }

        // add message to conversation

        // query string
        $query = 'INSERT INTO ' . $this->tables[1] . ' (Message, SenderID, ReceiverID, ConversationID, MessageDate) VALUES (:Message, :SenderID, :ReceiverID, :ConversationID, :MessageDate)';
        // prepare statement
        $statement = $this->conn->prepare($query);
        // bind parameters
        $statement->bindParam(':Message', $this->getMessage());
        $statement->bindParam(':SenderID', $this->getSenderID());
        $statement->bindParam(':ReceiverID', $this->getReceiverID());
        $statement->bindParam(':ConversationID', $this->getConversationID());
        $statement->bindParam(':MessageDate', $this->getMessageDate());
        // execute query
        if($statement->execute())
        {
            // conversation id
            $MessageID = $this->conn->lastInsertId();
            // query string
            $query = 'UPDATE ' . $this->tables[0] . ' SET LatestMessage = :LatestMessage, MessageDate = :MessageDate WHERE ConversationID = :ConversationID';
            // prepare statement
            $statement = $this->conn->prepare($query);
            // bind parameters
            $statement->bindParam(':MessageDate', $this->getMessageDate());
            $statement->bindParam(':LatestMessage', $this->getMessage());
            $statement->bindParam(':ConversationID', $this->getConversationID());

            // execute query
            if($statement->execute())
            {
                // query string
                $query = 'SELECT * FROM ' . $this->tables[1] . ' WHERE MessageID = :MessageID';
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
}