<?php

$mysql=new mysqlFunctions();
 ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
  <a class="navbar-brand" href="index.php"> <i class="fas fa-hippo"></i>    Summit Borrowing Lists  <?php if (isset($_SESSION["instName"])){echo " - ".$_SESSION["instName"];}?></a>
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>



  <div style=' width:100%;text-align:right; float:right; color: white;'><?=$loginStatus?></div>


  <div class="collapse navbar-collapse" id="navbarResponsive">
    <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Home">
        <a class="nav-link" href="index.php">
          <i class="fa fa-fw fa-home"></i>
          <span class="nav-link-text">Home</span>
        </a>
      </li>


<?php if (isset($_SESSION["validUser"]) && $_SESSION["validUser"]==true) { ?>

      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Selectors">
        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
          <i class="fa fa-fw fa-users"></i>
          <span class="nav-link-text">Selectors</span>
        </a>
        <ul class="sidenav-second-level collapse" id="collapseComponents">
<?php

$selectors=$mysql->getAllSelectors($_SESSION["instID"]);
foreach ($selectors as $s){
  ?>
  <li >
    <a href="index.php?state=selector&id=<?= $s["id"];?>" style='padding-bottom:0px;'><?= $s["name"];?></a>
  </li>

  <?php
}

 ?>

        </ul>
      </li>
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Manage Users">
        <a class="nav-link" href="index.php?state=manageUsers">
          <i class="fa fa-user-cog"></i>
          <span class="nav-link-text">Manage Users</span>
        </a>
      </li>
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
        <a class="nav-link" href="index.php?state=subjects">
          <i class="fa fa-fw fa-globe"></i>
          <span class="nav-link-text">Manage Subjects</span>
        </a>
      </li>



      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
        <a class="nav-link" href="index.php?state=tools">
          <i class="fa fa-fw fa-wrench"></i>
          <span class="nav-link-text">Process Alma Reports</span>
        </a>
      </li>
    <?php } ?>

    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Docs">
      <a class="nav-link" href="index.php?state=docs">
        <i class="fa fa-fw fa-info"></i>
        <span class="nav-link-text">Help / Docs</span>
      </a>
    </li>
      <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">
        <a class="nav-link" href="index.php?state=about">
          <i class="fa fa-fw fa-smile"></i>
          <span class="nav-link-text">About</span>
        </a>
      </li>

    </ul>



  </ul>



    <ul class="navbar-nav sidenav-toggler">
      <li class="nav-item">
        <a class="nav-link text-center" id="sidenavToggler">
          <i class="fa fa-fw fa-angle-left"></i>
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">


    </ul>
  </div>
</nav>
<?php
