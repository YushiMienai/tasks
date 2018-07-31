<?php
/**
 * Created by PhpStorm.
 * User: Yushi
 * Date: 28.07.2018
 * Time: 2:38
 */

class dbconnection
{
    public function connect(){
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $db = 'yii2';
        $connection = mysqli_connect($host,$user,$pass,$db);
        return $connection;
    }

}