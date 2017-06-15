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
  $current_password = $user_record["hashed_password"];


if(isset($_POST['submit'])){
//process_form
$user_id = (int) $_POST["user_id"];
$first_name = mysql_prep($_POST["first_name"]);
$last_name = mysql_prep($_POST["last_name"]);
$username = mysql_prep($_POST["username"]);


if(!empty($_POST["new_password"])){
  $old_password = mysql_prep($_POST["password"]);
  $new_password = mysql_prep($_POST["new_password"]);
  $confirm_password = mysql_prep($_POST["confirm_password"]);
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

} else {
  $new_password = null;
}
  //validations
  if($new_password){
  fields_are_equal("new_password", "confirm_password");
  if(!password_verify($old_password, $current_password)){

    $errors["verify_password"] = "could not verify password";
  }
  }

  $required_fields = array("username", "first_name", "last_name");
	validate_presences($required_fields);

  $fields_with_max_lengths = array("password" => "30", "username" => "60");
  validate_max_lengths($fields_with_max_lengths);

  $fields_with_min_lengths = array("password" => "6", "new_password" => "6", "confirm_password" => "6", "username" => 6);
  validate_min_lengths($fields_with_min_lengths);

  $fields_with_no_whitespace = array("username", "password", "new_password", "confirm_password");
  field_has_no_whitespace($fields_with_no_whitespace);

  //if errors is not empty redirect to new_subject page
  if(empty($errors)){

      $query = "UPDATE user set ";
      if($new_password){
        $query .= "hashed_password = '{$hashed_password}', ";
      }
      $query .= "first_name = '{$first_name}', ";
      $query .= "last_name = '{$last_name}', ";
      $query .= "username = '{$username}' ";
      $query .= "WHERE user_id = {$user_id} ";
      $query .= "LIMIT 1";
      $result = mysqli_query($connection, $query);
      confirm_query_query($connection, $query);


      if($result && mysqli_affected_rows($connection)) {

        $_SESSION["message"] .= "Profile updated";
        $_SESSION["username"] = $username;
        redirect_to("client.php");


      } else {
        $message = "Profile update failed";

      }

  } //end of update query

} //end of $_POST conditional

} //end of $_GET conditional

else {

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

      <form id='edit-user' class = "form" action="update_client_profile.php?user_id=<?php echo urlencode($current_user_id) ?>" method="post">
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
        <h3>Update Password</h3>
        <div class="password-group">
          <div class = "form-group">
            <label class = "form-label" for="password">Password</label>
            <input class = "form-control" id = "password" type="password" name="password" value="" />
          </div>
          <div class = "form-group">
            <label class = "form-label" for="new_password">New Password</label>
            <input class = "form-control" id = "new_password" type="password" name="new_password" value="" />
          </div>
          <div class = "form-group">
            <label class = "form-label" for="confirm_password">Confirm Password <span id="message"></span></label>
            <input class = "form-control" id = "confirm_password" type="password" name="confirm_password" value="" />
          </div>
        </div>


        <input class = "btn" type="submit" name="submit" value="Update Profile" />
        <p class="form-instruction" style="margin-top: 2rem;"><a href="client.php" style="width: auto;">Cancel</a></p>
      </form>
    </div>
  </div>
      <pre>

      </pre>
</main>
<script>
$('#new_password, #confirm_password').on('keyup', function () {
  if ($('#new_password').val() == $('#confirm_password').val()) {
    $('#message').html(' Matching').css('color', 'green');
  } else
    $('#message').html(' Not Matching').css('color', 'red');
});

</script>


<?php include("../layouts/footer.php") ?>
