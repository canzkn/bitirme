<?php
/**
 * Core Answer Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

namespace Core;

class Answer {
    // variables
    private $AnswerID;
    private $Content;
    private $Reputation;
    private $Status;
    private $UserID;
    private $QuestionID;
    private $CreateDate;

    /**
     * Get the value of AnswerID
     */ 
    public function getAnswerID()
    {
        return $this->AnswerID;
    }

    /**
     * Set the value of AnswerID
     *
     * @return  self
     */ 
    public function setAnswerID($AnswerID)
    {
        $this->AnswerID = $AnswerID;

        return $this;
    }

    /**
     * Get the value of Content
     */ 
    public function getContent()
    {
        return $this->Content;
    }

    /**
     * Set the value of Content
     *
     * @return  self
     */ 
    public function setContent($Content)
    {
        $this->Content = $Content;

        return $this;
    }

    /**
     * Get the value of Reputation
     */ 
    public function getReputation()
    {
        return $this->Reputation;
    }

    /**
     * Set the value of Reputation
     *
     * @return  self
     */ 
    public function setReputation($Reputation)
    {
        $this->Reputation = $Reputation;

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
     * Get the value of QuestionID
     */ 
    public function getQuestionID()
    {
        return $this->QuestionID;
    }

    /**
     * Set the value of QuestionID
     *
     * @return  self
     */ 
    public function setQuestionID($QuestionID)
    {
        $this->QuestionID = $QuestionID;

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
}