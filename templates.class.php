<?php
include("utilities.class.php");


class templates extends utilities{

  function __construct($mysql){
    $this->mysql=$mysql;
  }

  function home(){

    $this->breadcrumb("home");


    if (isset($_SESSION["validUser"]) && $_SESSION["validUser"]==true){
      if (isset($_SESSION["flash"]) && $_SESSION["flash"]==true){$this->flashMessage();}
//var_dump($_SESSION);
  $subjectText=$this->getUserSubjects($_SESSION["userID"]);
  $this->textBox("My Subjects", $subjectText, "globe");


      $this->azBox($_SESSION["instID"]);
      $this->allSubjectsBox($_SESSION["instID"]);

      //$this->userManagement();
      //var_dump($_SESSION);
    }

    else{
      $this->hello();
    }

  }

  function docs(){
    ?>
    <!--
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-info"></i> Docs / Help</div>
      <div class="card-body">


      <p style="margin-top: 30px;">Rough outline:</p>
      <ul>
        <li>Create Users</li>
        <li>Create Subjects</li>
        <li>Get Alma Analytics reports</li>
        <li>Load & process reports</li>
        <li>Match requests to subjects</li>
      </ul>

      </div>
      <div class="card-footer small text-muted"></div>
    </div>
  -->




  <div class="card mb-3">
<h5 class="card-header">Need help?</h5>
<div class="card-body">
  <!--<h5 class="card-title">Special title treatment</h5>-->

  If you have questions or run into issues when using this site, please chime in on <a href='https://orbiscascade.slack.com/messages/CEG4P3QTT' target='_blank'>Alliance Slack</a>.


</div>
</div>


    <div class="card mb-3">
  <h5 class="card-header">Step 1 - Create Users</h5>
  <div class="card-body">
    <!--<h5 class="card-title">Special title treatment</h5>-->
    <div class="alert alert-info" role="alert">
    <p class="card-text">Creating users allows you to later associate subjects with users, and will let them login with their own Google account.</p>
  </div>
    <ol>
      <li>Click "Manage Users" in the left-hand menu.</li>
      <li>In the User Management box, click "Add User".</li>
      <li>Enter the user's full name and email address, then click Submit. Note: email MUST be a GMail address!</li>
      <li>Repeat this step as needed to create more users for your organization.</li>
    </ol>
  </div>
</div>

<div class="card mb-3">
<h5 class="card-header">Step 2 - Create Subjects</h5>
<div class="card-body">
<!--<h5 class="card-title">Special title treatment</h5>-->
<div class="alert alert-info" role="alert">
<p class="card-text">Creating subjects with call number ranges lets you categorize Summit requests.</p>
</div>
<ol>
  <li>Click "Manage Subjects" in the left-hand menu.</li>
  <li>In the "Manage Subjects" box, click "Add Subject".</li>
  <li>Enter the subject name (e.g. "Political Science"), followed by one or more call number ranges (click "add another range" to enter more than one).</li>
  <li>In the drop-down menu, select the user responsible for this subject.</li>
  <li>Click Submit.</li>
  <li>Repeat this step as needed to create more users for your organization.</li>
</ol>
</div>
</div>

<div class="card mb-3">
<h5 class="card-header">Step 3 - Get Alma Analytics reports</h5>
<div class="card-body">
<!--<h5 class="card-title">Special title treatment</h5>-->
<!--
<div class="alert alert-info" role="alert">
<p class="card-text"> These reports have the Summit requests!</p>
</div>
-->
<p>Once you're in Alma Analytics, follow these steps to generate a report:</p>
<ul>
<li>Click "Catalog", and then navigate to and open the Orbis Cascade Alliance folder:<br><img src='images/report location.png' width='400px;'></li>
<li>Open the "Summit Borrowing Requests w/Date Prompt" report:<br><img src='images/click open.png' width='400px;'></li>
<li>You'll be prompted to enter a start and finish date range. Do so, and click "ok".</li>

<li>Once the report is generated, export as CSV:<br><img src='images/export csv.png' width='400px;'></li>
</ul>
</div>
</div>

<div class="card mb-3">
<h5 class="card-header">Step 4 - Load and process reports</h5>
<div class="card-body">
<!--<h5 class="card-title">Special title treatment</h5>
<div class="alert alert-info" role="alert">
<p class="card-text"> </p>
</div>-->
<p>Click the menu option "Process Alma Reports", and follow these steps:</p>
<ul>
  <li>In the Upload Summit Borrowing Report box, click "Choose File", and select the Alma Analytics report you downloaded in step 3. Files should be less than 40Mb.</li>
  <li>Enter a description in the File Description input. You may want to enter a time range for future reference (e.g. Jan-Dec 2018).</li>
  <li>Click "Upload Report". After the file uploads, it will appear in the "Your Uploaded Files" box.</li>
  <li>In the Status Check box, you should now see a section titled "Awaiting Processing", with a button reading "Find call numbers for <x> Requests". Click this button.</li>
    <li>The system is now querying the WorldCat Classify API for call numbers. This can take a while, depending upon the number of requests to process. You can monitor the progress in the progress bar.</li>
    <li>When done processing, the page will refresh. If you notice the processing is stuck, just refresh the page, and click the button again.</li>
</ul>

</div>
</div>

<div class="card mb-3">
<h5 class="card-header">Step 5 - Match requests to subjects</h5>
<div class="card-body">
<!--<h5 class="card-title">Special title treatment</h5>
<div class="alert alert-info" role="alert">
<p class="card-text"> This is where you populate any subject reports with </p>
</div>-->
<p>In the Process Alma Reports page, go to the Status Check box, and click Look for Subject matches.</p>
<p>You should click this button anytime you:</p>
<ul>
<li>Finish getting call numbers for requests</li>
<li>Create a new subject</li>
<li>Edit the call number ranges of an existing subject</li>
</ul>
<p>After clicking this button, any matches will appear on a matched subject page.</p>
</div>
</div>

<?php


  }


  function manageUsers(){

    $this->breadcrumb("Manage Users", $level=null);

    if (isset($_SESSION["validUser"]) && $_SESSION["validUser"]==true){
      if (isset($_SESSION["flash"]) && $_SESSION["flash"]==true){$this->flashMessage();}


      $this->userManagement();
      //var_dump($_SESSION);
    }

    else{
      $this->hello();
    }

  }





  function flashMessage(){
    /* types include alert-success,  */
    $type=$_SESSION["flashType"];
    $title=$_SESSION["flashTitle"];
    $desc=$_SESSION["flashDesc"];
    ?>
    <div class="alert <?= $type ?>" role="alert">
      <strong><?= $title ?></strong> <?= $desc ?>
    </div>
    <?php

    unset($_SESSION["flash"]);
    unset($_SESSION["flashType"]);
    unset($_SESSION["flashTitle"]);
    unset($_SESSION["flashDesc"]);


  }



/*
  function letter1($letter){
    $mysql=$this->mysql;
    $instID=$_SESSION["instID"];
    $rows=$mysql->getAllByLetter($letter, $instID);
    $count=number_format(count($rows));
    $title="Requests - $letter ($count)";
    $pcdata=$mysql->getPieChartForLetterData($letter, $instID);
    $data=array();
    $labels=array();
    foreach ($pcdata as $pc){
      $cn=$pc["LCsubject"];
      $c=$pc["c"];

      array_push($data, $c);
      array_push($labels, $cn);


    }

    if ($letter=="dewey"){
      $this->breadcrumb("Dewey");
      $this->azBox($_SESSION["instID"]);
      $this->table($rows, $title, $dewey=true);
    }
    else{
      $this->breadcrumb($letter);
      $this->azBox($_SESSION["instID"]);

      $this->table($rows, $title);
      ?><div class="row">
      <div class="col-lg-8">
<?php
$areaData=$mysql->getAreaChartDataByLetter($letter, $instID);
//var_dump($areaData);
if(count($areaData)>0){
  $formatted=$this->formatAreaChartData($areaData);
  $this->areaChart("letterAreaChart", "Borrowing over time", $formatted);
}
?>

      </div>
      <div class="col-lg-4"><?php
      if(count($data)>0){
      $this->pieChart("Requests within call number", $labels, $data, "myPieChart");
    }
      ?></div><?php
      ?></div><?php
    }


  }
*/

  function letter($letter){
    $mysql=$this->mysql;
    $instID=$_SESSION["instID"];
    $rows=$mysql->getAllByLetter($letter, $instID);
    $count=number_format(count($rows));
    $title="Requests - $letter ($count)";
    $pcdata=$mysql->getPieChartForLetterData($letter, $instID);
    $data=array();
    $labels=array();
    foreach ($pcdata as $pc){
      $cn=$pc["LCsubject"];
      $c=$pc["c"];

      array_push($data, $c);
      array_push($labels, $cn);


    }

    if ($letter=="dewey"){
      $this->breadcrumb("Dewey");
      $this->azBox($_SESSION["instID"]);
      $content=$this->table2($rows, $title, $dewey=true);
      echo $content;
    }
    else{
      $this->breadcrumb($letter);
      $this->azBox($_SESSION["instID"]);

      $content=$this->table2($rows, $title);
      echo $content;
      ?><div class="row">
      <div class="col-lg-8">
<?php
$areaData=$mysql->getAreaChartDataByLetter($letter, $instID);
//var_dump($areaData);
if(count($areaData)>0){
  $formatted=$this->formatAreaChartData($areaData);
  $this->areaChart("letterAreaChart", "Borrowing over time", $formatted);
}
?>

      </div>
      <div class="col-lg-4"><?php
      if(count($data)>0){
      $this->pieChart("Requests within call number", $labels, $data, "myPieChart");
    }
      ?></div><?php
      ?></div><?php
    }


  }


  function viewall(){
    $mysql=$this->mysql;
    $instID=$_SESSION["instID"];
    //$rows=$mysql->getAllByLetter($letter, $instID);
    $rows=$mysql->getAll($instID);
    var_dump($rows);
    $count=number_format(count($rows));
    $title="Requests - $letter ($count)";
    $pcdata=$mysql->getPieChartForLetterData($letter, $instID);
    $data=array();
    $labels=array();
    foreach ($pcdata as $pc){
      $cn=$pc["LCsubject"];
      $c=$pc["c"];

      array_push($data, $c);
      array_push($labels, $cn);


    }

    if ($letter=="dewey"){
      $this->breadcrumb("Dewey");
      $this->azBox($_SESSION["instID"]);
      $content=$this->table2($rows, $title, $dewey=true);
      echo $content;
    }
    else{
      $this->breadcrumb($letter);
      $this->azBox($_SESSION["instID"]);

      $content=$this->table2($rows, $title);
      echo $content;
      ?><div class="row">
      <div class="col-lg-8">
<?php
$areaData=$mysql->getAreaChartDataByLetter($letter, $instID);
//var_dump($areaData);
if(count($areaData)>0){
  $formatted=$this->formatAreaChartData($areaData);
  $this->areaChart("letterAreaChart", "Borrowing over time", $formatted);
}
?>

      </div>
      <div class="col-lg-4"><?php
      if(count($data)>0){
      $this->pieChart("Requests within call number", $labels, $data, "myPieChart");
    }
      ?></div><?php
      ?></div><?php
    }


  }


  function getUserSubjects($id){
    $mysql=$this->mysql;
    $info=$mysql->getSelector($id);

    $name=$info[0]["name"];
    $subjects=$mysql->getSelectorSubjects($id);
    if(count($subjects)==0){$subjectText="<p>You have no subjects.</p>";}
    else{
      $subjectText="<p>";
      foreach ($subjects as $subject){

        $subjectId=$subject["id"];
        $subjectName=$subject["subject"];
        $subjectText.="<a href='index.php?state=subject&subject_id=$subjectId'>$subjectName </a> | ";

      }
      $subjectText=rtrim( $subjectText, "| ");
    }

    return $subjectText;

  }

  function selector($id){

    $mysql=$this->mysql;
    $info=$mysql->getSelector($id);
    $name=$info[0]["name"];

/*
    $subjects=$mysql->getSelectorSubjects($id);
    $subjectText="<p>";
    foreach ($subjects as $subject){

      $subjectId=$subject["id"];
      $subjectName=$subject["subject"];
      $subjectText.="<a href='index.php?state=subject&subject_id=$subjectId'>$subjectName </a> | ";

    }
    $subjectText=rtrim( $subjectText, "| ");
*/
    $subjectText=$this->getUserSubjects($id);





    /*output*/
    $this->breadcrumb($name);
    $this->textBox("Subjects", $subjectText, "globe");
  //  $this->pieChart();

  }

  function subjects(){

    $mysql=$this->mysql;
    ?>
    <script>
    var q="q!!";
    </script>
    <?php

    $this->breadcrumb("Manage Subjects", $level=null);
    if (isset($_SESSION["flash"]) && $_SESSION["flash"]==true){$this->flashMessage();}
    //$this->allSubjectsBox($_SESSION["instID"]);
    $this->manageSubjectsBox($_SESSION["instID"]);

  }



  function subject($id){

    $mysql=$this->mysql;
    if ($id=="unclassified"){
      $subject="Outside subject ranges";
      $requests=$mysql->getRequestsNoSubjects();
      $count=number_format(count($requests));
      $boxtext="<p>These requests have LC Classification, but do not fall within any of the predefined call number ranges for selectors.</p>";


    }
    else{

      $info=$mysql->getSubjectInfo($id);
      //var_dump($info);
      $subject=$info[0]["subject"];
      $selector=$info[0]["selector"];
      $selector_id=$info[0]["selector_id"];
      $requests=$mysql->getRequestsBySubjectId($id);

      $topResults=$mysql->getRequestsGroupedBySubject($id);
      $count=number_format(count($requests));
      $ranges=$mysql->getSubjectRanges($id);
      $callnumbers="";
      foreach ($ranges as $range){
        $blcsub=$range["begLCsub"];
        $blcnl=$range["begLCnl"];
        $elcsub=$range["endLCsub"];
        $elcnl=$range["endLCnl"];
        $cn="$blcsub $blcnl - $elcsub $elcnl, ";
        $callnumbers.=$cn;


      }
      $callnumbers=rtrim($callnumbers, ", ");

      $boxtext="<p>Subject: $subject<br/>Selector: <a href='index.php?state=selector&id=$selector_id'>$selector</a><br/>";
      $boxtext.="Call Numbers: $callnumbers</p>";
      $areaData=$mysql->getAreaChartDataBySubjectId($id);

      $pcdata=$mysql->getPieChartForSubjectId($id);
      $data=array();
      $labels=array();
      foreach ($pcdata as $pc){
        $cn=$pc["LCsubject"];
        $c=$pc["c"];

        array_push($data, $c);
        array_push($labels, $cn);


      }


    }
    $level["label"]="Subjects";
    $level["link"]="index.php?state=subjects";

    $title="All Requests - $subject ($count)";
    $this->breadcrumb($subject);
    $this->textBox("Subject information", $boxtext, "info-circle");
    $content=$this->table2($requests, $title);
    $content.=$this->tableTopRequests($topResults, $subject);
    echo $content;
    //$this->table($requests, $title);



    if ($id!="unclassified"){
      ?><div class="row">
      <div class="col-lg-8">

        <?php
        if(count($areaData)>0){
        $formatted=$this->formatAreaChartData($areaData);
        $this->areaChart("subjectAreaChart", "Borrowing over time", $formatted);
      }

      ?>
    </div>

    <?php if(count($data)>0){ ?>
      <div class="col-lg-4"><?php

      $this->pieChart("Requests within subject", $labels, $data, "myPieChart");
      ?></div><?php
      }


      ?></div><?php
      foreach($topResults as $result){


      }

      //var_dump($topResults);



    }




  }

  function hunt($label){
    $mysql=$this->mysql;
    $search=new cnSearch($mysql);

    //$search->oclcNewSearch();

    echo $label;

    switch($label){



      case "new":
      echo "find new stuff!";




      $search->newFileSearch($_REQUEST["id"]);

      break;


      case "102ta":

      $search->newTitleAuthorSearch();


      break;

    }

  }

  function tools(){
    $mysql=$this->mysql;
    $instID=$_SESSION["instID"];
    $tbp=$mysql->getRequestsToBeProcessed($instID);


    //$needSubj=$c[0]["needSubj"];
    $this->breadcrumb("Process Alma Reports");

    if (isset($_SESSION["flash"]) && $_SESSION["flash"]==true){$this->flashMessage();}
    $status=$mysql->getStatuses($instID);

    $report="";
    $newReport="";

  //  var_dump($status)

    $green=array("complete", "needSubj", "Dewey");
    $yellow=array("new", "oclc1", "ta", "oclc2", "isbn1", "isbn2");
    $red=array("102ta", "102oclc", "XMLerror");
    $black=array("unable to resolve");
    $labels=array("ta"=>"Title & Author only", "oclc1"=>"Has OCLC number", "oclc2"=>"Has OCLC number", "isbn1"=>"Has ISBN", "isbn2"=>"Has ISBN", "unable to resolve"=>"Unable to find call number", "needSubj"=>"LC CN Found, not matched to subject", "Dewey"=>"Dewey Call Number Found", "complete"=>"Matched to subject");
//var_dump($status);
$newStati=array();
    $a=0;



    foreach ($status as $stat){

      $la=$stat["APIstatus"];
      $label=$labels[$la];
      $c=number_format($stat["total"]);
      $newStati[$la]=$c;

      if (in_array($la, $green)){$color="green"; $icon="check";}
      if (in_array($la, $yellow)){$color="orange"; $icon="search";}
      if (in_array($la, $red)){$color="red"; $icon="exclamation-triangle";}
      if (in_array($la, $black)){$color="black"; $icon="frown";}

      $report.="<p><i class='fas fa-$icon' style='color:$color'></i> $label: $c";

      if($la=="needSubj" && $c>0){ $report.=" | <a href='index.php?state=match&instID=$instID'>look for subject matches</a>";}

      $report.="</p>";
    }
  //  var_dump($newStati);
    if (isset($newStati["complete"]) || isset($newStati["needSubj"]) || isset($newStati["Dewey"]) || isset($newStati["unable to resolve"])){$newReport.="<h5>Processed</h5>";}
    else{$a++;}
    if (isset($newStati["complete"])){
      $newReport.="<p><i class='fas fa-check' style='color:green'></i> Matched to Subject: ".$newStati["complete"]."</p>";
    }
    if (isset($newStati["needSubj"])){
      $newReport.="<p><i class='fas fa-check' style='color:green'></i> LC call number found, not matched to subject: ".$newStati["needSubj"]."</p>";
    }
    if (isset($newStati["Dewey"])){
      $newReport.="<p><i class='fas fa-check' style='color:green'></i> Dewey Call Number Found: ".$newStati["Dewey"]."</p>";
    }
    if (isset($newStati["unable to resolve"])){
      $newReport.="<p><i class='fas fa-meh' style='color:green'></i> Unable to find call number: ".$newStati["unable to resolve"]."</p>";
    }
    if(isset($newStati["needSubj"]) && $newStati["needSubj"]>0){

      $newReport.="<form method='get' action='index.php'><input type='hidden' name='state' value='match'><input type='hidden' name='instID' value='$instID'><button  class='btn btn-primary' type='submit'>Look for subject matches</button></form>";
    }



    if(isset($newStati["ta"]) || isset($newStati["oclc1"]) || isset($newStati["isbn1"]) || isset($newStati["isbn2"]) || isset($newStati["oclc2"])){$newReport.="<hr><h5>Awaiting Processing</h5>";}
    else{$a++;}
    if (isset($newStati["oclc1"]) || isset($newStati["oclc2"])){
      if(isset($newStati["oclc1"])){$x=$newStati["oclc1"];}
      else{$x=0;}
      if(isset($newStati["oclc2"])){$y=$newStati["oclc2"];}
      else{$y=0;}
      $x=(int)str_replace(",","",$x);
      $y=(int)str_replace(",","",$y);
      $t=number_format($x+$y);


      //echo "$x, $y";
      $newReport.="<p><i class='fas fa-search' style='color:orange'></i> Has OCLC number: ".$t."</p>";
    }
    if (isset($newStati["isbn1"]) || isset($newStati["isbn2"])){
      if(isset($newStati["isbn1"])){$x=$newStati["isbn1"];}
      else{$x=0;}
      if(isset($newStati["isbn2"])){$y=$newStati["isbn2"];}
      else{$y=0;}
      $t=$x+$y;
      $newReport.="<p><i class='fas fa-search' style='color:orange'></i> Has ISBN number: ".$t."</p>";
    }

    if(isset($newStati["ta"])){
      $newReport.="<p><i class='fas fa-search' style='color:orange'></i> Has Title and/or Author only: ".$newStati["ta"]."</p>";


    }
    if($a==2){$newReport="<p>No records have been loaded yet. Try uploading an Alma Analytics report.</p>";}



?>

<div class="row">
  <div class="col-lg-6">
    <?php
    #$this->summaryBox($_SESSION["instID"]);
    ?>

    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-wrench"></i> Status check</div>

      <div class="card-body">


        <?php
        //echo $report;

        echo $newReport;

        if ($tbp>0){
          ?>
          <button id='hunt' class="btn btn-primary" type="button">Find call numbers for <?= $tbp?> Requests</button>

          <div id='progressContainer' style='margin-top:20px;'>
          <div class="progress" >
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progressBar"></div>
          </div>
        </div>
          <script>

          $("#hunt").click(function(){
            sessionStorage.setItem("count", 1);
            sessionStorage.setItem("check", "");

            $(this).text("Searching...(0.0% complete)");
            $(this).attr("disabled", true);
            $.ajax({
              method: "POST",
              url: "background.php",
              data: { instID: <?= $_SESSION["instID"];?>, fx: "start" }
            });
            //$(".progress").css("display", "inline");
             search = setInterval(doStuff, 2000);


          });
          function doStuff() {

            var t=<?= $tbp ?>


            $.ajax({
              method: "POST",
              url: "background.php",
              data: { instID: <?= $_SESSION["instID"];?>, fx: "checkProgress" }
            })
              .done(function( msg ) {


                console.log(msg);
                w=(1-(msg/t))*100;
                console.log("W:"+w);
                var rounded = Math.round( w * 10 ) / 10;

              //  a=Math.floor(Math.random() * 100);
                $("#progressBar").css("width", w+"%");
                $("#hunt").text("Searching... ("+rounded+"% complete)");

                if (!sessionStorage.check){sessionStorage.setItem("check", msg);}
                if (!sessionStorage.count){sessionStorage.setItem("count", 1);}
                else{
                  if (msg==sessionStorage.check){sessionStorage.count= Number(sessionStorage.count)+1;}
                  console.log(sessionStorage.count);
                  if(sessionStorage.count>5){
                    clearInterval(search);
                    $("#progressContainer").html("<p>Sorry, there may be an issue with processing the current request. Refresh the page and try again.</p>");


                  }

                }
/*
                if(msg==1){
                //  https://summitstats.org/index.php?state=tools
                  clearInterval(search);
                  window.location.replace("https://summitstats.org/index.php?state=finishTools");


                }
*/

                if(w==100){
                  clearInterval(search);
                  location.reload();
                }

              });

          }

          </script>
          <?php
        }

        ?>

      </div>
      <div class="card-footer small text-muted"></div>
    </div>


  </div>



  <div class="col-lg-6">

    <?php
$text="<p>Upload report here.</p>";


$text='<form action="upload.php" method="post" enctype="multipart/form-data">
    Select report to upload:
    <div class="custom-file form-group">
      <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload" required>
      <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
    </div>
    <div class="form-group">
      <label for="formGroupExampleInput">Example label</label>
      <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Example input">
    </div>

    <!--<input type="file" name="fileToUpload" id="fileToUpload">-->
    <div class=" form-group">
    <button class="btn btn-primary" type="submit">Upload report</button>
    </div>
    <!--<input type="submit" value="Upload Report" name="submit">-->
</form>';

$icon="upload";
//$this->textBox("Upload report", $text, $icon);

$this->uploadCard();

     ?>

  </div>
</div>
<div class="row">
  <div class="col-lg-12">
<?php  $this->filesCard();?>
</div>

</div>



  <?php

  //$this->uploadCard();
  }

function uploadCard(){
?>


  <div class="card mb-3">
  <div class="card-header">
    <i class="fa fa-upload"></i> Upload Summit Borrowing Report</div>
  <div class="card-body">
    <form action="index.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="exampleFormControlFile1">Select a report to upload</label>
        <input type="file" class="form-control-file" id="exampleFormControlFile1" name="fileToUpload">
      </div>
      <div class="form-group">
        <label for="formGroupExampleInput2">File Description</label>
        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="e.g. November 2018 report" name="description" required>
      </div>
      <div class=" form-group">
        <button class="btn btn-primary" type="submit">Upload report</button>
      </div>
      <input type="hidden" name="submit" value="true">
      <input type="hidden" name="instID" value="<?= $_SESSION["instID"] ?>">
      <input type="hidden" name="state" value="processUpload">
    </form>
  </div>

  <div class="card-footer small text-muted">I'm a footer!</div>


  </div>


<?php

}

function filesCard(){

  $mysql=$this->mysql;
  $instID=$_SESSION["instID"];
  $files=$mysql->getFilesByInstitution($instID);



?>


  <div class="card mb-3">
  <div class="card-header">
    <i class="fa fa-file-csv"></i> Your uploaded Files</div>
  <div class="card-body">
    <table class="table table-bordered" id="filesDataTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>Description</th>
          <th>Count</th>
          <th>Range</th>
          <th>Status</th>
          <th>Delete</th>

        </tr>
      </thead>


    <?php #var_dump($files);?>
    <?php
    if(count($files)==0){
      ?><tr><td colspan="5">Your institution has no files!</td></tr><?php
    }
    else{
      foreach ($files as $file){
        $id=$file["id"];
        $desc=$file["description"];
        $status=$file["status"];
        $count=$file["count"];
        $beg=$file["begDate"];
        $end=$file["endDate"];
        $tbp=$mysql->getRequestsToBeProcessedByFile($id);

        $st=$tbp;

        $x=(($count-$tbp)/$count)*100;
        $y=number_format($x, 2);
        $st=$y."% processed";


        $row= "<tr><td>$desc</td><td>$count</td><td>$beg - $end</td><td>$st</td><td><span  id='$id' data-toggle='modal' data-target='#deleteFile' data-fileID='$id'><i class='fas fa-times-circle' style='color:red;cursor:pointer;'></i></span></td></tr>";

          echo $row;


      }


    }



    ?>
  </table>

  </div>

  <div class="card-footer small text-muted">I'm a footer!</div>


  </div>
  <?php $this->deleteFileModal(); ?>


<?php

}


/* widgets*/

  function breadcrumb($text, $level=null){


    ?>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="index.php">Home</a>
      </li>
      <?php
      if ($level){
        $label=$level["label"];
        $link=$level["link"];
        ?>
      <li class="breadcrumb-item active">
        <a href="<?= $link;?>"><?= $label; ?></a>

      </li>
        <?php

      }
      if ($text !="home"){
        ?>
      <li class="breadcrumb-item active"><?= $text; ?></li>
        <?php
      }
       ?>
    </ol>

    <?php
  }

  /* takes mysql output, preps for 12-month area chart   */
  function formatAreaChartData($data){



    $months=array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    $firstYear=$data[0]["yyyy"];
    $firstMonth=$data[0]["m"];
    $firstCount=$data[0]["c"];


    $formatted=array();
    $usedMonths=array();
    end($data);         // move the internal pointer to the end of the array
    $key = key($data);  // fetches the key of the element pointed to by the internal pointer
    $lastMonth=$data[$key]["m"];
    $lastCount=$data[$key]["c"];
    $lastYear=$data[$key]["yyyy"];

    for ($x=$firstMonth; $x<=12; $x++){
      $formatted[$firstYear][$x]=0;

    }
    if ($lastYear - $firstYear>1){
      for($x=$firstYear+1; $x<$lastYear; $x++){
        for ($y=1; $y<=12; $y++){
          $formatted[$x][$y]=0;
        }
      }
    }
    for ($x=1; $x<=$lastMonth; $x++){
      $formatted[$lastYear][$x]=0;
    }
    foreach ($data as $d){
      $c=intval($d["c"]);
      $m=intval($d["m"]);
      $yyyy=intval($d["yyyy"]);
      $formatted[$yyyy][$m]=$c;
    }

    return $formatted;

  }

  function areaChart($id, $title, $data){
    $colors=array("#4D4D4D", "#5DA5DA", "#FAA43A", "#60BD68", "#F17CB0", "#B2912F", "#B276B2", "#DECF3F", "#F15854");
    $n=0;
    $max=0;
    $datasets="";
    foreach ($data as $yy=>$d){
      $datapoints="";
      foreach ($d as $point){
        //echo "<p>$d: $point</p>";
        $datapoints.="$point,";
        if ($point>$max){$max=$point;}
      }
      $datapoints=rtrim($datapoints, ",");
      $c=$colors[$n];

      //echo "<p>$yy: $d</p>";
      $datasets.="{
      label: '$yy',
      lineTension: 0.3,
      backgroundColor: '',
      borderColor: '$c',
      pointRadius: 5,
      pointBackgroundColor: '$c',
      pointBorderColor: '$c',
      pointHoverRadius: 5,
      pointHoverBackgroundColor: '$c',
      pointHitRadius: 20,
      pointBorderWidth: 5,
      data: [$datapoints],
      },";
      $n++;

    }
    $datasets=rtrim($datasets, ",");


    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-area-chart"></i> <?= $title;?></div>
      <div class="card-body">
        <canvas id="<?= $id;?>" width="100%" height="30"></canvas>
      </div>
      <div class="card-footer small text-muted"></div>
    </div>
    <script>
    jQuery(document).ready(function(){
    var ctx = document.getElementById("<?= $id; ?>");
    var <?= $id; ?> = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [<?= $datasets; ?>],
      },
      options: {
        scales: {
          xAxes: [{
            time: {
              unit: 'date'
            },
            gridLines: {
              display: false
            },
            ticks: {
              maxTicksLimit: 7
            }
          }],
          yAxes: [{
            ticks: {
              min: 0,
              max: <?= $max; ?>,
              maxTicksLimit: 5
            },
            gridLines: {
              color: "rgba(0, 0, 0, .125)",
            }
          }],
        },
        legend: {
          display: true
        }
      }
    });
  });
    </script>
<?php
  }


  function textBox($heading, $text, $icon){

    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-<?=$icon;?>"></i> <?= $heading; ?></div>
      <div class="card-body">
        <?= $text; ?>
      </div>
      <div class="card-footer small text-muted"></div>
    </div>
<?php



  }

  function manageSubjectsBox($instID){

    $mysql=$this->mysql;
    $subjects=$mysql->getAllSubjects($instID);
    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-link"></i> Manage Subjects</div>
      <div class="card-body">
        <!--<p style='color:red;'>NOTE: edit & delete functions still in progress!! -jm</p>-->
      <?php


      if(count($subjects)>0){

      $range="<p>";
      foreach ($subjects as $subject){
        $id=$subject["id"];
        $sub=$subject["subject"];
        $userID=$subject["selector_id"];
        $range.="<p>$sub | <a data-toggle='modal' data-target='#editSubjectModal' data-id='$id' data-subject='$sub' data-userid='$userID' style='cursor:pointer;color:blue;'>edit</a> | <a style='cursor:pointer;color:blue;' class='deleteSubject'>delete</a></p>  ";
      }
      $range=rtrim( $range, "| ");
    //  $range.="<a href='index.php?state=letter&letter=dewey'>Dewey</a></p>";
      echo $range."</p>";

    //  echo "<p><a href='index.php?state=subject&subject_id=unclassified'>View requests outside subject ranges</a></p>";

    }
    else{echo "<p>Your institution does not have any subjects set up yet. Click the button below to add one!</p>";}
       ?>
       <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSubjectModal">
         Add Subject
       </button>


      </div>
      <div class="card-footer small text-muted"></div>
    </div>
    <script>
    $(".deleteSubject").click(function(){
      alert("delete subject is under construction...come back soon!")
    });
    </script>
<?php
$this->addSubjectModal();
$this->editSubjectModal();
//$this->deleteSubjectModal();





  }



  function allSubjectsBox($instID){
    $mysql=$this->mysql;
    $subjects=$mysql->getAllSubjects($instID);


    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-link"></i> All Requests by subject</div>
      <div class="card-body">
      <?php
      if(count($subjects)>0){

      $range="<p>";
      foreach ($subjects as $subject){
        $id=$subject["id"];
        $sub=$subject["subject"];
        $range.="<a href='index.php?state=subject&subject_id=$id'>$sub</a> | ";
      }
      $range=rtrim( $range, "| ");
    //  $range.="<a href='index.php?state=letter&letter=dewey'>Dewey</a></p>";
      echo $range."</p>";

      echo "<p><a href='index.php?state=subject&subject_id=unclassified'>View requests outside subject ranges</a></p>";

    }
    else{echo "<p>Your institution does not have any subjects set up yet. <a href=''>Manage subjects</a>.</p>";}
       ?>
      </div>
      <div class="card-footer small text-muted"></div>
    </div>
<?php
  }

  function azBox($instID){
    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-link"></i> All Requests by call number</div>
      <div class="card-body">
      <?php
      $letters=array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "Z");
      $range="<p>";
      foreach ($letters as $letter){
        $range.="<a href='index.php?state=letter&letter=$letter'>$letter</a> | ";
      }
    //  $range=rtrim( $range, "| ");
      $range.="<a href='index.php?state=letter&letter=dewey'>Dewey</a></p>";
      $range.="<a href='index.php?state=viewall'>View all (in development)<a/>";
      echo $range;
       ?>
      </div>
      <div class="card-footer small text-muted"></div>
    </div>
<?php

  }

  function summaryBox($instID){
    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fas fa-thermometer-three-quarters"></i> Summary</div>

      <div class="card-body">

      </div>
      <div class="card-footer small text-muted"></div>
    </div>
<?php


  }





function pieChart($title, $labels, $data, $piechartID){
  $labs="";
  $t=0;
  foreach ($labels as $label){
    if ($t==9){break;}
    $labs.="\"$label\",";
    $t++;
  }
  $labs=rtrim($labs, ",");
  $datas="";
  $t=0;
  foreach ($data as $d){
    if ($t==9){break;}
    $datas.="$d,";
    $t++;
  }
  $datas=rtrim($datas, ",");
  $colors=array("#4D4D4D", "#5DA5DA", "#FAA43A", "#60BD68", "#F17CB0", "#B2912F", "#B276B2", "#DECF3F", "#F15854", "#fff");
  $c=count($data);
  if ($c>9){$c=9;}
  $bgcolors="";
  for($x = 0; $x < $c; $x++){

    $col=$colors[$x];
    $bgcolors.="\"$col\",";
  }
  $bgcolors=rtrim($bgcolors, ",");
?>
  <!-- Example Pie Chart Card-->
  <div class="card mb-3">
    <div class="card-header">
      <i class="fa fa-pie-chart"></i> <?= $title; ?></div>
    <div class="card-body">
      <canvas id="<?= $piechartID; ?>" width="100%" height="100"></canvas>
    </div>
    <div class="card-footer small text-muted"></div>
  </div>
  <script>
  jQuery(document).ready(function(){
  var ctx = document.getElementById("<?= $piechartID; ?>");
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: [<?= $labs; ?>],
      datasets: [{
        data: [<?= $datas; ?>],
        backgroundColor: [<?=   $bgcolors; ?>],
      }],
    },
  });
  });
  </script>


  <?php
}




  function card(){
    ?>
    <!-- Example Social Card-->
    <div class="card mb-3">
      <a href="#">
        <img class="card-img-top img-fluid w-100" src="https://unsplash.it/700/450?image=281" alt="">
      </a>
      <div class="card-body">
        <h6 class="card-title mb-1"><a href="#">Jeffery Wellings</a></h6>
        <p class="card-text small">Nice shot from the skate park!
          <a href="#">#kickflip</a>
          <a href="#">#holdmybeer</a>
          <a href="#">#igotthis</a>
        </p>
      </div>
      <hr class="my-0">
      <div class="card-body py-2 small">
        <a class="mr-3 d-inline-block" href="#">
          <i class="fa fa-fw fa-thumbs-up"></i>Like</a>
        <a class="mr-3 d-inline-block" href="#">
          <i class="fa fa-fw fa-comment"></i>Comment</a>
        <a class="d-inline-block" href="#">
          <i class="fa fa-fw fa-share"></i>Share</a>
      </div>
      <div class="card-footer small text-muted">Posted 1 hr ago</div>
    </div>
<?php


  }



  function table2($rows, $title, $dewey=false){
    #get csv download deets
    switch($_REQUEST["state"]){
      case "subject":
      $suffix="&type=subject&subject_id=".$_REQUEST["subject_id"];
      break;

      case "letter":
      case "letter2":
      $suffix="&type=letter&subject_id=".$_REQUEST["letter"];
      break;




    }

    $content="";


$content='    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> '.$title.'  <a href=index.php?state=dlcsv'.$suffix.' style="float:right"><i class="fas fa-download"></i></a></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="requestDataTable" width="100%" cellspacing="0" style="display:none">
            <thead>
              <tr>
                <th>Call Number</th>
                <th>Title</th>
                <th>Author</th>
                <th>Pub. Date</th>
                <th>Request Date</th>

              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Call Number</th>
                <th>Title</th>
                <th>Author</th>
                <th>Pub. Date</th>
                <th>Request Date</th>

              </tr>
            </tfoot>
            <tbody>';

            foreach ($rows as $row){
            $id=$row["id"];
            if ($dewey==true){$callnumber=$row["dewey"];}
            else{
              $callnumber=$row["LCsubject"].$row["LCnumberLine"];
              if (strlen($row["LCremainder"])>0){$callnumber.=" .".$row["LCremainder"];}
            }


            $title=rtrim($row["title"], "/");
            $author=$row["author"];
            $pubDate=$row["pubdate"];
            if ($pubDate==1){$pubDate="";}
            $requestDate=$row["requestDate"];

            $content.='<tr>
              <td>'.$callnumber.'</td>
              <td>'.$title.'</td>
              <td>'.$author.'</td>
              <td>'.$pubDate.'</td>
              <td>'.$requestDate.'</td>

            </tr>';


            }




        $content.='    </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted"></div>
    </div>


    <script>
    jQuery(document).ready(function(){$("#requestDataTable").dataTable( {
      "order": [],
      "initComplete": function () {
      $("#requestDataTable").show();

      }
    });
  });

    </script>';

    return $content;


  }



  function tableTopRequests($rows, $title, $dewey=false){
    #get csv download deets
    switch($_REQUEST["state"]){
      case "subject":
      $suffix="&type=subject&subject_id=".$_REQUEST["subject_id"];
      break;

      case "letter":
      case "letter2":
      $suffix="&type=letter&subject_id=".$_REQUEST["letter"];
      break;




    }

    $content="";


$content='    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Top Requests in '.$title.'  <span id="trCount"></span></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="topRequestsDataTable" width="100%" cellspacing="0" style="display:none">
            <thead>
              <tr>
                <th>Call Number</th>
                <th>Title</th>
                <th>Author</th>
                <th>Pub. Date</th>
                <th>Count</th>

              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Call Number</th>
                <th>Title</th>
                <th>Author</th>
                <th>Pub. Date</th>
                <th>Count</th>

              </tr>
            </tfoot>
            <tbody>';
            $x=0;
            foreach ($rows as $row){
              if($row["c"]>1){
                //$id=$row["id"];
                if ($dewey==true){$callnumber=$row["dewey"];}
                else{
                  $callnumber=$row["LCSubject"].$row["LCnumberLine"];
                  if (strlen($row["LCremainder"])>0){$callnumber.=" .".$row["LCremainder"];}
                }


                $title=rtrim($row["title"], "/");
                $author=$row["author"];
                $pubDate=$row["pubdate"];
                $count=$row["c"];

                $content.='<tr>
                  <td>'.$callnumber.'</td>
                  <td>'.$title.'</td>
                  <td>'.$author.'</td>
                  <td>'.$pubDate.'</td>
                  <td>'.$count.'</td>

                </tr>';
                $x++;

            }


            }




        $content.='    </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted"></div>
    </div>


    <script>
    jQuery(document).ready(function(){$("#topRequestsDataTable").dataTable( {
      "order": [],
      "initComplete": function () {
      $("#topRequestsDataTable").show();
      $("#trCount").text("('.$x.')");

      }
    });
  });

    </script>';

    return $content;


  }

  function table($rows, $title, $dewey=false){
    #get csv download deets
    switch($_REQUEST["state"]){
      case "subject":
      $suffix="&type=subject&subject_id=".$_REQUEST["subject_id"];
      break;

      case "letter":
      $suffix="&type=letter&subject_id=".$_REQUEST["letter"];
      break;




    }


?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> <?= $title; ?>  <a href='index.php?state=dlcsv<?= $suffix?>' style="float:right"><i class="fas fa-download"></i> (in progress)</a></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="requestDataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Call Number</th>
                <th>Title</th>
                <th>Author</th>
                <th>Pub. Date</th>
                <th>Request Date</th>

              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Call Number</th>
                <th>Title</th>
                <th>Author</th>
                <th>Pub. Date</th>
                <th>Request Date</th>

              </tr>
            </tfoot>
            <tbody>
            <?php
            foreach ($rows as $row){
            $id=$row["id"];
            if ($dewey==true){$callnumber=$row["dewey"];}
            else{
              $callnumber=$row["LCsubject"].$row["LCnumberLine"];
              if (strlen($row["LCremainder"])>0){$callnumber.=" .".$row["LCremainder"];}
            }


            $title=rtrim($row["title"], "/");
            $author=$row["author"];
            $pubDate=$row["pubdate"];
            $requestDate=$row["requestDate"];
            ?>
            <tr>
              <td><?= $callnumber; ?></td>
              <td><?= $title; ?></td>
              <td><?= $author; ?></td>
              <td><?= $pubDate; ?></td>
              <td><?= $requestDate; ?></td>

            </tr>

            <?php
            }


            ?>

            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer small text-muted"></div>
    </div>
    <script>
    jQuery(document).ready(function(){
    $('#requestDataTable').dataTable( {
        "order": []
    } );
  });
    </script>

<?php


  }


  function hello(){
    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-book-reader"></i> Welcome!</div>
      <div class="card-body">
      Hello! With this site, you can upload your Summit Borrowing reports from Alma Analytics, and process them to get call number reports.
      If you're interested in trying this, email <a href='mailto:jeremym@lclark.edu'>Jeremy</a>.

      <p style="margin-top: 30px;">Institutions using this site:</p>
      <ul>
        <li>Lewis & Clark College</li>
      </ul>

      </div>
      <div class="card-footer small text-muted"></div>
    </div>

<?php

  }

  function editUsers(){

    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-smile"></i> Add user</div>
      <div class="card-body">
        This site lets you do the following:
        <ul>
          <li>Load Summit borrowing lists from Alma Analytics</li>
          <li>Process these reports using the Worldcat Classify API to retrieve call numbers</li>
          <li>Set up custom subject call number ranges (e.g. Physics: QC 1 - QC 999)</li>
          <li>Set up selector profiles, and match them to the custom ranges</li>
          <li>Download title lists in CSV format</li>
        </ul>




      </div>
      <div class="card-footer small text-muted"></div>
    </div>




<?php




  }

  function userManagement(){
    $mysql=$this->mysql;
    $users=$mysql->getAllUsers($_SESSION["instID"]);
    //var_dump($users);
    $c=count($users);


    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-user-cog"></i> User Management</div>
      <div class="card-body">
        <?php if ($c==0){echo "<p>No current users.</p>";}
        else{
          foreach ($users as $user){
            $id=$user["id"];
            $name=$user["name"];
            $email=$user["email"];
            echo"<p>$name | <a data-target='#edituser' data-name='$name' data-userid='$id' data-email='$email' data-toggle='modal' class='editUser' id='$id' style='color:blue;cursor:pointer;'>edit user</a> | <a   data-toggle='modal' data-target='#deleteUser'data-name='$name' data-userid='$id' style='color:blue;cursor:pointer;'>delete user</a></p>";


          }

        }



        ?>



        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userModal">
          Add User
        </button>


      </div>
      <div class="card-footer small text-muted"></div>
    </div>







<?php

$this->addUserModal();
$this->editUserModal();
$this->deleteUserModal();


  }






  function about(){
    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-smile"></i> About Summit Borrowing Lists</div>
      <div class="card-body">
        This site lets you do the following:
        <ul>
          <li>Load Summit borrowing lists from Alma Analytics</li>
          <li>Process these reports using the Worldcat Classify API to retrieve call numbers</li>
          <li>Set up custom subject call number ranges (e.g. Physics: QC 1 - QC 999)</li>
          <li>Set up selector profiles, and match them to the custom ranges</li>
          <li>Download title lists in CSV format</li>
        </ul>
        <p>This site was created by Jeremy McWilliams at Lewis & Clark's Watzek Library. If you have questions/comments/suggestions, chime in on <a href='https://orbiscascade.slack.com/messages/CEG4P3QTT' target='_blank'>Alliance Slack</a>!</p>



      </div>
      <div class="card-footer small text-muted"></div>
    </div>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-people-carry"></i> Acknowledgements</div>
      <div class="card-body">
        Huge thanks to:
        <ul>
          <li>Jen Jacobs, Head of Access Services, Watzek Library</li>
          <li>Erica Jensen, Visual Resources & Fine Arts Librarian, Watzek Library</li>
          <li>Elaine Hirsch, Associate Director, Watzek Library</li>

      </div>
      <div class="card-footer small text-muted"></div>
    </div>
<?php

  }

  function addUserModal(){
?>
    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="get">
              <div class="form-group">
                <label for="fullname">Full Name</label>
                <input name="name" type="text" class="form-control" id="fullname" placeholder="e.g. Jane Smith" required>
              </div>


              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required>

              </div>
              <input type="hidden" name="instID" value="<?= $_SESSION["instID"] ?>">
              <input type="hidden" name="state" value="adduser">

              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
          <div class="modal-footer">

          </div>
        </div>
      </div>
    </div>
    <?php

  }

  function editUserModal(){

    ?>

        <!-- Modal -->
        <div class="modal fade" id="edituser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="get">
                  <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input name="name" type="text" class="form-control" id="fullname" placeholder="e.g. Jane Smith" value="1234">
                  </div>


                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">

                  </div>
                  <input type="hidden" name="state" value="edituser">
                  <input type="hidden" name="userID" value="" id="userID">

                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
              <div class="modal-footer">

              </div>
            </div>
          </div>
        </div>

    <script>

    $('#edituser').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var name = button.data('name') // Extract info from data-* attributes
      var email = button.data('email')
      var userid = button.data('userid')
      console.log(name);
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)

      modal.find("#fullname").attr("value", name);
      modal.find("#email").attr("value", email);
      modal.find("#userID").attr("value", userid);
      //modal.find('.modal-body input').val(recipient)
    });
    </script>
    <?php
  }

  function deleteUserModal(){

    ?>

        <!-- Modal -->
        <div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteUserLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h5>Are you sure you want to delete <span id="deleteFullName"></span>?</h5>
                <div>
                <form method="get">

                  <input type="hidden" id="deleteUserID" name="userID" value="">
                  <input type="hidden" name="state" value="deleteUser">

                  <button type="submit" class="btn btn-primary" style="float:left;background-color:red;">Delete User!</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="float:right;">Cancel</button>
              </div>
              <div class="modal-footer" style="clear:both;">

              </div>
            </div>
          </div>
        </div>
        <script>

        $('#deleteUser').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var name = button.data('name') // Extract info from data-* attributes
          var userid = button.data('userid')
          // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
          // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
          var modal = $(this)
          modal.find("#deleteFullName").text(name);
          modal.find("#deleteUserID").attr("value", userid);
          //modal.find('.modal-body input').val(recipient)
        });
        </script>

    <?php
  }



  function addSubjectModal(){
    $mysql=$this->mysql;

    $rows=$mysql->getAllUsers($_SESSION["instID"]);



?>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Subject</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" action="index.php">
              <div class="form-group">
                <label for="fullname">Subject Name</label>
                <input name="subject" type="text" class="form-control" id="fullname" placeholder="e.g. Biology" required>
              </div>





              <div id="lc-rows">

              <div class="form-row">
                <div class="form-group col-md-2">
                  <label for="inputEmail4">Beg. Class</label>
                  <input type="text" class="form-control" placeholder="e.g QH" name="begLCsub[]" required>
                </div>
                <div class="form-group col-md-3">
                  <label for="inputEmail4">Beg. Number</label>
                  <input type="text" class="form-control" placeholder="e.g. 1" name="begLCnl[]" required>
                </div>
                <div class="form-group col-md-1">
                  <label for="inputEmail4"></label>
                  <div>to</div>

                </div>
                <div class="form-group col-md-2">
                  <label for="inputEmail4">End Class</label>
                  <input type="text" class="form-control" placeholder="e.g. QH" name="endLCsub[]" required>
                </div>
                <div class="form-group col-md-3">
                  <label for="inputEmail4">End Number</label>
                  <input type="text" class="form-control" placeholder="e.g. 705.5" name="endLCnl[]" required>
                </div>



              </div>
            </div><!--lc-rows-->
            <div class="form-row">
              <p id="addRangeRow" style="color:blue;text-decoration:underline;cursor:pointer;">Add another range</p>
            </div>
            <div class="form-row">

              <label for="exampleFormControlSelect1">Add User</label>
              <select class="form-control" id="exampleFormControlSelect1" name="user">
                <?php
                foreach ($rows as $row){
                  $id=$row["id"];
                  $name=$row["name"];
                  echo "<option value='$id'>$name</option>";



                }


                ?>

              </select>
            </div>

              <input type="hidden" name="instID" value="<?= $_SESSION["instID"] ?>">
              <input type="hidden" name="state" value="addsubject">

              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
          <div class="modal-footer">

          </div>
        </div>
      </div>
    </div>

<script>

var row='<div class=form-row><div class="form-group col-md-2"><input class=form-control name=begLCsub[] placeholder="e.g QH"></div><div class="form-group col-md-3"><input class=form-control name=begLCnl[] placeholder="e.g. 1"></div><div class="form-group col-md-1"><div>to</div></div><div class="form-group col-md-2"><input class=form-control name=endLCsub[] placeholder="e.g. QH"></div><div class="form-group col-md-3"><input class=form-control name=endLCnl[] placeholder="e.g. 705.5"></div></div>';
$("#addRangeRow").click(function(){
  $("#lc-rows").append(row);


})

</script>

    <?php

  }

  function editSubjectModal(){
    $mysql=$this->mysql;
    $rows=$mysql->getAllUsers($_SESSION["instID"]);
    ?>

        <!-- Modal -->
        <div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Subject (Under Construction!!)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="index.php">
                  <div class="form-group">
                    <label for="fullname">Subject Name</label>
                    <input name="subject" type="text" class="form-control" id="subjectname" placeholder="e.g. Biology" required>
                  </div>

                  <div id="lc-rows-edit">

                </div><!--lc-rows-edit-->
                <div class="form-row">
                  <p id="addRangeRowEdit" style="color:blue;text-decoration:underline;cursor:pointer;">Add another range</p>
                </div>
                <div class="form-row">

                  <label for="exampleFormControlSelect1">Select User</label>
                  <select class="form-control" id="edit-selectors" name="user">
                    <?php
                    foreach ($rows as $row){
                      $id=$row["id"];
                      $name=$row["name"];
                      //echo "<option value='$id'>$name</option>";



                    }


                    ?>

                  </select>
                </div>

                  <input type="hidden" name="subjectID" id="subjectID" value="">
                  <input type="hidden" name="state" value="editSubject">

                  <button type="submit" class="btn btn-primary">Submit</button>
                </form>
              </div>
              <div class="modal-footer">

              </div>
            </div>
          </div>
        </div>

    <script>

    $('#editSubjectModal').on('show.bs.modal', function (event) {
      $("#lc-rows-edit").html("");
      $('option:selected', this).remove();
      $("#edit-selectors").html("");
      var userid=null;
      var button = $(event.relatedTarget) // Button that triggered the modal
      var subject = button.data('subject') // Extract info from data-* attributes
      var id = button.data('id');
      var userid=button.data('userid');
      console.log(id);
      console.log(userid);
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)

      modal.find("#subjectname").attr("value", subject);
      modal.find("#subjectID").attr("value", id);
    //  $("#edit-selectors option[value="+userid+"]").attr('selected', 'selected');
<?php
    foreach ($rows as $row){
      $id=$row["id"];
      $name=$row["name"];
      ?>modal.find("#edit-selectors").append("<option value='<?=$id?>'><?=$name?></option>");<?php
      //echo "<option value='$id'>$name</option>";



    }
    ?>
    $("#edit-selectors option[value="+userid+"]").attr('selected', 'selected');

      $.ajax({
        method: "POST",
        url: "background.php",
        data: { id: id, fx: "getSubjectRanges" }
      })
        .done(function( r ) {
          console.log(r);
          ranges=JSON.parse(r)
          for(var i = 0; i < ranges.length; i++) {
            var obj = ranges[i];
            var begLCnl=obj.begLCnl;
            var begLCsub=obj.begLCsub;
            var endLCsub=obj.endLCsub;
            var endLCnl=obj.endLCnl;
            var row='<div class="form-row"><div class="form-group col-md-2"> <label for="inputEmail4">Beg. Class</label> <input type="text" class="form-control" placeholder="e.g QH" name="begLCsub[]" required value="'+begLCsub+'"></div><div class="form-group col-md-3"> <label for="inputEmail4">Beg. Number</label> <input type="text" class="form-control" placeholder="e.g. 1" name="begLCnl[]" required value="'+begLCnl+'"></div><div class="form-group col-md-1"> <label for="inputEmail4"></label><div>to</div></div><div class="form-group col-md-2"> <label for="inputEmail4">End Class</label> <input type="text" class="form-control" placeholder="e.g. QH" name="endLCsub[]" required value="'+endLCsub+'"></div><div class="form-group col-md-3"> <label for="inputEmail4">End Number</label> <input type="text" class="form-control" placeholder="e.g. 705.5" name="endLCnl[]" required value="'+endLCnl+'"></div></div>';
            modal.find("#lc-rows-edit").append(row);
            //("#lc-rows").append("hello");

          }
          $("#addRangeRowEdit").click(function(){
            console.log("row");
            modal.find("#lc-rows-edit").append(newRow);


          })


        });

        var newRow='<div class=form-row><div class="form-group col-md-2"><input class=form-control name=begLCsub[] placeholder="e.g QH"></div><div class="form-group col-md-3"><input class=form-control name=begLCnl[] placeholder="e.g. 1"></div><div class="form-group col-md-1"><div>to</div></div><div class="form-group col-md-2"><input class=form-control name=endLCsub[] placeholder="e.g. QH"></div><div class="form-group col-md-3"><input class=form-control name=endLCnl[] placeholder="e.g. 705.5"></div></div>';



      //modal.find('.modal-body input').val(recipient)
    });
    </script>
    <?php
  }

  function deleteFileModal(){
    ?>
        <!-- Modal -->
        <div class="modal fade" id="deleteFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete this file?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                By deleting this file, you will also delete any records associated with this file.

                <form method="get" action="index.php">
                  <button type="submit" class="btn btn-primary">Delete</button>
                  <input type='hidden' name='fileID' value='' id='fileIDinput'>
                  <input type='hidden' name='state' value='deleteFile'>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" style="float:right;">Cancel</button>
                </form>
              </div>
              <div class="modal-footer">
              </div>
            </div>
          </div>
        </div>

    <script>

    $('#deleteFile').on('show.bs.modal', function (event) {


      var button = $(event.relatedTarget) // Button that triggered the modal
      var fileID = button.data('fileid') // Extract info from data-* attributes
      console.log(fileID);
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find("#fileIDinput").attr("value", fileID);
      //modal.find('.modal-body input').val(recipient)
    });
    </script>
    <?php

  }



}

 ?>
