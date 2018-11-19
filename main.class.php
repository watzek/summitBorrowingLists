<?php

include ("templates.class.php");
include ("mysql.class.php");
include ("process.class.php");


class main{

  function __construct($state){
    $mysql=new mysqlFunctions();
    $templates=new templates($mysql);
    $this->templates=$templates;
    $this->mysql=$mysql;

    /* in cases where pre-processing is required  */
    switch($state){
      case "adduser":
      $templates->addNewUser();
      break;

      case "processUpload":
      $templates->processUpload();
      //exit();
      break;

      default:


    }



  }


  function switchboard($state){
    $templates=$this->templates;

    switch($state){

      case "home":
      $templates->home();
      break;

      case "hunt":
      $templates->hunt($_GET["status"]);
      break;

      case "selector":
      $templates->selector($_GET["id"]);
      break;

      case "letter":
      $templates->letter($_GET["letter"]);
      break;
      case "subjects":
      $templates->subjects();
      break;

      case "subject":
      $templates->subject($_GET["subject_id"]);
      break;

      case "tools":
      $templates->tools();
      break;

      case "about":
      $templates->about();
      break;


      case "manageUsers":
      $templates->manageUsers();
      break;



    }





  }


    function getLoginStatus(){
      $loginStatus="";
      $auth_url="";


      require_once 'vendor/autoload.php';
      $client = new Google_Client();
      $client->setRedirectUri('https://' . $_SERVER['HTTP_HOST']);
      $client->setAuthConfig('clientcreds.json');
      $client->setIncludeGrantedScopes(true);   // incremental auth
      $client->addScope('profile');
      $client->addScope('email');

      $auth_url = $client->createAuthUrl();

      if (isset($_GET["logout"])){
        unset($_SESSION['access_token']);
        unset($_SESSION['email']);
        unset($_SESSION["name"]);
        unset($_SESSION["validUser"]);
        unset($_SESSION['instID']);
        unset($_SESSION["instName"]);


        $client->revokeToken();

      }



      if (isset($_SESSION["access_token"])){

        /* everything normal?   */

        $loginStatus="Welcome, ".$_SESSION["name"].". <a href='index.php?logout'>Logout</a>";

          //$client = new Google_Client();
          //$client->setAuthConfig('clientcreds.json');
          //$client->setAccessType("offline");        // offline access
      }
      else{

        /* coming back from Google auth page     */
        if (isset($_GET["code"])){
          echo "code!";
          $client->authenticate($_GET['code']);

          //$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
          $_SESSION['access_token'] = $client->getAccessToken();
          //$_SESSION['access_token'] = $client->fetchAccessTokenWithAuthCode($_GET['code']);
          //var_dump($token);

          $oauth2 = new Google_Service_Oauth2($client);
          $userInfo = $oauth2->userinfo->get();
          //var_dump($userInfo);
          $_SESSION["email"]=$userInfo["email"];
          $_SESSION["name"]=$userInfo["name"];
          $rows=$this->mysql->getInstIdByEmail($userInfo["email"]);
          //var_dump($rows);
          if (empty($rows)){$_SESSION["validUser"]=false;}
          else{
            $_SESSION["instID"]=$rows[0]["id"];
            $_SESSION["instName"]=$rows[0]["name"];
            $_SESSION["validUser"]=true;
          }
          //var_dump($_SESSION);




          $redirect_uri='https://' . $_SERVER['HTTP_HOST'];
          header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));

        }

        /* no code, default un-authenticated state   */
        else{

          $loginStatus="<a href='$auth_url'>login</a>";



        }



      }



      return $loginStatus;

    }






}



?>
