<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation.php"); ?>
<?php confirm_admin_login() ?>

<?php
$urlname = "";
$url = "";
$users = array();


if(isset($_POST['submit'])){
//process_form

$url_name = mysql_prep($_POST["url_name"]);
$url = mysql_prep($_POST["url"]);
$client_users = ($_POST["users"]);
$admin_users = user_array_by_role(0,1);




  //validations
  $required_fields = array("url", "url_name");
	validate_presences($required_fields);

  $fields_with_max_lengths = array("url_name" => "30");
  validate_max_lengths($fields_with_max_lengths);

  $fields_with_no_whitespace = array("url", "url_name");
  field_has_no_whitespace($fields_with_no_whitespace);


  //if errors is not empty redirect to new_subject page
  if(empty($errors)){

    $query = "INSERT into url (";
    $query .= "url_name, url";
    $query .= ") values (";
    $query .= "'{$url_name}', '{$url}' ";
    $query .= ") ";

      $result = mysqli_query($connection, $query);
      confirm_query_query($connection, $query);
      $new_url_id = mysqli_insert_id($connection);


      if($result && mysqli_affected_rows($connection)) {

        $_SESSION["message"] = "URL updated";

      } else {
        $message = "URL update failed";
      }
      //create association with client users
      $assoc_result1 = create_user_url_assoc($new_url_id, $client_users, "user");
      $assoc_result2 = create_user_url_assoc($new_url_id, $admin_users, "user");
      if($assoc_result1 && $assoc_result2 && mysqli_affected_rows($connection) > 0){
        $_SESSION["message"] .= ", and user-url association created";
        $_session["urls"] = $urls;
        redirect_to("client.php");

      } else {
        $message .= "could not create user-url assoc";
      }

    }
  } else {
  //probably a GET request

}

 ?>





  <!-- ====================page content ===================================-->
  <?php include("../layouts/header.php") ?>

  <body>
    <section class="background-pic"></section>
    <main class='main yel-white-background' id='create-url'>
      <section class="section ">
        <div class="title">
          <a class='a-with-img' href='../../index.html'><img class='logo' src='../../images/logos/DRD_circle.svg'></a>
          <h2>Create New URL</h2>
        </div>
        <div id="create-url-div" class='form-div white-background'>
          <!-- errors and message divs -->
          <?php If(!empty($message)||!empty($errors)) { ?>
          <div class='error-div'>
            <?php   if (!empty($message)) {
            echo "<div class = 'message'>" . htmlentities($message) . "</div>";
          }; $message = "";?>
            <?php echo form_errors($errors); ?>
          </div>
          <?php } ?>



          <div class="form-div client-input" id="create-url-form">
            <p class='form-instruction'>Fill Form</p>

            <form id='create-url' class="form" action="create_url.php ?>" method="post">

              <div class="form-group">
                <label class="form-label" for="url_name">URL Name</label>
                <input class="form-control" id='url_name' type="text" name="url_name" value="<?php echo htmlentities($urlname) ?>" />
              </div>
              <div class="form-group">
                <label class="form-label" for="url">URL Path</label>
                <input class="form-control" id='url' type="text" name="url" value="<?php echo htmlentities($url) ?>" />
              </div>

            <div class="flex-group">
              <div class="form-group" id="url-group">
                <label id="url-label" class="form-label" for="url">Select client access:</label>
                <select size="6" class="form-options" id='users' name="users[]" multiple>
                    <?php $user_set = find_users_by_role(1,2);
                    if(!empty($user_set)){
                            $output = "";
                          while($user = mysqli_fetch_assoc($user_set)) {
                            $output .= "<option value='";
                            $output .= $user["user_id"];
                            $output .= "'>";
                            $output .= htmlentities($user["username"]);
                            $output .= "</option>";
                            }
                            echo $output;

                        } else { echo "<option>you have no clients</option>";}
                    ?>
                    </select>
              </div>
              <div class="form-instruction">
                <p>Admins will automatically be granted full access</p>
              </div>
            </div>

              <input class="btn" type="submit" name="submit" value="Create URL" />
              <p class="form-instruction" style="margin-top: 2rem;"><a href="client.php" style="width: auto;">Cancel</a></p>
            </form>
          </div>
        </div>
      </section>


      <pre>
        <?php
        print_r($_SESSION);
        print_r($user_set);
         ?>
      </pre>
    </main>

    <?php include("../layouts/footer.php") ?>
