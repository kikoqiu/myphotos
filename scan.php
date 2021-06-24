<!doctype html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Myphoto Scan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  </head>
<body> 
<script>
var oldHeight=document.body.scrollHeight;
setInterval(()=>{
    if(oldHeight!=document.body.scrollHeight){
        oldHeight=document.body.scrollHeight
        window.scrollTo(0,document.body.scrollHeight);
    }
    },400);
</script>
<?php
require_once("global.php");
ini_set('memory_limit','1024M');
set_time_limit(10000);
header('X-Accel-Buffering: no');

login();


require_once('media_helper.php');
if(! (extension_loaded('exif') && extension_loaded('mbstring'))){ 
    echo 'exif module was not loaded,please check it in php.ini<br>NOTICE:On Windows,php_mbstring.dll must be before php_exif.dll'; 
}





function scanfiles($imgfolder, $tgt,$ret=array()){  
    if(is_array ( $imgfolder )){
        foreach($imgfolder as $f){
            $ret=scanfiles($f,$tgt,$ret); 
        }
        return $ret;
    }
    #echo "entering $imgfolder<br/>";print_r($ret);
    $list = scandir(toansi($imgfolder));
    foreach ($list as $value) {
        $nextpath=$imgfolder."/".fromansi($value);
		if(is_dir(toansi($nextpath)) ){ //判断是不是文件夹
            if($value!="." && $value!=".."){               
			    $ret=scanfiles($nextpath,$tgt,$ret); //继续遍历
            }
            continue;
		}
  
        $ext = strtolower(pathinfo($value)['extension']);
        if (in_array($ext,$tgt)) {
            $ret[]= $imgfolder .'/' . fromansi($value);
        }
    }
    #echo "exiting $imgfolder<br/>";    print_r($ret);
    return $ret;
}


if(getconfig('scanimg')){
    $images=scanfiles(getconfig('photodir'),getconfig('imgext'));
    #print_r($images);
    $ret=array();
    foreach($images as $img){
        echo date("Y-m-d h:i:sa").":$img<br/>";
        $photo=findPhoto($img);
        if($photo==null){
            $th=getimageinfo($img,$w=2000,$h=300,getconfig('thumbdir'));
            updatePhoto($img,$th[0],$th[1],$th[2],$th[3],R::isoDateTime($th[4]),json_encode($th[5]),$th[6]);
            echo date("Y-m-d h:i:sa").': update'.$img."<br/>\r\n";
            ob_flush();
            flush();
        }
    }
    #echo json_encode($ret);
}


/**
 * Deprecated
 */
function getvideoinfo($img, $w,$th,$thumbdir,$ffdir){
    if(!file_exists($thumbdir))mkdir($thumbdir);
	$imgansi=toansi($img);
    $fname=basename($imgansi);
    $fmtime=filemtime($imgansi);

    #$exif = exif_read_data($imgansi, 0, 1);
    #var_dump($exif); 
    $tgt = getFileCacheName($img,'thumb_',"_".$w."_".$th.".jpg", $thumbdir);
    
    if(!file_exists($tgt)){        
        $infocmd="\"$ffdir/ffprobe\" -v error -select_streams v:0 -show_entries stream=width,height,r_frame_rate,nb_frames  -of json \"$imgansi\"";
        echo $infocmd;
        ob_start();        
        system($infocmd,$ret);
        $infojson=ob_get_contents();
        ob_end_clean();
        
        if($ret==0){
            $info=json_decode($infojson,1);
            $srcw=$info['streams'][0]['width'];
            $srch=$info['streams'][0]['height'];
            $nbframes=$info['streams'][0]['nb_frames'];
            $framemod=floor($nbframes/19);
            if(!($framemod>=1)){
                $framemod=1;
            }
            $nframes=intval($nbframes/$framemod)+1;
        }else{
            echo "error0:".$img;
            $info==null;
            return null;
        }        
        
        $cmd="\"$ffdir/ffmpeg\" -i \"$imgansi\" -vf select='not(mod(n\,$framemod))',scale=-1:$th,tile=10x2 -frames:v 1 \"$tgt\"";
        ob_start();
        system($cmd,$ret);      
        $result=ob_get_contents();
        ob_end_clean();
        if($ret!=0){
            echo "error1: $result ".$img;
            return null;
        }
        list($fw, $fh, $tmp) = getimagesize($tgt);
        $tw=$fw;
        $th=$fh;
    }

    return array($tgt,$tw,$th,'4',$fmtime,$infojson,$nframes);
}




function getvideoinfoAsImg($img, $w,$th,$thumbdir,$ffdir){
    if(!file_exists($thumbdir))mkdir($thumbdir);
	$imgansi=toansi($img);
    $fname=basename($imgansi);
    $fmtime=filemtime($imgansi);

    $tgt = getFileCacheName($img,'thumb_',"_".$w."_".$th.".jpg", $thumbdir);

    $vfh=$th;//intval($th/2);        
    $framemod=15;
    $infojson=null;
    $nframes=1;
        
    if(!file_exists($tgt)){
        $cmd="\"$ffdir/ffmpeg\" -i \"$imgansi\" -vf select='not(mod(n\,$framemod))',scale=-1:$vfh,tile=1x1 -frames:v 1 \"$tgt\"";
        if(!empty($_REQUEST['debug']))echo "$cmd<br/>\n";
        ob_start();
        system($cmd,$ret);      
        $result=ob_get_contents();
        ob_end_clean();
        if($ret!=0){
            echo "error1: $result ".$img;
            return null;
        }
    }
    list($fw, $fh, $tmp) = getimagesize($tgt);
    $tw=$fw;
    $th=$fh;
    return array($tgt,$tw,$th,'5',$fmtime,$infojson,$nframes);
}




if(getconfig('scanvideo')){
    $images=scanfiles(getconfig('photodir'),getconfig('videoext'));
    $ret=array();
    foreach($images as $img){
        echo date("Y-m-d h:i:sa").": Start for $img<br/>\r\n";
        $photo=findPhoto($img);

        $th=getvideoinfoAsImg($img,$w=2000,$h=300,getconfig('thumbdir'),getconfig('ffmpegdir'));
        echo date("Y-m-d h:i:sa").": getvideoinfoAsImg <br/>\r\n";

        updatePhoto($img,$th[0],$th[1],$th[2],$th[3],R::isoDateTime($th[4]),json_encode($th[5]),$th[6],$photo); 
        
        if(getconfig('genvideopreview')){
            $t=genVideoPreview($img,getconfig('vthumbdir'),getconfig('ffmpegdir'));
            if($t[0]!=0){
                echo $t[2];
            }
            echo date("Y-m-d h:i:sa").": genVideoPreview <br/>\r\n";
        }

        echo date("Y-m-d h:i:sa").": end <br/>\r\n";
        ob_flush();
        flush();
    }

}

?>