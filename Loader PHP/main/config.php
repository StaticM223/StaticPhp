<?php

$host = "localhost";
$dbname = "fuckoff";
$username = "fuckoff";
$password = "fuckoff";

try{
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
}catch(Exception $e){
    die("Fatal error: ".$e->getMessage());
}
