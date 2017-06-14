<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation.php"); ?>
<?php confirm_login() ?>

<?php

if(!empty($_GET["user_id"])){
  $current_user_id = $_GET["user_id"];
  $user_record = find_user_by_id($current_user_id);
  $current_username = $user_record["username"];
  $current_first_name = $user_record["first_name"];
  $current_last_name = $user_record["last_name"];
  $current_role_id = $user_record["role_id"];
  $current_urls = find_url_array_by_user($current_user_id);
}



if(isset($_POST['submit'])){
//process_form
$user_id = (int) $_POST["user_id"];
$first_name = mysql_prep($_POST["first_name"]);
$last_name = mysql_prep($_POST["last_name"]);
$username = mysql_prep($_POST["username"]);
$role_id = (int) $_POST["role_id"];
if($_POST["role_id"]==2){
  $urls = $_POST["urls"];
} elseif($_POST["role_id"]==1){
$urls = website_id_array();
}
if(!empty($_POST["password"])){
  $password = mysql_prep($_POST['password']);
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
} else {
  $password = null;
}




  //validations
  $required_fields = array("username", "first_name", "last_name", "role_id");
	validate_presences($required_fields);

  $fields_with_max_lengths = array("password" => "30", "username" => "60");
  validate_max_lengths($fields_with_max_lengths);

  $fields_with_min_lengths = array("password" => "6", "username" => 6);
  validate_min_lengths($fields_with_min_lengths);

  $fields_with_no_whitespace = array("username", "password");
  field_has_no_whitespace($fields_with_no_whitespace);



  //if errors is not empty redirect to new_subject page
  if(empty($errors)){

      $query = "UPDATE user set ";
      $query .= "first_name = '{$first_name}', ";
      $query .= "last_name = '{$last_name}', ";
      $query .= "username = '{$username}', ";
      if($password){
        $query .= "hashed_password = '{$hashed_password}', ";
      }
      $query .= "role_id = {$role_id} ";
      $query .= "WHERE user_id = {$user_id} ";
      $query .= "LIMIT 1";
      $result = mysqli_query($connection, $query);
      confirm_query_query($connection, $query);


      if($result && mysqli_affected_rows($connection)) {

        $_SESSION["message"] .= "User updated";


      } else {
        $message = "User update failed";

      }

      //if admin access to all urls automatically. if client choose from list

              //delete all prior associations
        $result1 = delete_by_foreign_key($user_id, "user_id", "user_url");
        if($result1 && mysqli_affected_rows($connection)>= 1)
        {
          $_SESSION["message"] .=", user url's deleted";

        } else {
          $message .=", user url's COULD NOT be deleted";
        }
        $result2 = create_user_url_assoc($new_user_id, $urls, "url");
          if($result2&& mysqli_affected_rows($connection))
          {
            $_SESSION["message"] .=", user url's associated";
            $_SESSION["urls"] = $urls;
            //on success redirect to client.php
            redirect_to("client.php");
          } else {
            $message .=", user url's COULD NOT be associated";
            $_SESSION["urls"] = $urls;
          }




  } //end of insert query

} else {
  //probably a GET request

}

 ?>





<!-- ====================page content ===================================-->
<?php include("../layouts/header.php") ?>
<body>
  <section class="background-pic"></section>
  <main class='main yel-white-background' id='create-user'>
    <section class="section create-user-section">
      <div class="title" >
        <a class='a-with-img' href='../../index.html'><img class='logo' src='../../images/logos/DRD_circle.svg'></a>
        <h2>Edit Client Record for: <?php echo "{$current_first_name} {$current_last_name}" ?></h2>
      </div>
      <div id="create-user-div" class='form-div white-background'>
        <!-- errors and message divs -->
        <?php If(!empty($message)||!empty($errors)) { ?>
        <div class='error-div'>
          <?php   if (!empty($message)) {
            echo "<div class = 'message'>" . htmlentities($message) . "</div>";
          }; $message = null;?>
          <?php echo form_errors($errors); ?>
        </div>
        <?php } ?>


		<div class="form-div">
      <p class = 'form-instruction'>Edit Form</p>

      <form id='edit-user' class = "form" action="edit_client.php?user_id=<?php echo urlencode($current_user_id) ?>" method="post">
        <div class = "form-group hidden">
          <label class = "form-label" for="user_id">User_id</label>
          <input class = "form-control" id='user_id' type="text" name="user_id" value="<?php echo $current_user_id ?>" readonly />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="first_name">First Name</label>
          <input class = "form-control" id='first_name' type="text" name="first_name" value="<?php echo htmlentities(ucwords($current_first_name)) ?>" />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="last_name">Last Name</label>
          <input class = "form-control" id='last_name' type="text" name="last_name" value="<?php echo htmlentities(ucwords($current_last_name)) ?>" />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="username">Email</label>
          <input class = "form-control" id='username' type="email" name="username" value="<?php echo htmlentities($current_username) ?>" />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="password">Password</label>
          <input class = "form-control" id = "password" type="password" name="password" value="" />
        </div>
        <div class = "flex-group">
          <div class="form-group" id="user-role-group">
            <label id="user-role-label" class="form-label" for="user-role">Select privileges:</label>
            <select size="2" class="form-options" id='user-role' name="role_id" >
              <option <?php if($current_role_id==2){echo "selected";} ?> value="2" >Client</option>
              <option  <?php if($current_role_id==1){echo "selected";} ?> value="1" >Admin</option>
            </select>
          </div>

          <div class="form-group" id="url-group">
            <label id="url-label" class="form-label" for="url">Select website access:</label>
            <select size="6" class="form-options" id='url' name="urls[]" multiple>
              <?php $url_set = find_all_websites();
              if(!empty($url_set)){
                $all_websites = website_id_array();
                      $output = "";
                    while($url = mysqli_fetch_assoc($url_set)) {
                      $output .= "<option value='";
                      $output .= $url["url_id"];
                      $output .= "' ";
                      if(has_inclusion_in($url["url_id"], $current_urls)){
                        $output .= "selected ";
                      }
                      $output .= ">";
                      $output .= htmlentities($url["url_name"]);
                      $output .= "</option>";
                    }
                      echo $output;

                  }
              ?>
              </select>
          </div>
        </div>
        <input class = "btn" type="submit" name="submit" value="Update User" />
        <p class="form-instruction" style="margin-top: 2rem;"><a href="client.php" style="width: auto;">Cancel</a></p>
      </form>
    </div>
  </div>
      <pre>

      </pre>
</main>
<script>
if($('#user-role').val() == 2) {
  $('#url-group').removeClass('hidden')} else if($('#user-role').val() == 1){
    $('#url-group').addClass('hidden');
  }
  $('#user-role').change(function(){if($('#user-role').val() == 2) {
    $('#url-group').removeClass('hidden')} else if($('#user-role').val() == 1){
      $('#url-group').addClass('hidden');

  }
});



</script>


<?php include("../layouts/footer.php") ?>
