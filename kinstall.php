<?php 
require_once("global.php");
ini_set('memory_limit','1024M');
set_time_limit(3600);
if(!empty($_GET['p'])){
    $json=@file_get_contents('php://input');
    $req=json_decode($json,1);

    $method=$req['m'];
    if(empty($method)){
        die(0);
    }
    function entry($method,$req){
        $ret=null;
        switch($method){
            case 'adduser':
                if(isInstalled()){
                    #$ret=[0,'already installed!'];
                    login();
                    addUser($req['un'],$req['pw'],$req['grade']);
                    $ret=[1,'added!'];
                }else{
                    addUser($req['un'],$req['pw'],$req['grade']);
                    $ret=[1,'added!'];
                }
                break;
            default:
                $ret=[0,'known method'];
                break;
        }
        echo json_encode($ret);
    }
    entry($method,$req);
    die(0);
}
if(isInstalled()){
  login();
}
?><!doctype html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"  crossorigin="anonymous">
    <title>Install</title>
  </head>
  <body>
    <h2>Install</h2>
    <h3>Extensions:</h3>
    <p><?php 
    $required=array('exif','mbstring','pdo_sqlite','pdo_mysql','gd');
    foreach($required as $r){
        if(extension_loaded($r)){
            echo "<div class='alert alert-success' role='alert'>";
            echo "$r is installed!";
            echo "</div>";
        }else{
            echo "<div class='alert alert-danger' role='alert'>";
            echo "$r is not installed!";
            echo "</div>";
        }
    }
    ?>
    <h3>Setup WebServer [not mandatory]</h3>
    <p>If you use nginx & fastcgi, please set up the nginx config with 
    <pre><code>
    fastcgi_connect_timeout 300;
    fastcgi_read_timeout 3000;
    fastcgi_send_timeout 3000;
    </code></pre>
    to make video scan to work! Otherwise the scan process might be interrupted when timeout occurs.
    </p>
    <h3>Setup ffmpeg [not mandatory]</h3>
    <p>You have to install ffmpeg to make the video function to work! 
    </p>
    
    <hr/>
    <h2>After install </h2>
    <h3>Copy config.default.php to config.php</h3>
    <p>You have to update config.php first! See config.default.php for more information.
    </p>
    <h3>Scan media files</h3>
    <p>Visit scan.php to index your pictures and videos!
    </p>
    <h3>Add a user here</h3>
    <form class="form-inline">
        <div class="form-group">
            <label for="un">Username</label>
            <input type="text" class="form-control form-control-sm" id="un" value='admin' >
        </div>
        <div class="form-group">
            <label for="pw">Password</label>
            <input type="text" class="form-control form-control-sm" id="pw">
        </div>
        <button type="button" class="btn btn-primary form-control-sm" onclick='submit1()'>Submit</button>
    </form>
    <br/>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        function submit1(){
            axios.post('kinstall.php?p=1', {
                "m": 'adduser',
                'un':$('#un').val(),
                'pw':$('#pw').val(),
                'grade':9
                }).then(response => {
                    alert(response.data[1]);
                    //$('#info').toast('show');
                    console.log('', response.data)
                }, error => {
                    console.log('', error.message)
                    alert(error.message);
                });
        }
    </script>
  </body>
</html>










