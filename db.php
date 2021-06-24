<?php
require 'rb.php';
R::setup( getconfig('dbconfig')[0] ,getconfig('dbconfig')[1],getconfig('dbconfig')[2]);
//R::freeze( TRUE );
R::setAutoResolve( TRUE ); //optional

function findPhoto($path){
    $post = R::findone('photo','path=?',array($path));
    return $post;
}
function markStar($path,$val){
    $post = findPhoto($path);
    if($post==null)return false;
    $post->star=$val;
    $post->starModified=R::isoDateTime();
    R::store( $post );
    return $post;
}
function getAllPhoto($updateTime){    
    if($updateTime!=null){
        try{
            $cnt = R::getCell('select count(*) from photo where update_time>?',array($updateTime)); 
            if($cnt==0)return null;   
        }catch(Exception $e){
        }
    }
    $posts = R::findAll( 'photo' );        
    return $posts;
}


function updatePhoto($path,$thumb,$tw,$th,$type,$fmtime,$exif,$nframes=1,$origbean=null){
    if($origbean!=null){
        $post=$origbean;
    }else{
        $post = R::dispense( 'photo' );
        $post->addTime=R::isoDateTime();
    }
    $post->path = $path;
    $post->thumb=$thumb;
    $post->tw=$tw;
    $post->th=$th;
    $post->type=$type;
    $post->photoTime=$fmtime;
    $post->exif=$exif;
    $post->updateTime=R::isoDateTime();    
    $post->nframes=$nframes;

    $id = R::store( $post ); //create or update
    return $id;

    #$post = R::load( 'post', $id ); //retrieve
    #R::trash( $post ); //delete*/
}



function getCache($key){    
    $post = R::findone('cache','ckey=?',array($key));    
    return $post;
}

function setCache($key,$val,$validUntil=null){
    $post = getCache($key);  
    if($post==null){
        $post = R::dispense( 'cache' );
        $post->ckey=$key;
    }
    $post->value=$val;
    $post->updateTime=R::isoDateTime();
    $post->validUntil=$validUntil;
    $id = R::store( $post ); //create or update
    return $post;
}

function addUser($un,$pw,$level,$post=null){
    if($post==null){
        $post = R::dispense( 'user' );
        $post->addTime=R::isoDateTime();
    }
    $post->un = $un;
    $post->pw=$pw;
    $post->level=$level;
    $post->updateTime=R::isoDateTime();

    $id = R::store( $post ); //create or update
    return $id;
}

function findUser($un,$pw){
    $post = R::findone('user','un=? and pw=?',array($un,$pw));
    return $post;
}

function isInstalled(){    
    $post = R::findAll( 'user' );
    return count($post)>0;
}
