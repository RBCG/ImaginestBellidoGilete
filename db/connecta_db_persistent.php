<?php
    $mysql_con = 'mysql:dbname=imaginest;host=localhost';
    $usu = 'root';
    $passwd = '';
    try{
        //Creem una connexió persistent a BDs
        $db = new PDO($mysql_con, $usu, $passwd, 
                        array(PDO::ATTR_PERSISTENT => true));
    }catch(PDOException $e){
        echo 'Error amb la BDs: ' . $e->getMessage();
    }