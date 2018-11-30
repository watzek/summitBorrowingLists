<?php

include ("mysql.class.php");

$fx=$_REQUEST["fx"];

switch($fx){
  case "start":
  start($_REQUEST["instID"]);
  break;

  case "checkProgress":
  checkProgress($_REQUEST["instID"]);
  break;



}

function start($instID){
  exec ("php ajax.class.php $instID");
}


function checkProgress($instID){
  $mysql=new mysqlFunctions();
  $c=$mysql->getRequestsToBeProcessed($instID);
  echo $c;

}



?>
