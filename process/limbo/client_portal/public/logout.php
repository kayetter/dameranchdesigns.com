<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
$_SESSION["user_id"] =  null;
$_SESSION["username"]= null;
session_destroy();
if(isset($_COOKIE[session_name()])){ setcookie(session_name(),"",time()-4200,"/client_portal");}
if(isset($_COOKIE["user_id"])){ setcookie("user_id","",time()-4200,"/client_portal/");}
redirect_to("../../index.html");

 ?>

 <?php
// super destroy
// session_start();
// $_SESSION = array();
//
//   session_destroy();
//   redirect_to("login.php");

  ?>
  <?php
   if (isset($connection)) {
     mysqli_close($connection);
   }
  
  ?>
