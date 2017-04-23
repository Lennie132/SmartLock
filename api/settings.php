<?php
/**
 * Created by PhpStorm.
 * User: Lennart
 * Date: 11-04-17
 * Time: 10:37
 */


// DATABASE VARIABLES LOCALHOST
$db_host = "localhost";
$db_username = "root";
$db_password = "root";
$db_name = "smartlock";


// CONNECT WITH DATABASE
$connection = mysqli_connect($db_host, $db_username, $db_password, $db_name)
or die(mysqli_error($connection));
