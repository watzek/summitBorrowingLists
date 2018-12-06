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

  case "getSubjectRanges":
  getSubjectRanges($_REQUEST["id"]);

}

function start($instID){
  exec ("php ajax.class.php $instID");
}


function checkProgress($instID){
  $mysql=new mysqlFunctions();
  $c=$mysql->getRequestsToBeProcessed($instID);
  echo $c;

}

function getSubjectRanges($id){

  $mysql=new mysqlFunctions();
  $r=$mysql->getSubjectRanges($id);
  echo json_encode($r);



}



?>
