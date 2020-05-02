<?php
/**
 * Functions.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2019
 * @license     The MIT License (MIT)
 */

class Functions{
    // set sessions
    public function setSessions($par)
    {
        foreach ($par as $anahtar => $deger){
            $_SESSION[$anahtar] = $deger;
        }
    }

    // get sessions
    public function getSession($par) 
    {
        if ( $_SESSION[$par] )
        {
            return $_SESSION[$par];
        }
        else
        {
            return false;
        }
    }

    // clear sessions
    public function clearSessions()
    {
        session_destroy();
        foreach ($par as $anahtar => $deger){
            unset($_SESSION[$anahtar]);
        }
    }

    // set cookies
    public function setCookies($par, $time)
    {
        foreach ($par as $anahtar => $deger){
            setcookie($anahtar, $deger, time()+$time);
        }
    }

    // get cookie
    public function getCookie($par) 
    {
        if ( $_COOKIE[$par] )
        {
            return $_COOKIE[$par];
        }
        else
        {
            return false;
        }
    }

    // clear cookies
    public function clearCookies()
    {
        foreach ($_COOKIE as $value => $deger){
            setcookie($value, $deger, time() - (60 * 60 * 24 * 30));
        }
    }

    // hash password
    public function hashPassword($par)
    {
        return md5(sha1(md5($par)));
    }

    // create token
    public function createToken($length = 64)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // check email correct format. 
    public function checkEmail( $email = NULL )
    {
        return preg_match('#([a-zA-Z0-9_-]+)(\@)([a-zA-Z0-9_-]+)(\.)([a-zA-Z0-9]{2,4})(\.[a-zA-Z0-9]{2,4})?#' , $email);
    }
}