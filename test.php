<?php


$bgsub="QA";
$bgln="1";

$endsub="QA";
$endln="939";

#$cn="QA76.9.B45";
#$cn="QA100";
$cn="QA100.B45";
#$cn="QA76.9";

$check=array($bgsub, $endsub);


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






 ?>
