<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation.php"); ?>
<?php confirm_admin_login() ?>

<?php
$username = "";
$first_name = "";
$last_name = "";
$role_id = "";
$urls = array();


if(isset($_POST['submit'])){
//process_form
$first_name = mysql_prep($_POST["first_name"]);
$last_name = mysql_prep($_POST["last_name"]);
$username = mysql_prep($_POST["username"]);
$role_id = (int) $_POST["role_id"];
$password = mysql_prep($_POST['password']);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
//if admin access to all urls automatically. if client choose from list
if($_POST["role_id"]==2){
  $urls = $_POST["urls"];
} elseif($_POST["role_id"]==1){
$urls = website_id_array();
}


  //validations
  $required_fields = array("username", "password", "first_name", "last_name", "role_id");
	validate_presences($required_fields);

  $fields_with_max_lengths = array("password" => "30", "username" => "60");
  validate_max_lengths($fields_with_max_lengths);

  $fields_with_min_lengths = array("password" => "6", "username" => 6);
  validate_min_lengths($fields_with_min_lengths);

  $fields_with_no_whitespace = array("username", "password");
  field_has_no_whitespace($fields_with_no_whitespace);

  user_not_in_set();


  //if errors is not empty redirect to new_subject page
  if(empty($errors)){

      $query = "INSERT into user (";
      $query .= "first_name, last_name, username, hashed_password, role_id ";
      $query .= ") values (";
      $query .= "'{$first_name}', '{$last_name}', '{$username}', '{$hashed_password}', {$role_id} ";
      $query .= ") ";

      $result = mysqli_query($connection, $query);
      $new_user_id = mysqli_insert_id($connection);

      if($result) {

        $_SESSION["message"] = "User Created";
        $_SESSION["urls"] = $urls;

      } else {
        $message = "User creation failed";

      }

        $assoc_result = create_user_url_assoc($new_user_id, $urls, "url");
        if($assoc_result && mysqli_affected_rows($connection) > 0){
          $_SESSION["message"] .= ", and user-url association created";
          $_session["urls"] = $urls;
          redirect_to("client.php");

        } else {
          $message .= "could not create user-url assoc";
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
    <section class="section client-section">
      <div class="logout">
        <a href="logout.php">+Logout</a>
      </div>
      <div class="title" >
        <a class='a-with-img' href='../../index.html'><img class='logo' src='../../images/logos/DRD_circle.svg'></a>
        <h2>Add New User</h2>
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


		<div class="form-div client-input" id="create-user-form">
      <form class='form' action="create_user.php" method="post">
      <p class = 'form-instruction'>Fill Form</p>

        <div class = "form-group">
          <label class = "form-label" for="first_name">First Name</label>
          <input class = "form-control" id='first_name' type="text" name="first_name" value="<?php echo htmlentities(ucwords($first_name)) ?>" />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="last_name">Last Name</label>
          <input class = "form-control" id='last_name' type="text" name="last_name" value="<?php echo htmlentities(ucwords($last_name)) ?>" />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="username">Email</label>
          <input class = "form-control" id='username' type="email" name="username" value="<?php echo htmlentities($username) ?>" />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="password">Password</label>
          <input class = "form-control" id = "password" type="password" name="password" value="" />
        </div>
        <div class = "flex-group">
          <div class="form-group" id="user-role-group">
            <label id="user-role-label" class="form-label" for="user-role">Select privileges:</label>
            <select size="2" class="form-options" id='user-role' name="role_id" >
              <option <?php if($role_id==2){echo "selected";} ?> value="2" >Client</option>
              <option  <?php if($role_id==1){echo "selected";} ?> value="1" >Admin</option>
            </select>
          </div>

          <div class="form-group hidden" id="url-group">
            <label id="url-label" class="form-label" for="url">Select website access:</label>
            <select size="6" class="form-options" id='url' name="urls[]" multiple>
              <?php $url_set = find_all_websites("1");
              if(!empty($url_set)){
                      $output = "";
                    while($url = mysqli_fetch_assoc($url_set)) {
                      $output .= "<option value='";
                      $output .= $url["url_id"];
                      $output .= "'>";
                      $output .= htmlentities($url["url_name"]);
                      $output .= "</option>";
                      }
                      echo $output;

                  }
              ?>
              </select>
          </div>
        </div>
        <input class = "btn" type="submit" name="submit" value="Add User" />
        <p class="form-instruction" style="margin-top: 2rem;"><a href="client.php" style="width: auto;">Cancel</a></p>
      </form>
    </div>
  </div>

</main>
<script>
  $('#user-role').change(function(){if($('#user-role').val() == 2) {
    $('#url-group').removeClass('hidden')} else if($('#user-role').val() == 1){
      $('#url-group').addClass('hidden');

  }
});



</script>


<?php include("../layouts/footer.php") ?>
