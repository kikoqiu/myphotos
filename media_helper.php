<?php

function image_resize($f, $t, $tw, $th,$exif){
    // 按指定大小生成缩略图，而且不变形，缩略图函数
        $temp = array(1=>'gif', 2=>'jpeg', 3=>'png');  


        list($fw, $fh, $tmp) = getimagesize($f);
    
        if(!$temp[$tmp]){
                return false;
        }
        $tmp = $temp[$tmp];
        $infunc = "imagecreatefrom$tmp";
        $outfunc = "image$tmp";
    
        $fimg = $infunc($f);
        if(!empty($exif['IFD0']['Orientation'])) {
            switch($exif['IFD0']['Orientation']) {
                case 8:
                    $fimg = imagerotate($fimg,90,0);
                    $fwt= $fw;
                    $fw=$fh;
                    $fh=$fwt;
                break;
                case 3:
                    $fimg = imagerotate($fimg,180,0);
                break;
                case 6:
                    $fimg = imagerotate($fimg,-90,0);
                    $fwt= $fw;
                    $fw=$fh;
                    $fh=$fwt;
                break;
            }
        }
        
    
        // 使缩略后的图片不变形，并且限制在 缩略图宽高范围内
        if($fw/$tw > $fh/$th){
            $th = $tw*($fh/$fw);
        }else{
            $tw = $th*($fw/$fh);
        }
    
        $timg = imagecreatetruecolor($tw, $th);
        imagecopyresampled($timg, $fimg, 0,0, 0,0, $tw,$th, $fw,$fh);
        if($outfunc($timg, $t,90)){
                return true;
        }else{
                return false;
        }
}
    



function getimageinfo($img, $w=2000,$h=300,$thumbdir,$thumbonly=false){
    $tgt = getFileCacheName($img,'thumb_',"_".$w."_".$h.".jpg", $thumbdir);
    if($thumbonly){       
        if(file_exists($tgt)){
            return array($tgt);
        }
    }
    if(!file_exists($thumbdir))mkdir($thumbdir);
	$imgansi=toansi($img);
    $fname=basename($imgansi);
    $fmtime=filemtime($imgansi);

    $exif = exif_read_data($imgansi, 0, 1);
    #var_dump($exif);

    

    if(!file_exists($tgt)){
        image_resize( $imgansi,$tgt, $w, $h,$exif);
    }

    list($fw, $fh, $tmp) = getimagesize($tgt);



    if(!empty($exif['EXIF']['DateTimeDigitized'])){
        $fmtime=strtotime($exif['EXIF']['DateTimeDigitized']);
    }
    #return array($tgt,$fw,$fh,$tmp,$fmtime,$exif);
    return array($tgt,$fw,$fh,$tmp,$fmtime,utf8ize($exif),1);
}


function genVideoPreview($img,$thumbdir,$ffdir,$rest=0){
    if(!file_exists($thumbdir))mkdir($thumbdir);
	$imgansi=toansi($img);
    $fname=basename($imgansi);
    $fmtime=filemtime($imgansi);
    $fsize=filesize($imgansi);

    $tgt = getFileCacheName($img,'video_',".mp4", $thumbdir);

    $videopreview_size=getconfig('videopreview_size');
    $videopreview_brate=getconfig('videopreview_brate');
    if($fsize>1024*1024*400)$videopreview_brate=getconfig('videopreview_brate1');
    $ret=0;
    $infocmd='';
    $infojson='';
    
    $thd0=$thd1='';
    if($rest>0){
        $thd0=getconfig('videogenerateithread');
        $thd1=getconfig('videogenerateothread');
    }

    $videogenerateparam=getconfig('videogenerateparam');
    if(!file_exists($tgt) || filesize($tgt)<=0){      
       $infocmd="\"$ffdir/ffmpeg\" -y $thd0 -i \"$imgansi\"  -vf \"scale=$videopreview_size\" $thd1 -c:v libx264 $videogenerateparam -b:v $videopreview_brate -c:a copy \"$tgt\" 2>&1";

        ob_start();        
        system($infocmd,$ret);
        $infojson=ob_get_contents();
        ob_end_clean();
        if($rest>0)sleep($rest);
    }
    return array($ret,$tgt, "<pre> $infocmd \n $infojson</pre>");
}