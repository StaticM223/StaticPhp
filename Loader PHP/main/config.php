<?php

$host = "localhost";
$dbname = "id14816417_miner";
$username = "id14816417_staticminer";
$password = "nYd6GGloEdB72k$";

try{
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
}catch(Exception $e){
    die("Fatal error: ".$e->getMessage());
}
