<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './debugger.php';
require_once './classes/Database.php';

$C = new Database;

if($C){
    echo 100;
}else{
   echo 911;
}
?>
