<?php
/**
 * Core Group Conversation Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

namespace Core;

class GroupConversation {
    // group_conversation
    private $GroupID;
    private $GroupName;
    private $CreatorID;
    private $CreateDate;
    private $LatestMessage;
    private $LatestUserID;
    // group_conversation_messages
    private $MessageID;
    private $Message;
    private $SenderID;
    private $MessageDate;
    // group_conversation_users
    private $Users = array();
    private $UserID;

    /**
     * Get the value of GroupID
     */ 
    public function getGroupID()
    {
        return $this->GroupID;
    }

    /**
     * Set the value of GroupID
     *
     * @return  self
     */ 
    public function setGroupID($GroupID)
    {
        $this->GroupID = $GroupID;

        return $this;
    }

    /**
     * Get the value of GroupName
     */ 
    public function getGroupName()
    {
        return $this->GroupName;
    }

    /**
     * Set the value of GroupName
     *
     * @return  self
     */ 
    public function setGroupName($GroupName)
    {
        $this->GroupName = $GroupName;

        return $this;
    }

    /**
     * Get the value of CreatorID
     */ 
    public function getCreatorID()
    {
        return $this->CreatorID;
    }

    /**
     * Set the value of CreatorID
     *
     * @return  self
     */ 
    public function setCreatorID($CreatorID)
    {
        $this->CreatorID = $CreatorID;

        return $this;
    }

    /**
     * Get the value of CreateDate
     */ 
    public function getCreateDate()
    {
        return $this->CreateDate;
    }

    /**
     * Set the value of CreateDate
     *
     * @return  self
     */ 
    public function setCreateDate($CreateDate)
    {
        $this->CreateDate = $CreateDate;

        return $this;
    }

    /**
     * Get the value of LatestMessage
     */ 
    public function getLatestMessage()
    {
        return $this->LatestMessage;
    }

    /**
     * Set the value of LatestMessage
     *
     * @return  self
     */ 
    public function setLatestMessage($LatestMessage)
    {
        $this->LatestMessage = $LatestMessage;

        return $this;
    }

    /**
     * Get the value of LatestUserID
     */ 
    public function getLatestUserID()
    {
        return $this->LatestUserID;
    }

    /**
     * Set the value of LatestUserID
     *
     * @return  self
     */ 
    public function setLatestUserID($LatestUserID)
    {
        $this->LatestUserID = $LatestUserID;

        return $this;
    }

    /**
     * Get the value of MessageID
     */ 
    public function getMessageID()
    {
        return $this->MessageID;
    }

    /**
     * Set the value of MessageID
     *
     * @return  self
     */ 
    public function setMessageID($MessageID)
    {
        $this->MessageID = $MessageID;

        return $this;
    }

    /**
     * Get the value of Message
     */ 
    public function getMessage()
    {
        return $this->Message;
    }

    /**
     * Set the value of Message
     *
     * @return  self
     */ 
    public function setMessage($Message)
    {
        $this->Message = $Message;

        return $this;
    }

    /**
     * Get the value of SenderID
     */ 
    public function getSenderID()
    {
        return $this->SenderID;
    }

    /**
     * Set the value of SenderID
     *
     * @return  self
     */ 
    public function setSenderID($SenderID)
    {
        $this->SenderID = $SenderID;

        return $this;
    }

    /**
     * Get the value of MessageDate
     */ 
    public function getMessageDate()
    {
        return $this->MessageDate;
    }

    /**
     * Set the value of MessageDate
     *
     * @return  self
     */ 
    public function setMessageDate($MessageDate)
    {
        $this->MessageDate = $MessageDate;

        return $this;
    }

    /**
     * Get the value of UserID
     */ 
    public function getUserID()
    {
        return $this->UserID;
    }

    /**
     * Set the value of UserID
     *
     * @return  self
     */ 
    public function setUserID($UserID)
    {
        $this->UserID = $UserID;

        return $this;
    }

    /**
     * Get the value of Users
     */ 
    public function getUsers()
    {
        return $this->Users;
    }

    /**
     * Set the value of Users
     *
     * @return  self
     */ 
    public function setUsers($Users)
    {
        $this->Users = $Users;

        return $this;
    }
}