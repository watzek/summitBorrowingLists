<?php

  class classify{

    function __construct($mysql){
      $this->mysql=$mysql;

    }


    function searchByOclc($oclc, $status){
      $cn=false;

      $url="http://classify.oclc.org/classify2/Classify?oclc=$oclc&summary=true";
      $response=simplexml_load_file($url);

      $code=$response->response->attributes()->code;
      //echo "<p>Code: $oclc | $code</p>";

/*
from: http://classify.oclc.org/classify2/api_docs/classify.html#examples
0:	Success. Single-work summary response provided.
2:	Success. Single-work detail response provided.
4:	Success. Multi-work response provided.
100:	No input. The method requires an input argument.
101:	Invalid input. The standard number argument is invalid.
102:	Not found. No data found for the input argument.
200:	Unexpected error.
*/

      switch($code){
        case "0":
          if ($response->recommendations->lcc){
            $cn=$response->recommendations->lcc->mostPopular->attributes()->sfa;
          }
          else{
            if($response->recommendations->ddc){
              $cn="DEWEY".$response->recommendations->ddc->mostPopular->attributes()->sfa;
            }

          }
          //else{$cn=false;}



        break;

        case "4":
        echo "MULTIWORK!!!!!!";
        echo $oclc;
        exit();
        #multi-work

        break;

        case "102":
        echo "error";
        $cn="102oclc";

        break;

      }

      if ($cn){return $cn;}
      //else{return false;}

    }


    function searchByIsbn($isbn){
      $cn=false;

      $url="http://classify.oclc.org/classify2/Classify?isbn=$isbn&summary=true";
      $response=simplexml_load_file($url);

      $code=$response->response->attributes()->code;
    //  echo "<p>Code: $isbn | $code</p>";

/*
from: http://classify.oclc.org/classify2/api_docs/classify.html#examples
0:	Success. Single-work summary response provided.
2:	Success. Single-work detail response provided.
4:	Success. Multi-work response provided.
100:	No input. The method requires an input argument.
101:	Invalid input. The standard number argument is invalid.
102:	Not found. No data found for the input argument.
200:	Unexpected error.
*/

      switch($code){
        case "0":
        if ($response->recommendations->lcc){
          $cn=$response->recommendations->lcc->mostPopular->attributes()->sfa;
        }
        else{
          if($response->recommendations->ddc){
            $cn="DEWEY".$response->recommendations->ddc->mostPopular->attributes()->sfa;
          }


        }



        break;

        case "4":
        $owi=$response->works->work[0]->attributes()->owi;
        echo $owi;
        $cn=$this->searchByOwi($owi);
        echo "MULTIWORK!!sdfksdhf";

        # http://classify.oclc.org/classify2/Classify?isbn=9780140444254&summary=true
        echo $isbn;
        //exit();

        break;

        case "102":
      //  echo "error";
        $cn="102isbn";

        break;

      }

      if ($cn){return $cn;}





    }


    function searchByTitleAndAuthor($title, $author=NULL, $id, $mysql){
      echo "searchByTitleAndAuthor";
      $cn=false;

      $url="http://classify.oclc.org/classify2/Classify?title=".urlencode($title);
      if (!is_null($author)){$url.="&author=".urlencode($author);}
      $url.="&summary=true";

      $response=simplexml_load_file($url);
      if(is_object($response)){

      echo $url;

      $code=$response->response->attributes()->code;
      echo "<p>Code:  $code</p>";

    /*
    from: http://classify.oclc.org/classify2/api_docs/classify.html#examples
    0:	Success. Single-work summary response provided.
    2:	Success. Single-work detail response provided.
    4:	Success. Multi-work response provided.
    100:	No input. The method requires an input argument.
    101:	Invalid input. The standard number argument is invalid.
    102:	Not found. No data found for the input argument.
    200:	Unexpected error.
    */
    //if ($code !=0){exit();}
      switch($code){
        case "0":
        if ($response->recommendations->lcc){
          $cn=$response->recommendations->lcc->mostPopular->attributes()->sfa;
        }



          elseif($response->recommendations->ddc){
            //echo "here i am";
            $cn="DEWEY".$response->recommendations->ddc->mostPopular->attributes()->sfa;
          }


        else{$mysql->updateStatus($id, "unable to resolve");}



        break;

        case "4":
        #multi-work
        $owi=$response->works->work[0]->attributes()->owi;
        echo "OWI: $owi";
        $cn=$this->searchByOwi($owi);
        //exit();



        break;

        case "102":
        case "100":
      //  echo "error";
        $mysql->updateStatus($id, "unable to resolve");

        break;

      }

      if ($cn){return $cn;}
      else{echo "CN FAIL";}


      }
      else{$mysql->updateStatus($id, "XMLerror");}


    }

    function searchByOwi($owi){

      //http://classify.oclc.org/classify2/Classify?owi=17655&summary=true
      $cn=false;

      $url="http://classify.oclc.org/classify2/Classify?owi=$owi&summary=true";
      echo $url;
      $response=simplexml_load_file($url);

      $code=$response->response->attributes()->code;
      echo "<p>Code: $owi | $code</p>";

      switch($code){

        #add dewey
        case "0":
        if ($response->recommendations->lcc){
          $cn=$response->recommendations->lcc->mostPopular->attributes()->sfa;
          echo $cn;
        }
        else{
          if($response->recommendations->ddc){
            echo "here i am";
            $cn="DEWEY".$response->recommendations->ddc->mostPopular->attributes()->sfa;
          }
        }



        break;



        case "102":
        echo "error";

        break;

      }

      if ($cn){return $cn;}




    }



  }







 ?>
