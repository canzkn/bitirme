<?php
/**
 * Core Conversation Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

namespace Core;

class Conversation {
    // variables
    private $ConversationID;
    private $SenderID;
    private $ReceiverID;
    private $MessageDate;
    private $LatestMessage;
    private $Status;
    // message variables
    private $MessageID;
    private $Message;
   

    /**
     * Get the value of ConversationID
     */ 
    public function getConversationID()
    {
        return $this->ConversationID;
    }

    /**
     * Set the value of ConversationID
     *
     * @return  self
     */ 
    public function setConversationID($ConversationID)
    {
        $this->ConversationID = $ConversationID;

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
     * Get the value of ReceiverID
     */ 
    public function getReceiverID()
    {
        return $this->ReceiverID;
    }

    /**
     * Set the value of ReceiverID
     *
     * @return  self
     */ 
    public function setReceiverID($ReceiverID)
    {
        $this->ReceiverID = $ReceiverID;

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
     * Get the value of Status
     */ 
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * Set the value of Status
     *
     * @return  self
     */ 
    public function setStatus($Status)
    {
        $this->Status = $Status;

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
}