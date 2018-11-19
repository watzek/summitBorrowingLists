<?php

include ("mysql.class.php");
$mysql=new mysqlFunctions();

/*
$rows=$mysql->getNl();

foreach ($rows as $row){

  $id=$row["id"];
  $nl=(float)$row["LCnumberLine"];

  echo "<p>$id | $nl</p>";
  $mysql->updateNl($id, $nl);



}
*/


$x=0;

$ranges=$mysql->getAllRangesCast();

foreach ($ranges as $range){
  $subject_id=$range["subject_id"];
  $bsub=$range["begLCsub"];
  $bnl=$range["bnl"];
  $esub=$range["endLCsub"];
  $enl=$range["enl"];
  $requests=$mysql->getRequestsMatchingRange($bsub, $bnl, $esub, $enl);
  $c=count($requests);
  foreach ($requests as $request){
    $id=$request["id"];
    $mysql->updateRequestSubjectId($id, $subject_id);

  }


  echo "$bsub, $bnl, $esub, $enl: $c<br>";
  $x=$x+$c;





}

echo "<p>total: $x</p>";




 ?>
