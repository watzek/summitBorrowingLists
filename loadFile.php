<?php

include ("mysql.class.php");
$x=new mysqlFunctions();

$ptypes=$x->getPatronTypes();
//var_dump($ptypes);
$groups=array();
foreach ($ptypes as $ptype){
  //var_dump($ptype);
  $id=$ptype["id"];
  $name=$ptype["ptype"];
  $groups[$id]=$name;




}
var_dump($groups);



$a=1;
$row = 1;
$nohits=0;
$hits=0;
$noisbn=0;
$total=0;
if (($handle = fopen("SummitBorrowingRequests2014-2017.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        if ($row>1){
        $num = count($data);
        echo "<p> $num fields in line $row: <br /></p>\n";
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        $d=explode("/", $data[0]);
        $date=formatDate($d);
        echo "<p>$date</p>";
        $author=$data[1];
        $title=$data[2];
        $publisher=$data[3];
        $yr=$data[4];
        preg_match('/[1-2]{1}[0-9]{3}/', $yr, $matches);
        var_dump($matches);
        $year=$matches[0];
        echo "<p>year: $year</p>";
        $isbns=$data[5];
        $is=explode(";", $isbns);
      //  echo count($is);
        $isbn1=$is[0];
        $isbn2=ltrim($is[1]);
        if (strlen($isbn1)==0){$isbn1=NULL;}
        if (strlen($isbn2)==0){$isbn2=NULL;}
        $oc=str_replace("(OCoLC)", "", $data[6]);
        $oc=str_replace("ocn","", $oc);
        $oc=str_replace("ocm","", $oc);
        $ocs=explode(";", $oc);
        $oclc1=$ocs[0];
        $oclc2=ltrim($ocs[1]);
        if (strlen($oclc1)==0){$oclc1=NULL;}
        if (strlen($oclc2)==0){$oclc2=NULL;}

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

    //    $lastId=$x->addRequest($entry);
        echo "<p>lastID: $lastId</p>";

        //if ($row==3){exit();}

}



/*
        $group=$data[6];
        $group_id=array_search("$group",$groups );
        echo "<p>id: $group_id</p>";
*/






        $row++;

/*
        echo "<p>$a)</p>";
        if (strlen($data[4])>7){
          //echo "ISBN:".$data[4]."<br>";
          $x=explode(";",$data[4]);
          $isbn= $x[0];

          if ($output=getCn($isbn)){
            echo "<p>isbn: $isbn<br/>";
            echo $output;
            echo "</p>";
            $hits++;


          }
          else{


            $nohits++;

          }



        }
        else{$noisbn++;}
        */
        $total++;


      //  if ($row==10){exit();}

/*
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";




        }
        */
        $a++;
    }
    fclose($handle);
}

echo "<p>Summary:</p>";
echo "Total Records: $total<br>";
echo "Records withoit ISBNs: $noisbn;<br>";
echo "Records with ISBNs, no API success: $nohits<br> ";
echo "Records with ISBNs, API success: $hits";

function getCn($isbn){

  //$call1="http://xisbn.worldcat.org/webservices/xid/lccn/$isbn?method=getMetadata&format=xml";

  $call1="http://classify.oclc.org/classify2/Classify?isbn=$isbn&summary=true";
  $data=simplexml_load_file($call1);
  $dewey="n/a";
  $lcc="n/a";
  $output="";
  if ($data->recommendations){
    if ($data->recommendations->ddc){$dewey=$data->recommendations->ddc->mostPopular->attributes()->sfa;}
    if ($data->recommendations->lcc){$lcc=$data->recommendations->lcc->mostPopular->attributes()->sfa;}

    $output.= "Dewey:$dewey<br>";
    $output.= "LCC: $lcc<br>";
    return $output;
  }
  else{



  }

  //echo "<a href='$call1' target='_blank'>check it</a>";
  //var_dump($data);






}

function formatOCLC(){



}

function formatDate($d){
  $m=$d[0];
  $da=$d[1];
  $yyyy="20".$d[2];
  if (strlen($m)==1){$mm="0".$m;}
  else{$mm=$m;}
  if (strlen($da)==1){$dd="0".$m;}
  else{$dd=$da;}
  $fulldate="$yyyy-$mm-$dd";
  return $fulldate;




}




?>
