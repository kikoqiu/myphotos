<?php
require_once("global.php");

login();

$json=@file_get_contents('php://input');
$req=json_decode($json,1);

$method=$req['m'];
if(empty($method)){
    die(0);
}

function entry($method,$req){
    $ret=null;
    switch($method){
        case 'star':
            $ret=api_star($req);
            break;
        case 'photos':
            $ret=api_photos($req);
            break;
                    
    }
    if(gettype($ret)!='string'){
        $ret= json_encode($ret);
    }
    echo $ret;
}



function api_star($p){
    $path=$p['path'];
    $ret=markStar($path,$p['star']=='1');
    return $ret;
}

function api_photos(){
    $cache=getCache('photos');

    $images=getAllPhoto($cache?$cache->updateTime:null);
    if($images==null){
        $ret=$cache->value;
    }else{
        $ret=array();
        foreach($images as $img){
            $ret[]=array(
                $img->thumb,
                $img->tw,
                $img->th,
                $img->type,
                strtotime($img->photoTime),
                $img->star,
                $img->path,
                $img->nframes
            );
        }
        $ret=json_encode($ret);
        setCache('photos',$ret);
    }
    return $ret;

}
entry($method,$req);