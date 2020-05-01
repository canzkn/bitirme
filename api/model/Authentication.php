<?php
/**
 * Authentication Class
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */

class Authentication extends Core\User {
    // Error Codes
    private $FAILED_CODE = 0;
    private $SUCCESS_CODE = 1;
    private $BLANK_CODE = 2;
    private $AVAILABLE_CODE = 3;
    private $ALREADY_LOGGED = 4;
    private $TOKEN_CREATE_FAILED = 5;

    // Access Token
    private $token;

    // DB Stuff
    private $conn;
    private $table = 'users';

    // Constructor with DB
    public function __construct($db) 
    {
        $this->conn = $db;
    }

    // Set Token
    public function setAccessToken($token)
    {
        $this->token = $token;

        return $this;
    }

    // Get Token
    public function getAccessToken()
    {
        return $this->token;
    }
}