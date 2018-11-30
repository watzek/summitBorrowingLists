<?php

//include ("mysql.class.php");
include ("classify.class.php");


//newTitleSearch();

//$oclc="55149618";
//$oclc="5514961x";

//$classify->searchByOclc($oclc);

//$rows=$mysql->getNewRequestsWithOclc();

class cnSearch{

  function __construct($mysql){

    //$mysql=new mysqlFunctions();
    $classify=new classify($mysql);
    $this->mysql=$mysql;
    $this->classify=$classify;


  }

  function newInstSearch(){




  }


  function newFileSearch($id){



      ?>
      <!--
<h2>Processing!</h2>
<img src="https://i.gifer.com/OEh8.gif">
-->
      <?php
    $mysql=$this->mysql;
    echo "newfilesearch!";
    $this->oclc1Search($id);
    $this->oclc2Search($id);
    $this->isbn1Search($id);
    $this->isbn2Search($id);
    $this->titleAuthorSearch($id);




    $mysql->updateFileStatus($id, "processed");
    echo "<p>Ok, it's done!</p>";
/*
    $_SESSION["flash"]=true;
    $_SESSION["flashTitle"]="Success";
    $_SESSION["flashDesc"]="File has been processed.";
    $_SESSION["flashType"]="alert-success";
    $newURL="https://summitstats.org/index.php?state=tools";
    header('Location: '.$newURL);
*/







  }

  function oclc1Search($instID){
    $c=0;
    $mysql=$this->mysql;
    $classify=$this->classify;
    $rows=$mysql->getNewRequestsByStatusAndInst("oclc1", $instID);
    //$rows=$mysql->getNewRequestsWithIsbn();
    //when oclc yields nothing
    //var_dump($rows);

//exit();

    foreach ($rows as $row){
      $id=$row["id"];
      var_dump($row);
      $oclc1=$row["oclc1"];
      $oclc2=$row["oclc2"];
      $isbn1=$row["isbn1"];
      $isbn2=$row["isbn2"];

      echo "oclc1: $oclc1, oclc2: $oclc2";


      if ($cn=$classify->searchByOclc($oclc1, "oclc1")){
        if (substr( $cn, 0, 5 ) === "DEWEY"){
          $cn=str_replace("DEWEY", "", $cn);
          $mysql->addDewey($id, $cn);
          $mysql->updateStatus($id, "Dewey");
        }
        elseif($cn=="102oclc"){
          if ($oclc2){$status="oclc2";}
          if (!($oclc2) && $isbn1){$status="isbn1";}
          if (!($oclc2) && !($isbn1)){$status="ta";}
          $mysql->updateStatus($id, $status);
        }
        else{
          echo "pieces!!!";
          $cnPieces=$this->handleCn($cn);
          var_dump($cnPieces);
        //  exit();
          if ($mysql->updateCN($id, $cnPieces)){
            $mysql->updateStatus($id, "needSubj");

            echo "<p>worked!</p>";
          }
          else{echo "<p>didn't!</p>";}
        }

      }
      else{
        if ($oclc2){$status="oclc2";}
        if (!($oclc2) && $isbn1){$status="isbn1";}
        if (!($oclc2) && !($isbn1)){$status="ta";}
        $mysql->updateStatus($id, $status);
      }
    }

  }
  function oclc2Search($instID){
    $c=0;
    $mysql=$this->mysql;
    $classify=$this->classify;
    $rows=$mysql->getNewRequestsByStatusAndInst("oclc2", $instID);
    //$rows=$mysql->getNewRequestsWithIsbn();
    //when oclc yields nothing
    //var_dump($rows);

//exit();

    foreach ($rows as $row){
      $id=$row["id"];
      //var_dump($row);
      $oclc1=$row["oclc1"];
      $oclc2=$row["oclc2"];
      $isbn1=$row["isbn1"];
      $isbn2=$row["isbn2"];

      //echo "oclc1: $oclc1, oclc2: $oclc2";

      //exit();
      if ($cn=$classify->searchByOclc($oclc1, "oclc1")){
        if (substr( $cn, 0, 5 ) === "DEWEY"){
          $cn=str_replace("DEWEY", "", $cn);
          $mysql->addDewey($id, $cn);
          $mysql->updateStatus($id, "Dewey");
        }
        elseif($cn=="102oclc"){

          if ($isbn1){$status="isbn1";}
          else{$status="ta";}
          $mysql->updateStatus($id, $status);
        }
        else{
          $cnPieces=$this->handleCn($cn);
          if ($mysql->updateCN($id, $cnPieces)){
            $mysql->updateStatus($id, "needSubj");

            echo "<p>worked!</p>";
          }
          else{echo "<p>didn't!</p>";}
        }

      }
      else{
        if ($isbn1){$status="isbn1";}
        else{$status="ta";}
        $mysql->updateStatus($id, $status);

      }
    }

  }
  function isbn1Search($instID){
    $mysql=$this->mysql;
    $classify=$this->classify;
    $rows=$mysql->getNewRequestsByStatusAndInst("isbn1", $instID);

    foreach ($rows as $row){
      $id=$row["id"];
      var_dump($row);
      $isbn1=$row["isbn1"];
      $isbn2=$row["isbn2"];
      if ($cn=$classify->searchByIsbn($isbn1)){

        //echo "CN!!";
        echo "CN: $cn";
        if (substr( $cn, 0, 5 ) === "DEWEY"){
          $cn=str_replace("DEWEY", "", $cn);
          $mysql->addDewey($id, $cn);
          $mysql->updateStatus($id, "Dewey");
        }



        switch($cn){


          case "102isbn":
          if($isbn2){$status="isbn2";}
          else{$status="ta";}
          $mysql->updateStatus($id, $status);

          break;

          default:

          $cnPieces=$this->handleCn($cn);
          if ($mysql->updateCN($id, $cnPieces)){
            $mysql->updateStatus($id, "needSubj");

            //echo "<p>worked!</p>";
          }
          else{
            //echo "<p>didn't!</p>";
          }

        }




      }
      else{
        if ($isbn2){$status="isbn2";}
        else{$status="ta";}
        $mysql->updateStatus($id, $status);


      }
    }
  }
  function isbn2Search($instID){
    $mysql=$this->mysql;
    $classify=$this->classify;
    $rows=$mysql->getNewRequestsByStatusAndInst("isbn2", $instID);

    foreach ($rows as $row){
      $id=$row["id"];
      //var_dump($row);

      $isbn2=$row["isbn2"];
      if ($cn=$classify->searchByIsbn($isbn2)){

        switch($cn){
          case "102isbn":
          $status="ta";
          $mysql->updateStatus($id, $status);

          break;

          default:

          $cnPieces=$this->handleCn($cn);
          if ($mysql->updateCN($id, $cnPieces)){
            $mysql->updateStatus($id, "needSubj");

            echo "<p>worked!</p>";
          }
          else{echo "<p>didn't!</p>";}

        }




      }
      else{
        $status="ta";
        $mysql->updateStatus($id, $status);


      }
    }
  }

  function titleAuthorSearch($instID){
    $mysql=$this->mysql;
    $classify=$this->classify;
    //$rows=$mysql->getTitleAuthorRequests($fileID);
    $rows=$mysql->getNewRequestsByStatusAndInst("ta", $instID);
    foreach ($rows as $row){
      $id=$row["id"];
  //    var_dump($row);
      $title=$row["title"];
      $author=$row["author"];
      if ($cn=$classify->searchByTitleAndAuthor($title, $author, $id, $mysql)){
          echo $cn;
          if (substr( $cn, 0, 5 ) === "DEWEY"){
            $cn=str_replace("DEWEY", "", $cn);
            $mysql->addDewey($id, $cn);
            $mysql->updateStatus($id, "Dewey");
          }
          else{
            if ($mysql->updateCN($id, $cnPieces)){
              $mysql->updateStatus($id, "needSubj");

              echo "<p>worked!</p>";
            }
            else{echo "<p>didn't!</p>";}

          }
        $cnPieces=$this->handleCn($cn);


      }
      else{


      }
    }



  }




  function oclcNewSearch(){
    $c=0;
    $mysql=$this->mysql;
    $classify=$this->classify;
    $rows=$mysql->getNewRequestsWithOclc();
    //$rows=$mysql->getNewRequestsWithIsbn();
    //when oclc yields nothing
    //var_dump($rows);
    foreach ($rows as $row){
      $id=$row["id"];
  //    var_dump($row);
      $oclc1=$row["oclc1"];
      if ($cn=$classify->searchByOclc($oclc1)){
        if (substr( $cn, 0, 5 ) === "DEWEY"){
          $cn=str_replace("DEWEY", "", $cn);
          $mysql->addDewey($id, $cn);
          $mysql->updateStatus($id, "Dewey");
        }
        elseif($cn=="102oclc"){$mysql->updateStatus($id, "102oclc");}
        else{
          $cnPieces=$this->handleCn($cn);
          if ($mysql->updateCN($id, $cnPieces)){
            $mysql->updateStatus($id, "needSubj");

            echo "<p>worked!</p>";
          }
          else{echo "<p>didn't!</p>";}
        }

      }
      else{
        $mysql->updateStatus($id, "noClass");
      }
    }

  }

  function isbnNewSearch(){
    $mysql=$this->mysql;
    $classify=$this->classify;
    $rows=$mysql->getNewRequestsWithIsbn(); //when oclc yields nothing

    foreach ($rows as $row){
      $id=$row["id"];
    //  var_dump($row);
      $isbn1=$row["isbn1"];
      if ($cn=$classify->searchByIsbn($isbn1)){
        $cnPieces=$this->handleCn($cn);
        if ($mysql->updateCN($id, $cnPieces)){
          $mysql->updateStatus($id, "needSubj");

          echo "<p>worked!</p>";
        }
        else{echo "<p>didn't!</p>";}

      }
      else{


      }
    }
  }


  function newTitleSearch(){
    $mysql=$this->mysql;
    $classify=$this->classify;
    $rows=$mysql->getNewRequestsNoIdentifiers();
    foreach ($rows as $row){
      $id=$row["id"];
    //  var_dump($row);
      $title=$row["title"];
      //$author=$row["author"];
      if ($cn=$classify->searchByTitle($title)){
        $cnPieces=$this->handleCn($cn);
        if ($mysql->updateCN($id, $cnPieces)){
          $mysql->updateStatus($id, "needSubj");

          echo "<p>worked!</p>";
        }
        else{echo "<p>didn't!</p>";}

      }
      else{


      }
    }
  }

  function newTitleAuthorSearch(){
    $mysql=$this->mysql;
    $classify=$this->classify;
    $rows=$mysql->getNewRequestsNoIdentifiers();
    foreach ($rows as $row){
      $id=$row["id"];
  //    var_dump($row);
      $title=$row["title"];
      $author=$row["author"];
      if ($cn=$classify->searchByTitleAndAuthor($title, $author, $id, $mysql)){
        $cnPieces=$this->handleCn($cn);
        if ($mysql->updateCN($id, $cnPieces)){
          $mysql->updateStatus($id, "needSubj");

          echo "<p>worked!</p>";
        }
        else{echo "<p>didn't!</p>";}

      }
      else{


      }
    }



  }

  function handleCn($cn){
    $cnPieces=array();
    $pieces=explode(".", $cn);
    //var_dump($pieces);

    $size=count($pieces);
    //echo $size;

    switch ($size) {
      case 1:

      $x=$pieces[0];
      $extra=NULL;

        break;

      case 2:
      if (is_numeric($pieces[1])){
        echo "yep";
        $x=implode(".",$pieces);
        $extra=NULL;
      }
        else{
          echo "nope";
          $x=$pieces[0];
          $extra=$pieces[1];
        }



      break;

      case 3:

      $x=$pieces[0].".".$pieces[1];
      $extra=$pieces[2];


      break;
      default:
        # code...
        break;
    }

    //echo "<p>x: $x</p>";


    preg_match('/[A-Z]{1,3}/', $x, $matches);
    //print_r($matches);

    $lcsub=$matches[0];
    $lcln=str_replace($lcsub,"",$x);

    $lcnlDecimal=number_format($lcln, 6, '.', '');

    echo "<br>$lcsub <br>";
    echo $lcln;

    echo "<br>$extra <br>";
    $cnPieces["LCsubject"]=$lcsub;
    $cnPieces["LCnumberLine"]=$lcln;
    $cnPieces["LCremainder"]=$extra;
    $cnPieces["LCnl"]=$lcnlDecimal;
    return $cnPieces;


  }

}


 ?>
