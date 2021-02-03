<?php 
include_once "class.php";
echo Main::UPDATE($_POST['user'],$_POST['ban'],$_POST['hwid'],$_POST['date'],$_POST['type']);