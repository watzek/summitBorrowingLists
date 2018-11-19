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


  function newFileSearch($id){
    echo "newfilesearch!";







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
      var_dump($row);
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
      var_dump($row);
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
      var_dump($row);
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
      var_dump($row);
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

    echo "<p>x: $x</p>";


    preg_match('/[A-Z]{1,3}/', $x, $matches);
    print_r($matches);

    $lcsub=$matches[0];
    $lcln=str_replace($lcsub,"",$x);

    echo "<br>$lcsub <br>";
    echo $lcln;
    echo "<br>$extra <br>";
    $cnPieces["LCsubject"]=$lcsub;
    $cnPieces["LCnumberLine"]=$lcln;
    $cnPieces["LCremainder"]=$extra;
    return $cnPieces;


  }

}


 ?>
