<?php
/**
 * Core Question Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

namespace Core;

class Question {
    // variables
    private $QuestionID;
    private $Title;
    private $Content;
    private $View;
    private $Status;
    private $CreateDate;
    private $UserID;
    private $Reputation;
    private $UpdateDate;
    private $AnswerUserID;
    private $tags = array();

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
     * Get the value of Title
     */ 
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * Set the value of Title
     *
     * @return  self
     */ 
    public function setTitle($Title)
    {
        $this->Title = $Title;

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
     * Get the value of View
     */ 
    public function getView()
    {
        return $this->View;
    }

    /**
     * Set the value of View
     *
     * @return  self
     */ 
    public function setView($View)
    {
        $this->View = $View;

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
     * Get the value of UpdateDate
     */ 
    public function getUpdateDate()
    {
        return $this->UpdateDate;
    }

    /**
     * Set the value of UpdateDate
     *
     * @return  self
     */ 
    public function setUpdateDate($UpdateDate)
    {
        $this->UpdateDate = $UpdateDate;

        return $this;
    }

    /**
     * Get the value of AnswerUserID
     */ 
    public function getAnswerUserID()
    {
        return $this->AnswerUserID;
    }

    /**
     * Set the value of AnswerUserID
     *
     * @return  self
     */ 
    public function setAnswerUserID($AnswerUserID)
    {
        $this->AnswerUserID = $AnswerUserID;

        return $this;
    }

    /**
     * Get the value of tags
     */ 
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set the value of tags
     *
     * @return  self
     */ 
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }
}