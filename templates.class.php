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

      $this->azBox();
      $this->allSubjectsBox();

      $this->userManagement();
      //var_dump($_SESSION);
    }

    else{
      $this->hello();
    }

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




  function letter($letter){
    $mysql=$this->mysql;
    $rows=$mysql->getAllByLetter($letter);
    $count=number_format(count($rows));
    $title="Requests - $letter ($count)";
    $pcdata=$mysql->getPieChartForLetterData($letter);
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
      $this->azBox();
      $this->table($rows, $title, $dewey=true);
    }
    else{
      $this->breadcrumb($letter);
      $this->azBox();

      $this->table($rows, $title);
      ?><div class="row">
      <div class="col-lg-8">
<?php
$areaData=$mysql->getAreaChartDataByLetter($letter);
$formatted=$this->formatAreaChartData($areaData);
$this->areaChart("letterAreaChart", "Borrowing over time", $formatted);
?>

      </div>
      <div class="col-lg-4"><?php
      $this->pieChart("Requests within call number", $labels, $data, "myPieChart");
      ?></div><?php
      ?></div><?php
    }


  }

  function selector($id){

    $mysql=$this->mysql;
    $info=$mysql->getSelector($id);
    $name=$info[0]["name"];
    $subjects=$mysql->getSelectorSubjects($id);
    $subjectText="<p>";
    foreach ($subjects as $subject){

      $subjectId=$subject["id"];
      $subjectName=$subject["subject"];
      $subjectText.="<a href='index.php?state=subject&subject_id=$subjectId'>$subjectName </a> | ";

    }
    $subjectText=rtrim( $subjectText, "| ");





    /*output*/
    $this->breadcrumb($name);
    $this->textBox("Subjects", $subjectText, "globe");
  //  $this->pieChart();

  }

  function subjects(){

    $mysql=$this->mysql;

    $this->breadcrumb("Subjects", $level=null);
    $this->allSubjectsBox();

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
      $subject=$info[0]["subject"];
      $selector=$info[0]["selector"];
      $selector_id=$info[0]["selector_id"];
      $requests=$mysql->getRequestsBySubjectId($id);
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

    $title="Requests - $subject ($count)";
    $this->breadcrumb($subject, $level);
    $this->textBox("Subject information", $boxtext, "info-circle");
    $this->table($requests, $title);
    if ($id!="unclassified"){
      ?><div class="row">
      <div class="col-lg-8">

        <?php
        $formatted=$this->formatAreaChartData($areaData);
        $this->areaChart("subjectAreaChart", "Borrowing over time", $formatted);

      ?>
    </div>
      <div class="col-lg-4"><?php
      $this->pieChart("Requests within subject", $labels, $data, "myPieChart");
      ?></div><?php
      ?></div><?php



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


    //$needSubj=$c[0]["needSubj"];
    $this->breadcrumb("Tools");

    if (isset($_SESSION["flash"]) && $_SESSION["flash"]==true){$this->flashMessage();}
    $status=$mysql->getStatuses();

    $report="";

  //  var_dump($status)
;
    $green=array("complete", "needSubj", "Dewey");
    $yellow=array("new");
    $red=array("102ta", "102oclc", "XMLerror");
    foreach ($status as $stat){
      $label=$stat["APIstatus"];
      $c=number_format($stat["total"]);
      if (in_array($label, $green)){$color="green"; $icon="check";}
      if (in_array($label, $yellow)){$color="orange"; $icon="thumbs-up";}
      if (in_array($label, $red)){$color="red"; $icon="exclamation-triangle";}

      $report.="<p><i class='fa fa-$icon' style='color:$color'></i> $label: $c";
      if ($color=="red" || $color=="orange"){$report.=" <a href='index.php?state=hunt&status=$label'>look for more</a>"; }
      $report.="</p>";
    }




?>

<div class="row">
  <div class="col-lg-6">

    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-wrench"></i> Status check</div>

      <div class="card-body">


        <?= $report;?>

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
        if ($status=="new"){

          $st="| <a href='https://summitstats.org/index.php?state=hunt&status=new&id=$id'>process file</a>";
        }
        else{$st=$status;}


        $row= "<tr><td>$desc</td><td>$count</td><td>$beg - $end</td><td>$st</td><td>x</td></tr>";

          echo $row;


      }


    }



    ?>
  </table>

  </div>

  <div class="card-footer small text-muted">I'm a footer!</div>


  </div>


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

  function allSubjectsBox(){
    $mysql=$this->mysql;
    $subjects=$mysql->getAllSubjects();

    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-link"></i> Requests by subject</div>
      <div class="card-body">
      <?php
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
       ?>
      </div>
      <div class="card-footer small text-muted"></div>
    </div>
<?php
  }

  function azBox(){
    ?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-link"></i> Requests by call number</div>
      <div class="card-body">
      <?php
      $letters=array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "Z");
      $range="<p>";
      foreach ($letters as $letter){
        $range.="<a href='index.php?state=letter&letter=$letter'>$letter</a> | ";
      }
    //  $range=rtrim( $range, "| ");
      $range.="<a href='index.php?state=letter&letter=dewey'>Dewey</a></p>";
      echo $range;
       ?>
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
  $colors=array("#4D4D4D", "#5DA5DA", "#FAA43A", "#60BD68", "#F17CB0", "#B2912F", "#B276B2", "#DECF3F", "#F15854");
  $c=count($data);
  if ($c>9){$c==9;}
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



  function table($rows, $title, $dewey=false){
?>
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> <?= $title; ?></div>
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
            echo "<p>$name | <button data-target='#edituser' data-toggle='modal' class='editUser' id='$id'>edit user</button> | edit user subjects</p>";


          }

        }



        ?>



        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userModal">
          Add User
        </button>



      </div>
      <div class="card-footer small text-muted"></div>
    </div>

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
                <input name="name" type="text" class="form-control" id="fullname" placeholder="e.g. Jane Smith">
              </div>


              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">

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
$this->editUserModal();

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
                    <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">

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

    <script>
    $(document).on("click", ".editUser", function () {
         var id = $(this).attr('id');
         console.log(id);
         //$(".modal-body #bookId").val( myBookId );
         // As pointed out in comments,
         // it is superfluous to have to manually call the modal.
         // $('#addBookDialog').modal('show');
    });

    </script>



    <?php

  }



}

 ?>
