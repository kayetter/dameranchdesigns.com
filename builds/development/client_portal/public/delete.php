<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php confirm_admin_login() ?>

<!-- //===============deleting and activating users ================= -->
<?php

  if(isset($_GET)){
    $user_id = ($_GET["user_id"]? $_GET["user_id"] : "");
    $url_id = ($_GET["url_id"]? $_GET["url_id"] : "");
    $action = $_GET["action"];
    $url = $_GET["url_id"];
    $current_user_id = $_SESSION["user_id"];
  } else {
    redirect_to("client.php");
  }



// if action = delete then delete user, if action = toggle_active than toggle activation
  if($action == "delete_user"){
    if($current_user_id!=$user_id){
      $result = delete_user($user_id);
    } else { $_SESSION["message"] = "you are logged in as the user you are trying to delete"; redirect_to("client.php");}
  } elseif($action == "delete_url"){
    $result = delete_url($url_id);
  } elseif($action == "toggle_active" && $user_id) {
    $result = toggle_active($user_id, "user");
  } elseif($action == "toggle_active" && $url_id){
    $result = toggle_active($url_id, "url");
  }

  ?>






<?php
 if (isset($connection)) {
   mysqli_close($connection);
 }

?>
