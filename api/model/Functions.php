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

    // time convert
    public function time_convert ( $zaman )
	{
	
	   $zaman =  !is_numeric( $zaman ) ? strtotime($zaman) : $zaman;
	   $zaman_farki = time() - $zaman;
	   $saniye = $zaman_farki;
	   $dakika = round($zaman_farki/60);
	   $saat = round($zaman_farki/3600);
	   $gun = round($zaman_farki/86400);
	   $hafta = round($zaman_farki/604800);
	   $ay = round($zaman_farki/2419200);
	   $yil = round($zaman_farki/29030400);
	   if( $saniye < 60 ){
	      if ($saniye == 0){
	         return "az önce";
	      } else {
	         return $saniye .' saniye önce';
	      }
	   } else if ( $dakika < 60 ){
	      return $dakika .' dakika önce';
	   } else if ( $saat < 24 ){
	      return $saat.' saat önce';
	   } else if ( $gun < 7 ){
	      return $gun .' gün önce';
	   } else if ( $hafta < 4 ){
	      return $hafta.' hafta önce';
	   } else if ( $ay < 12 ){
	      return $ay .' ay önce';
	   } else {
	      return $yil.' yıl önce';
	   }
    }
    
    // time convert 2
    public function time_convert2 ( $date )
	{
		$aylar = array(
			'Ocak',
			'Şubat',
			'Mart',
			'Nisan',
			'Mayıs',
			'Haziran',
			'Temmuz',
			'Ağustos',
			'Eylül',
			'Ekim',
			'Kasım',
			'Aralık'
		);
		$ay = $aylar[date('m', strtotime($date)) - 1];
		return date("j ", strtotime($date)) . $ay . date(" Y H:i", strtotime($date));
	}
}