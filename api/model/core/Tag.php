<?php
/**
 * Core Tag Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

namespace Core;

class Tag {
    // variables
    private $TagID;
    private $TagName;
    private $TagInformation;

    /**
     * Get the value of TagID
     */ 
    public function getTagID()
    {
        return $this->TagID;
    }

    /**
     * Set the value of TagID
     *
     * @return  self
     */ 
    public function setTagID($TagID)
    {
        $this->TagID = $TagID;

        return $this;
    }

    /**
     * Get the value of TagName
     */ 
    public function getTagName()
    {
        return $this->TagName;
    }

    /**
     * Set the value of TagName
     *
     * @return  self
     */ 
    public function setTagName($TagName)
    {
        $this->TagName = $TagName;

        return $this;
    }

    /**
     * Get the value of TagInformation
     */ 
    public function getTagInformation()
    {
        return $this->TagInformation;
    }

    /**
     * Set the value of TagInformation
     *
     * @return  self
     */ 
    public function setTagInformation($TagInformation)
    {
        $this->TagInformation = $TagInformation;

        return $this;
    }
}