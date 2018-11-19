<?php
class utilities{

  function addNewUser(){

    $mysql=$this->mysql;
    if ($mysql->addNewUser($_REQUEST["name"], $_REQUEST["email"], $_REQUEST["instID"])){

      //update flash message
      $_SESSION["flash"]=true;
      $_SESSION["flashTitle"]="Success!";
      $_SESSION["flashDesc"]=$_REQUEST["name"]." has been added.";
      $_SESSION["flashType"]="alert-success";
      $newURL="https://summitstats.org";
      header('Location: '.$newURL);

    }
    else{
      echo "didn't";

    }

  }

  function processUpload(){

    $type=$_FILES["fileToUpload"]["type"];
    if ($type !="text/csv"){
      $_SESSION["flash"]=true;
      $_SESSION["flashTitle"]="Error!";
      $_SESSION["flashDesc"]="You attempted to load a non-csv file. Please upload a csv file.";
      $_SESSION["flashType"]="alert-danger";
      $newURL="https://summitstats.org/index.php?state=tools";
      header('Location: '.$newURL);


    }

    $mysql=$this->mysql;
    $instID=$_SESSION["instID"];
    $libInfo=$mysql->getLibraryDetails($instID);
    $libCode=$libInfo[0]["code"];
    $t=time();
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $target_file=$target_dir .$libCode.$t.".csv";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {

      //var_dump($_FILES);
      //var_dump($_POST);

    }
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $orig=$_FILES["fileToUpload"]["name"];
      $description=$_POST["description"];
      $newfile=$libCode.$t.".csv";
      if ($fileID=$mysql->registerFile($newfile,$instID, $description, $orig )){
        //echo $fileID;
        $a=1;
        $row = 1;
        $nohits=0;
        $hits=0;
        $noisbn=0;
        $total=0;
        if (($handle = fopen($target_file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                if ($row>1){
                $num = count($data);
                //echo "<p> $num fields in line $row: <br /></p>\n";
                //echo "<pre>";
                //var_dump($data);
                //echo "</pre>";

                $date=$data[0];
                //echo "<p>$date</p>";
                $author=$data[1];
                $title=$data[2];
                $publisher=$data[3];
                $yr=$data[4];
                preg_match('/[1-2]{1}[0-9]{3}/', $yr, $matches);
                //var_dump($matches);
                if (isset($matches[0])){$year=$matches[0];}
                else{$year="1";}
                //echo "<p>year: $year</p>";
                $isbns=$data[5];
                $is=explode(";", $isbns);
              //  echo count($is);
                $isbn1=$is[0];
                if(isset($is[1])){$isbn2=ltrim($is[1]);}
                else{$isbn2="";}
                if (strlen($isbn1)==0){$isbn1=NULL;}
                if (strlen($isbn2)==0){$isbn2=NULL;}
                $oc=str_replace("(OCoLC)", "", $data[6]);
                $oc=str_replace("ocn","", $oc);
                $oc=str_replace("ocm","", $oc);
                $ocs=explode(";", $oc);
                $oclc1=$ocs[0];
                if (isset($ocs[1])){$oclc2=ltrim($ocs[1]);}
                else{$oclc2="";}
                if (strlen($oclc1)==0){$oclc1=NULL;}
                if (strlen($oclc2)==0){$oclc2=NULL;}
                $APIstatus=$this->getFirstAPIstatus($oclc1, $oclc2, $isbn1, $isbn2);

                $oclc=$data[6];

                $entry=array();
                $entry["title"]=$title;
                $entry["pubDate"]=$year;
                $entry["isbn1"]=$isbn1;
                $entry["isbn2"]=$isbn2;
                $entry["oclc1"]=$oclc1;
                $entry["oclc2"]=$oclc2;
                $entry["requestDate"]=$date;
                $entry["author"]=$author;
                $entry["instID"]=$instID;
                $entry["fileID"]=$fileID;
                $entry["APIstatus"]=$APIstatus;
                //var_dump($entry);

                $lastId=$mysql->addRequest($entry);
                //echo "<p>lastID: $lastId</p>";

        }

                $row++;
                $total++;
                $a++;
            }
            fclose($handle);
        }


        #update count & range
        $count=$mysql->getCountByFile($fileID);
        $beg=$mysql->getBegDate($fileID);
        $end=$mysql->getEndDate($fileID);
        $mysql->updateFileCountRange($fileID, $count, $beg, $end);
//echo"<p>$count, $total</p>";

      }
      $_SESSION["flash"]=true;
      $_SESSION["flashTitle"]="Success!";
      $_SESSION["flashDesc"]="$count requests have been added to process! Please visit the files section to begin processing!";
      $_SESSION["flashType"]="alert-success";
      $newURL="https://summitstats.org/index.php?state=tools";
      header('Location: '.$newURL);


    } else {
      $_SESSION["flash"]=true;
      $_SESSION["flashTitle"]="Error!";
      $_SESSION["flashDesc"]="Sorry, there was an error uploading your file. Please try again!";
      $_SESSION["flashType"]="alert-danger";
      $newURL="https://summitstats.org/index.php?state=tools";
      header('Location: '.$newURL);
    }



  }

  function getFirstAPIstatus($oclc1, $oclc2, $isbn1, $isbn2){

    switch (true){
      case $oclc1 !=NULL:
      $status="oclc1";
      break;

      case $isbn1 !=NULL:
      $status="isbn1";
      break;

      default:
      $status="ta";

    }
    return $status;


  }

  function formatDate($d){
    $m=$d[0];
    $da=$d[1];
    $yyyy=$d[2];
    if (strlen($m)==1){$mm="0".$m;}
    else{$mm=$m;}
    if (strlen($da)==1){$dd="0".$m;}
    else{$dd=$da;}
    $fulldate="$yyyy-$mm-$dd";
    return $fulldate;
  }



}

?>