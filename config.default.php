<?php
function getconfig($item){
    return  
        array(
            'dbconfig'=>array('sqlite:./dbfile.db','',''),#using sqlite as backend
            #'dbconfig'=>array('mysql:host=localhost;dbname=db','',''),#use mysql
            'photodir'=>array('./photos'),#your photo directory you put your photos
            'scanvideo'=>0,#if you want to scan video files
            'videoext'=>array('mp4'),#the video file extension you want to scan
            'genvideopreview'=>1,#generate video preview(encoded with ffmpeg) when scaning
            'scanimg'=>1,#if you want to scan image files
            'imgext'=>array('jpg', 'pdf', 'png'),
            'ffmpegdir'=>'/usr/bin',#the ffmpeg file extension
            'thumbdir'=>'thumb/',#the directory you want to put the thumbnail images, PHP mast have write access, must under the web directory. suggent not modify
            'vthumbdir'=>'thumb/video/',#the video preview directory
            'videopreview_size'=>'iw/2:ih/2', #the video preview size
            'videopreview_brate'=>'1000k', #the bit rate for video file
            'videopreview_brate1'=>'500k', #the bitrate for file bigger than 400M
            'videogenerateithread'=>'-threads 2',
            'videogenerateothread'=>'-threads 2',
            'sleepbetweenngenvideo'=>10,
            'videogenerateparam'=>'-profile:v main -preset:v faster -level 2',
        )[$item];
}
date_default_timezone_set("Asia/Shanghai");