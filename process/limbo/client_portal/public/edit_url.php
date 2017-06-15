
<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation.php"); ?>
<?php confirm_login() ?>

<?php
$current_urlname = "";
$current_url = "";

if(!empty($_GET["url_id"])){
  $current_url_id = $_GET["url_id"];
  $url_record = find_website_by_id($current_url_id);
  $current_url = $url_record["url"];
  $current_urlname = $url_record["url_name"];
}

if(isset($_POST['submit'])){
//process_form
$url_id = (int) $_POST["url_id"];
$url_name = mysql_prep($_POST["url_name"]);
$url = mysql_prep($_POST["url"]);

  //validations
  $required_fields = array("url", "url_name");
	validate_presences($required_fields);

  $fields_with_max_lengths = array("url_name" => "30");
  validate_max_lengths($fields_with_max_lengths);

  $fields_with_no_whitespace = array("url", "url_name");
  field_has_no_whitespace($fields_with_no_whitespace);


  //if errors is not empty redirect to new_subject page
  if(empty($errors)){

      $query = "UPDATE url set ";
      $query .= "url = '{$url}', ";
      $query .= "url_name = '{$url_name}' ";
      $query .= "WHERE url_id={$url_id} ";
      $query .= "LIMIT 1";
      $result = mysqli_query($connection, $query);
      confirm_query_query($connection, $query);


      if($result && mysqli_affected_rows($connection)) {

        $_SESSION["message"] = "URL updated";
        redirect_to("client.php#website-admin");
      } else {
        $message = "URL update failed";
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
  <main class='main yel-white-background' id='create-user'>
    <section class="section create-user-section">
      <div class="title" >
        <a class='a-with-img' href='../../index.html'><img class='logo' src='../../images/logos/DRD_circle.svg'></a>
        <h2>Edit URL Record for: <?php echo htmlentities($current_urlname) ?></h2>
      </div>
      <div id="create-user-div" class='form-div white-background'>
        <!-- errors and message divs -->
        <?php If(!empty($message)||!empty($errors)) { ?>
        <div class='error-div'>
          <?php   if (!empty($message)) {
            echo "<div class = 'message'>" . htmlentities($message) . "</div>";
          }; $message = "";?>
          <?php echo form_errors($errors); ?>
        </div>
        <?php } ?>


		<div class="form-div">
      <p class = 'form-instruction'>Edit Form</p>

      <form id='edit-user' class = "form" action="edit_url.php?url_id=<?php echo urlencode($current_url_id) ?>" method="post">
        <div class = "form-group hidden">
          <label class = "form-label" for="url_id">url_id</label>
          <input class = "form-control" id='url_id' type="text" name="url_id" value="<?php echo $current_url_id ?>" readonly />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="url_name">URL Name</label>
          <input class = "form-control" id='url_name' type="text" name="url_name" value="<?php echo htmlentities($current_urlname) ?>" />
        </div>
        <div class = "form-group">
          <label class = "form-label" for="url">URL Path</label>
          <input class = "form-control" id='url' type="text" name="url" value="<?php echo htmlentities($current_url) ?>" />
        </div>

        <input class = "btn" type="submit" name="submit" value="Update URL" />
        <p class="form-instruction" style="margin-top: 2rem;"><a href="client.php" style="width: auto;">Cancel</a></p>
      </form>
    </div>
    <div class="client-output white-background" id="user-url-list">
      <h3>Users associated with this website</h3>

        <?php
        $user_set = find_users_by_url($current_url_id);
        if(!empty($user_set)){
          $output = "<ul>";
          while ($user = mysqli_fetch_assoc($user_set)){
            $role = ($user["role_id"] == 1 ? "Admin":"Client");
            $output .= "<li>";
            $output .= $user["username"];
            $output .= " (";
            $output .= $role;
            $output .= ")</li>";
          }
          $output .= "</ul>";
          echo $output;

          }


         ?>
      </ul>

    </div>
  </div>
</section>

</main>

<?php include("../layouts/footer.php") ?>
