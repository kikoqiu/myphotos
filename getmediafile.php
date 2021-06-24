<?php
require_once("global.php");
ini_set('memory_limit','1024M');
set_time_limit(3600);

login();

$path=$_REQUEST['p'];
$ext=explode('.',$path);
$ext=$ext[count($ext)-1];
$isimg=in_array(strtolower($ext), getconfig('imgext'));
$isvideo=in_array(strtolower($ext), getconfig('videoext'));
if(!$isimg
    && !$isvideo){
    die('bad');
}

require_once('media_helper.php');




function outputfile($path,$isimg,$isvideo){
    $fnameansi = toansi($path);
    $fp = fopen($fnameansi, 'rb');

    // send the right headers
    if($isimg){
        header("Content-Type: image/$ext");}
    else if($isvideo){
        header("Content-Type: video/$ext");
    }

    header("Content-Length: " . filesize($fnameansi));
    // dump the picture and stop the script
    fpassthru($fp);
    exit;
}
function outputvideop($video){        
    $ret=genVideoPreview($video,getconfig('vthumbdir'),getconfig('ffmpegdir'));
    if($ret[0]==0){
        header('Location: '.$ret[1]);
    }else{
        echo $ret[2];
    }
    exit;
}
function outputimgp($img){    
    $ret=getimageinfo($img, $w=1280,$h=1280,getconfig('thumbdir'),true);
    header('Location: '.$ret[0]);
    exit;
}

if(empty($_REQUEST['pre'])){    
    outputfile($path,$isimg,$isvideo);
}else{
    if($isimg){
        outputimgp($path);
    }else if($isvideo){
        outputvideop($path);
    }
}
?>