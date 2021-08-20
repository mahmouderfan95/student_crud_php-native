<?php 

    $dsn = "mysql:host=localhost;dbname=studentapp";
    $user = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET Names utf8',
    );

    try{
        $conect = new PDO($dsn,$user,$pass,$option);
        $conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo 'conected database success';
    }catch(PDOException $ex){
        echo 'faild conect' . $ex->getMessage();
    }


?>