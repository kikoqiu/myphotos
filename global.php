<?php
date_default_timezone_set("Asia/Shanghai");
define('ISWIN', strtoupper(substr(PHP_OS,0,3))==='WIN');


require_once('config.php');
require_once('db.php');


function fromansi($str){
	 if(!ISWIN)return $str;
	return iconv('gbk' , 'utf-8' , $str );	
}
function toansi($str){	
	if(!ISWIN)return $str;
	return iconv('utf-8' , 'gbk' , $str );
	
}
function utf8ize($mixed) {
	if(!ISWIN)return $mixed;
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } else if (is_string ($mixed)) {
        return mb_convert_encoding($mixed,'UTF-8','UTF-8');
    }
    return $mixed;
}


function getFileCacheName($img,$prefix,$postfix,$thumbdir){    
    $imgansi=toansi($img);
    $fname=basename($imgansi);
    $fnameutf8=fromansi($fname);
    $fmtime=filemtime($imgansi);
    $fsize=filesize($imgansi);
    $hash=sha1($fnameutf8.$fsize.date("Y-m-d H:i:s",$fmtime));
    $tgt = $thumbdir."/$prefix$hash".$postfix;
    return $tgt;
}



 function login() {
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="Zello"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'You have to login first！';
        exit;
    }else{
        $valid_passwords = array ("admin" => "123456");
        $valid_users = array_keys($valid_passwords);

        $user = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];

        $user=findUser($user,$pass);

        $validated = $user!=null;

        if (!$validated) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            die ("Not authorized");
        }
        return $user;
    }
}



