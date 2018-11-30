#!/usr/bin/php -q
<?php
echo "hello";
var_dump($argv);
$instID=$argv[1];
include ("mysql.class.php");
//include ("classify.class.php");
include ("process.class.php");

$mysql=new mysqlFunctions();
echo $instID;

$x=new cnSearch($mysql);
$x->newFileSearch($instID);






?>
