<?php

class mysqlFunctions{

  function __construct(){

    include("config.php");
    $connect="mysql:host=$server;dbname=$database";

    try {
      $db = new PDO($connect, $username, $password);

    }
    catch(PDOException $e) {
      echo $e->getMessage();
    }
    $this->db=$db;

  }

  function addSubject($subject, $instID, $userID){
    $db=$this->db;
    try {
      if ($userID){
        $stmt = $db->prepare("insert into subjects (subject, selector_id, library_id) values (:subject, :userID, :instID)");
        $data=array(":subject"=>$subject, ":instID"=>$instID, ":userID"=>$userID);
      }
      else{
        $stmt = $db->prepare("insert into subjects (subject,  library_id) values (:subject, :instID)");
        $data=array(":subject"=>$subject, ":instID"=>$instID);
      }


      //var_dump($data);

      $stmt->execute($data);
      //$stmt->debugDumpParams();
      $lastId = $db->lastInsertId();
      return $lastId;

    }
    catch (Exception $e) {

      echo $e;
    }

  }


  function getRequestsGroupedBySubject($id){
    $db=$this->db;
    try {
      //select count(title), title from requests  where APIstatus='unable to resolve' and library_id=1 group by title order by count(title) asc;
      $stmt = $db->prepare("select count(title) as c, title, author, LCSubject, LCnumberLine, LCremainder, pubdate from requests  where subject_id=:id group by title order by count(title) desc, title asc;");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //  var_dump($rows);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;


  }

  function addRange($begLCsub, $begLCnl, $endLCsub, $endLCnl, $subject_id){
    $db=$this->db;
    try {

        $stmt = $db->prepare("insert into ranges (begLCsub, begLCnl, endLCsub, endLCnl, subject_id) values (:begLCsub, :begLCnl, :endLCsub, :endLCnl, :subject_id)");
        $data=array(":begLCsub"=>$begLCsub, ":begLCnl"=>$begLCnl, ":endLCsub"=>$endLCsub, ":endLCnl"=>$endLCnl, ":subject_id"=>$subject_id);
      $stmt->execute($data);
      //$stmt->debugDumpParams();
      $lastId = $db->lastInsertId();
      return $lastId;

    }
    catch (Exception $e) {

      echo $e;
    }
  }

  function getAllSelectors($instID){
    try {
      $db=$this->db;
      $stmt = $db->prepare("select distinct selectors.id, selectors.name from selectors, subjects where  selectors.library_id=:instID and selectors.id=subjects.selector_id");
      $stmt = $db->prepare("select distinct selectors.id, selectors.name from selectors where  selectors.library_id=:instID ");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;

  }

  function getRequestsToBeProcessed($instID){
    $db=$this->db;
    try {

      $stmt = $db->prepare("select count(id) as c from requests where (APIstatus='oclc1' or APIstatus='oclc2' or APIstatus='isbn1' or APIstatus='isbn2' or APIstatus='ta') and library_id=:instID");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows[0]["c"];



  }


  function getRequestsToBeProcessedByFile($fileID){
    $db=$this->db;
    try {

      $stmt = $db->prepare("select count(id) as c from requests where (APIstatus='oclc1' or APIstatus='oclc2' or APIstatus='isbn1' or APIstatus='isbn2' or APIstatus='ta') and file_id=:fileID");
      $stmt->execute(array(":fileID"=>$fileID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows[0]["c"];



  }


  function getAllUsers($instID){
    try {
      $db=$this->db;
      $stmt = $db->prepare("select * from selectors where library_id=:instID");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;

  }

  function getFilesByInstitution($instID){
    try {
      $db=$this->db;
      $stmt = $db->prepare("select * from reportFiles where library_id=:instID order by id desc");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  function deleteFile($id){
    $db=$this->db;
    try {

      $stmt = $db->prepare("delete from reportFiles where id=:id");
      $stmt->execute(array(":id"=>$id));
      if($stmt->fetchAll(PDO::FETCH_ASSOC)){return true;}
      else{return false;}

    }
    catch (Exception $e) {

      echo $e;
    }
  }

  function deleteRequestsByFile($id){
    $db=$this->db;
    try {
      $stmt = $db->prepare("delete from requests where file_id=:id");


      $stmt->execute(array(":id"=>$id));


    }
    catch (Exception $e) {

      echo $e;
    }
    $stmt->debugDumpParams();

  }

  function updateFileStatus($id, $status){
    $db=$this->db;
    try {
      $db=$this->db;
      $stmt = $db->prepare("update reportFiles set status=:status where id=:id");
      $stmt->execute(array(":status"=>$status, ":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }

  }

  function getBegDate($fileID){
    try {
      $db=$this->db;
      $stmt = $db->prepare("select requestDate from requests where file_id=:fileID order by requestDate asc limit 1");
      $stmt->execute(array(":fileID"=>$fileID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows[0]["requestDate"];
  }
  function getEndDate($fileID){
    try {
      $db=$this->db;
      $stmt = $db->prepare("select requestDate from requests where file_id=:fileID order by requestDate desc limit 1");
      $stmt->execute(array(":fileID"=>$fileID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows[0]["requestDate"];
  }

  function getCountByFile($fileID){
    try {
      $db=$this->db;
      $stmt = $db->prepare("select count(id) as c from requests where file_id=:fileID");
      $stmt->execute(array(":fileID"=>$fileID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows[0]["c"];



  }

  function updateFileCountRange($id, $count, $beg, $end){
    try {
      $db=$this->db;
      $stmt = $db->prepare("update reportFiles set count=:count, begDate=:beg, endDate=:end where id=:id");
      $stmt->execute(array(":id"=>$id, ":count"=>$count, ":beg"=> $beg, ":end"=>$end));
      if($stmt->fetchAll(PDO::FETCH_ASSOC)){return true;}
      else{return false;}

    }
    catch (Exception $e) {

      echo $e;
    }


  }


  function getSelector($id){

    try {
      $db=$this->db;
      $stmt = $db->prepare("select * from selectors where id=:id");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;

  }

  function getAllSubjects($instID){
    $db=$this->db;
    try {
      $stmt = $db->prepare("select * from subjects where library_id=:instID order by subject");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;

  }

  function getSelectorSubjects($id){

    try {
      $db=$this->db;
      $stmt = $db->prepare("select * from subjects where selector_id=:id order by subject");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  /*includes selector name & id */
  function getSubjectInfo($id){
    try {
      $db=$this->db;
      $stmt = $db->prepare("select subjects.subject as subject, selectors.name as selector, selectors.id as selector_id from subjects join selectors on subjects.selector_id=selectors.id where subjects.id=:id;");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }


  function getPatronTypes(){
    try {
      $db=$this->db;
      $stmt = $db->prepare("select * from patronTypes");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;




  }

  function getRequestsNeedingSubjects(){

    try {
      $db=$this->db;
      $stmt = $db->prepare("select count(id) as needSubj from requests where APIstatus='needSubj'");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;





  }

  function addRequest($entry){
    //echo "entry:";

    $db=$this->db;
    try {

      $stmt = $db->prepare("insert into requests (title, pubDate, isbn1, isbn2, oclc1, oclc2, requestDate, author, library_id, file_id, APIstatus, publisher) values (:title, :pubDate, :isbn1, :isbn2, :oclc1, :oclc2, :requestDate, :author, :library_id, :file_id, :APIstatus, :publisher)");
      $data=array(":title"=>$entry["title"], ":pubDate"=>$entry["pubDate"], ":isbn1"=>$entry["isbn1"], ":isbn2"=>$entry["isbn2"], ":oclc1"=>$entry["oclc1"], ":oclc2"=>$entry["oclc2"], ":requestDate"=>$entry["requestDate"], ":author"=>$entry["author"], ":library_id"=>$entry["instID"], ":file_id"=>$entry["fileID"], ":APIstatus"=>$entry["APIstatus"], ":publisher"=>$entry["publisher"]);
      //var_dump($data);

      $stmt->execute($data);
      //$stmt->debugDumpParams();
      $lastId = $db->lastInsertId();
      return $lastId;

    }
    catch (Exception $e) {

      echo $e;
    }

  }

  function getNewRequestsByStatusAndInst($status, $instID){

    $db=$this->db;

      try {
        $stmt = $db->prepare("select * from requests where APIstatus=:status and library_id=:instID");
        $stmt->execute(array(":status"=>$status, ":instID"=>$instID));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      }
      catch (Exception $e) {

        echo $e;
      }



    return $rows;

  }

  function getNewRequestsByStatus($status, $fileID=null){

    $db=$this->db;
    if ($fileID){
      try {
        $stmt = $db->prepare("select * from requests where APIstatus=:status and file_id=:fileID");
        $stmt->execute(array(":status"=>$status, ":fileID"=>$fileID));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      }
      catch (Exception $e) {

        echo $e;
      }


    }
    else{
      try {
        $stmt = $db->prepare("select * from requests where APIstatus=:status ");
        $stmt->execute(array(":status"=>$status));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      }
      catch (Exception $e) {

        echo $e;
      }

    }


    return $rows;

  }



  function getNewRequestsWithOclc($file_id=NULL){
    $db=$this->db;
    if ($file_id){
      try {
        $stmt = $db->prepare("select * from requests where (APIstatus='oclc1' or APIstatus='oclc2') and file_id=:fileID ");
        $stmt->execute(array(":fileID"=>$file_id));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      }
      catch (Exception $e) {

        echo $e;
      }

    }
    else{
      try {
        $stmt = $db->prepare("select * from requests where oclc1 is not null and APIstatus='new' ");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      }
      catch (Exception $e) {

        echo $e;
      }


    }



    return $rows;

  }

  function getNewRequestsWithIsbn(){
    $db=$this->db;
    try {
      $stmt = $db->prepare("select * from requests where  isbn1 is not null and APIstatus='new' ");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;



  }

  /* gets new requests with neither OCLC nor ISBN        */
  function getNewRequestsNoIdentifiers(){

    $db=$this->db;
    try {
      $stmt = $db->prepare("select * from requests where oclc1 is null and isbn1 is null and APIstatus='new' and title is not null  order by id asc");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;



  }

  function updateCN($id, $cnPieces){
    //var_dump($cnPieces);
    //echo $id;
    $db=$this->db;
    try {
      $db=$this->db;
      $stmt = $db->prepare("update requests set LCsubject=:LCsubject, LCnumberLine=:LCnumberLine, LCremainder=:LCremainder, LCnl=:LCnl where id=:id");
      $data=array(":LCsubject"=>$cnPieces["LCsubject"], ":LCnumberLine"=>$cnPieces["LCnumberLine"], ":LCremainder"=>$cnPieces["LCremainder"],":LCnl"=>$cnPieces["LCnl"], ":id"=>$id);


      if ($stmt->execute($data)){return true;}
      else{return false;}



    }
    catch (Exception $e) {

      echo $e;
    }

  }

  function updateStatus($id, $status){

    $db=$this->db;
    try {
      $db=$this->db;
      $stmt = $db->prepare("update requests set APIstatus=:status where id=:id");
      $data=array(":status"=>$status, ":id"=>$id);


      if ($stmt->execute($data)){return true;}
      else{return false;}

    }
    catch (Exception $e) {

      echo $e;
    }

  }

  function addDewey($id, $cn){

    $db=$this->db;
    try {
      $db=$this->db;
      $stmt = $db->prepare("update requests set dewey=:cn where id=:id");
      $data=array(":cn"=>$cn, ":id"=>$id);


      if ($stmt->execute($data)){return true;}
      else{return false;}

    }
    catch (Exception $e) {

      echo $e;
    }

  }



  function getAllByLetterForCsv($letter, $instID){
  //select * from requests where APIstatus='needSubj' order by LCsubject, LCnumberLine, LCremainder, pubdate desc, oclc1, requestDate desc;

      $db=$this->db;
      try {
        if ($letter=="dewey"){
          $stmt = $db->prepare("select requestDate, author, title, publisher, pubdate, concat_ws(';',isbn1,isbn2) as isbn, concat_ws(';',oclc1,oclc2) as oclc, dewey as cn, LCremainder from requests where  APIstatus='Dewey' and library_id=:instID order by dewey asc");
          $stmt->execute(array(":instID"=>$instID));
        }
        elseif($letter=="all"){
          $stmt = $db->prepare("select requestDate, author, title, publisher, pubdate, concat_ws(';',isbn1,isbn2) as isbn, concat_ws(';',oclc1,oclc2) as oclc, concat_ws('',LCsubject,LCnumberLine) as cn, LCremainder from requests where  LCsubject is not null and library_id=:instID order by LCsubject, LCnl, LCremainder, pubdate desc, oclc1, requestDate desc");
          $stmt->execute(array(":instID"=>$instID));
        }
        else{
          $stmt = $db->prepare("select requestDate, author, title, publisher, pubdate, concat_ws(';',isbn1,isbn2) as isbn, concat_ws(';',oclc1,oclc2) as oclc, concat_ws('',LCsubject,LCnumberLine) as cn, LCremainder from requests where  LCsubject like '$letter%' and library_id=:instID order by LCsubject, LCnl, LCremainder, pubdate desc, oclc1, requestDate desc");
          $stmt->execute(array(":instID"=>$instID));
        }
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      }
      catch (Exception $e) {

        echo $e;
      }
      //$stmt->debugDumpParams();
      return $rows;

    }

function getAllByLetter($letter, $instID){
//select * from requests where APIstatus='needSubj' order by LCsubject, LCnumberLine, LCremainder, pubdate desc, oclc1, requestDate desc;

    $db=$this->db;
    try {
      if ($letter=="dewey"){
        $stmt = $db->prepare("select * from requests where  APIstatus='Dewey' and library_id=:instID order by dewey asc");
        $stmt->execute(array(":instID"=>$instID));
      }
      elseif($letter=="all"){
        $stmt = $db->prepare("select * from requests where  LCsubject is not null and library_id=:instID order by LCsubject, LCnl, LCremainder, pubdate desc, oclc1, requestDate desc");
        $stmt->execute(array(":instID"=>$instID));
      }
      else{
        $stmt = $db->prepare("select * from requests where  LCsubject like '$letter%' and library_id=:instID order by LCsubject, LCnl, LCremainder, pubdate desc, oclc1, requestDate desc");
        $stmt->execute(array(":instID"=>$instID));
      }
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;

  }

  function getAll($instID){
    $db=$this->db;
    $rows=array();

    try {

      $stmt = $db->prepare("select * from requests where  LCsubject like '$letter%' and library_id=:instID order by LCsubject, LCnl, LCremainder, pubdate desc, oclc1, requestDate desc");
      $stmt->execute(array(":instID"=>$instID));
/*
      foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $row) {
          array_push($rows, $row);
      }
      */
      /*
      foreach($stmt->fetch(PDO::FETCH_ASSOC) as $row){
        array_push($rows, $row);


      }
      */

      //$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;

  }

  function getRequestsBySubjectId($id){
    $db=$this->db;
    try {
      $stmt = $db->prepare("select * from requests where  subject_id=:id order by LCsubject, LCnl, LCremainder, pubdate desc, oclc1, requestDate desc");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //  var_dump($rows);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  function getRequestsBySubjectIdForCsv($id){
    $db=$this->db;
    try {
      $stmt = $db->prepare("select requestDate, author, title, publisher, pubdate, concat_ws(';',isbn1,isbn2) as isbn, concat_ws(';',oclc1,oclc2) as oclc, concat_ws('',LCsubject,LCnumberLine) as cn, LCremainder  from requests where  subject_id=:id order by LCsubject, LCnl, LCremainder, pubdate desc, oclc1, requestDate desc");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //  var_dump($rows);
    //concat_ws(';',oclc1,oclc2) as oclc

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }



  function getRequestsNoSubjects(){
    $db=$this->db;
    try {
      $stmt = $db->prepare("select * from requests where  APIstatus='needSubj' order by LCsubject, LCnl, LCremainder, pubdate desc, oclc1, requestDate desc");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //  var_dump($rows);

    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  function getAreaChartDataBySubjectId($id){

    $db=$this->db;
    try {

      $stmt = $db->prepare("select count(id)as c, MONTH(requestDate) as m, YEAR(requestDate) as yyyy from requests where subject_id=:id GROUP BY YEAR(requestDate), MONTH(requestDate)");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  function getAreaChartDataByLetter($letter, $instID){

    $db=$this->db;
    try {

      $stmt = $db->prepare("select count(id)as c, MONTH(requestDate) as m, YEAR(requestDate) as yyyy from requests where LCsubject like '$letter%' and library_id=:instID GROUP BY YEAR(requestDate), MONTH(requestDate)");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }


  function getSubjectRanges($id){
    $db=$this->db;
    try {

      $stmt = $db->prepare("select * from ranges where subject_id=:id order by begLCsub, begLCnl");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  function getPieChartForLetterData($letter, $instID){


    $db=$this->db;
    try {

      $stmt = $db->prepare("select LCsubject, count(id) as c from requests where LCsubject like '$letter%' and library_id=:instID group by LCsubject order by c desc");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;


  }

  function getPieChartForSubjectId($id){


    $db=$this->db;
    try {

      $stmt = $db->prepare("select LCsubject, count(id) as c from requests where subject_id=:id group by LCsubject order by c desc");
      $stmt->execute(array(":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;


  }

  function getAllRangesCastByInst($instID){
    $db=$this->db;
    try {

      $stmt = $db->prepare("select subject_id, begLCsub, CAST(begLCnl AS decimal(10,6)) as bnl, endLCsub, CAST(endLCnl AS decimal(10,6)) as enl from ranges join subjects on ranges.subject_id=subjects.id and subjects.library_id=:instID order by begLCsub, enl");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }


  function getAllRangesCast(){
    $db=$this->db;
    try {

      $stmt = $db->prepare("select subject_id, begLCsub, CAST(begLCnl AS decimal(10,6)) as bnl, endLCsub, CAST(endLCnl AS decimal(10,6)) as enl from ranges order by begLCsub, enl");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  function getRequestsMatchingRange($bsub, $bnl, $esub, $enl, $instID){
    $db=$this->db;
    try {

      $stmt = $db->prepare("select * from requests where LCsubject>= :bsub and LCnl >= :bnl and LCsubject <= :esub and LCnl <= :enl and subject_id is null and library_id=:instID ");
      $stmt->execute(array(":bsub"=>$bsub, ":bnl"=>$bnl, ":esub"=>$esub, ":enl"=>$enl, ":instID"=>$instID));
      //$stmt->debugDumpParams();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      //var_dump($rows);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;

  }

  function getStatuses($instID){
    $db=$this->db;
    try {
      $stmt = $db->prepare("select distinct APIstatus, count(id) as total from requests where library_id=:instID group by APIstatus order by APIstatus asc;");
      $stmt->execute(array(":instID"=>$instID));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      //var_dump($rows);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  function updateRequestSubjectId($id, $subject_id){
    $db=$this->db;
    try {
      $stmt = $db->prepare("update requests set subject_id=:subject_id, APIstatus='complete' where id=:id");
      $stmt->execute(array(":subject_id"=>$subject_id, ":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      //var_dump($rows);
    }
    catch (Exception $e) {

      echo $e;
    }
  }

  function getNl(){
    $db=$this->db;
    try {
      $stmt = $db->prepare("select id, LCnumberLine from requests where LCnumberLine is not null");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;
  }

  function updateNl($id, $nl){
    $db=$this->db;
    try {
      $stmt = $db->prepare("update requests set LCnl=:nl where id=:id");
      $stmt->execute(array(":nl"=>$nl, ":id"=>$id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {

      echo $e;
    }
    return $rows;

  }

  function getLibraryDetails($id){
    $db=$this->db;
    try {
      $stmt= $db->prepare("select * from library where id=:id");
      $stmt->execute(array(":id"=>$id));
      $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e){
      echo $e;
    }

    return $rows;

  }

  function getInstIdByEmail($email){
    $db=$this->db;
    try {
      $stmt= $db->prepare("select library.id, library.name from library join selectors on library.id=selectors.library_id where selectors.email=:email");
      $stmt->execute(array(":email"=>$email));
      $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e){
      echo $e;
    }

    return $rows;



  }

  function getUserIdByEmail($email){
    $db=$this->db;
    try {
      $stmt= $db->prepare("select id from selectors where email=:email");
      $stmt->execute(array(":email"=>$email));
      $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e){
      echo $e;
    }

    return $rows;
  }

  function deleteUser($userID){
    $db=$this->db;
    try {
      $stmt= $db->prepare("delete from selectors where id=:userID");
      if ($stmt->execute(array(":userID"=>$userID))){return true;}
      else{return false;}

    }
    catch (Exception $e){
      echo $e;
    }
  }
  function updateDeletedUserSubjects($userID){
    $db=$this->db;
    try {
      $stmt= $db->prepare("update subjects set selector_id=null where selector_id=:userID");
      if ($stmt->execute(array(":userID"=>$userID))){return true;}
      else{return false;}

    }
    catch (Exception $e){
      echo $e;
    }
  }

  function addNewUser($name, $email, $instID){
    $db=$this->db;
    try {
      $stmt= $db->prepare("insert into selectors(name, email, library_id) values (:name, :email, :instID)");
      if ($stmt->execute(array(":name"=>$name,":email"=>$email, ":instID"=>$instID))){return true;}
      else{return false;}

    }
    catch (Exception $e){
      echo $e;
    }

  }

  function editUser($name, $email, $userID){
    $db=$this->db;
    try {
      $stmt= $db->prepare("update selectors set name=:name, email=:email where id=:userID");
      if ($stmt->execute(array(":name"=>$name, ":email"=>$email,  ":userID"=>$userID))){

        return true;
      }
      else{return false;}

    }
    catch (Exception $e){
      echo $e;
    }


  }

  function registerFile($newfile,$instID, $description, $orig ){
    $db=$this->db;
    try {
      $stmt= $db->prepare("insert into reportFiles(name, library_id, description, origFileName, status) values (:name, :instID, :description, :orig, 'new')");
      if ($stmt->execute(array(":name"=>$newfile, ":instID"=>$instID, ":description"=>$description, ":orig"=>$orig))){
        $id = $db->lastInsertId();
        return $id;
      }
      else{return false;}

    }
    catch (Exception $e){
      echo $e;
    }

  }






}

//$x=new mysqlFunctions();

//$x->getAllSelectors();





 ?>
