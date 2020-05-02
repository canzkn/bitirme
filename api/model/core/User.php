<?php
/**
 * Core User Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

namespace Core;

class User {

    // User Properties
    private $UserID;
    private $Username;
    private $Email;
    private $Password;
    private $Fullname;
    private $Information;
    private $Address;
    private $RegisterDate;
    private $LastSeen;
    private $ProfileViews;
    private $AvatarImage;
    private $CoverImage;

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
     * Get the value of Username
     */ 
    public function getUsername()
    {
        return $this->Username;
    }

    /**
     * Set the value of Username
     *
     * @return  self
     */ 
    public function setUsername($Username)
    {
        $this->Username = $Username;

        return $this;
    }

    /**
     * Get the value of Email
     */ 
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * Set the value of Email
     *
     * @return  self
     */ 
    public function setEmail($Email)
    {
        $this->Email = $Email;

        return $this;
    }

    /**
     * Get the value of Password
     */ 
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * Set the value of Password
     *
     * @return  self
     */ 
    public function setPassword($Password)
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * Get the value of Fullname
     */ 
    public function getFullname()
    {
        return $this->Fullname;
    }

    /**
     * Set the value of Fullname
     *
     * @return  self
     */ 
    public function setFullname($Fullname)
    {
        $this->Fullname = $Fullname;

        return $this;
    }

    /**
     * Get the value of Information
     */ 
    public function getInformation()
    {
        return $this->Information;
    }

    /**
     * Set the value of Information
     *
     * @return  self
     */ 
    public function setInformation($Information)
    {
        $this->Information = $Information;

        return $this;
    }

    /**
     * Get the value of Address
     */ 
    public function getAddress()
    {
        return $this->Address;
    }

    /**
     * Set the value of Address
     *
     * @return  self
     */ 
    public function setAddress($Address)
    {
        $this->Address = $Address;

        return $this;
    }

    /**
     * Get the value of RegisterDate
     */ 
    public function getRegisterDate()
    {
        return $this->RegisterDate;
    }

    /**
     * Set the value of RegisterDate
     *
     * @return  self
     */ 
    public function setRegisterDate($RegisterDate)
    {
        $this->RegisterDate = $RegisterDate;

        return $this;
    }

    /**
     * Get the value of LastSeen
     */ 
    public function getLastSeen()
    {
        return $this->LastSeen;
    }

    /**
     * Set the value of LastSeen
     *
     * @return  self
     */ 
    public function setLastSeen($LastSeen)
    {
        $this->LastSeen = $LastSeen;

        return $this;
    }

    /**
     * Get the value of ProfileViews
     */ 
    public function getProfileViews()
    {
        return $this->ProfileViews;
    }

    /**
     * Set the value of ProfileViews
     *
     * @return  self
     */ 
    public function setProfileViews($ProfileViews)
    {
        $this->ProfileViews = $ProfileViews;

        return $this;
    }

    /**
     * Get the value of AvatarImage
     */ 
    public function getAvatarImage()
    {
        return $this->AvatarImage;
    }

    /**
     * Set the value of AvatarImage
     *
     * @return  self
     */ 
    public function setAvatarImage($AvatarImage)
    {
        $this->AvatarImage = $AvatarImage;

        return $this;
    }

    /**
     * Get the value of CoverImage
     */ 
    public function getCoverImage()
    {
        return $this->CoverImage;
    }

    /**
     * Set the value of CoverImage
     *
     * @return  self
     */ 
    public function setCoverImage($CoverImage)
    {
        $this->CoverImage = $CoverImage;

        return $this;
    }
}