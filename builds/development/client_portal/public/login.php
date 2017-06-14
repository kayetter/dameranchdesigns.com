<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation.php"); ?>



<?php

  $username = "";
  $password = "";
  $remember_me = "";
  $attempt_login = false;

//if cookie is set, user is assumed to be logged in and authentication is not performed
if(isset($_COOKIE["user_id"])){
  $user_id = $_COOKIE["user_id"];
  $user_record = find_user_by_id($_COOKIE["user_id"]);
  $username = $user_record["username"];
  $password = "rememberme";
  $remember_me = true;
  $attempt_login = true;
}

if(isset($_POST["submit"])) {
  if($attempt_login){
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $username;

    if($remember_me==true){
      setcookie("user_id", $attempt_login["user_id"], time()+3600,"/client_portal/");
    } elseif (!$remember_me){
      setcookie("user_id", null, -1,"/client_portal/");
      }
    redirect_to("client.php");
  } else {
  $username = $_POST["username"];
  $password = $_POST["password"];
    if(isset($_POST["remember_me"])){
       $remember_me = true;
    } else {$remember_me = false;}

    //validations
    $required_fields = array("username", "password");
  	validate_presences($required_fields);

    //if errors is not empty redirect to new_subject page
    if(empty($errors)){
        $attempt_login = authenticate_user($username, $password);
        $user_id = $attempt_login["user_id"];
        $username = $attempt_login["username"];
      }
    }

    if($attempt_login) {
      $_SESSION["user_id"] = $user_id;
      $_SESSION["username"] = $username;
      if($remember_me){
        setcookie("user_id", $user_id, time()+3600,"/client_portal/");
      } elseif (!$remember_me){
        setcookie("user_id", null, -1,"/client_portal/");
        }
      redirect_to("client.php");
    } else {
      $password = "";
      $username = $_POST["username"];
      $message = "Username/password not found";
    }
  }else {

    //get request
  }



 ?>
  <!-- ====================page content ===================================-->
  <?php include("../layouts/header.php") ?>

  <body>
    <section class="background-pic"></section>
    <main class='main yel-white-background' id='login'>
      <section class="section client-section">
        <div class="title" >
          <a class='a-with-img' href='index.html'><img class='logo' src='../../images/logos/DRD_circle.svg'></a>
          <h2>Client Login</h2>
        </div>
        <div id="login-div" class='form-div white-background'>
          <!-- errors and message divs -->
          <?php If(!empty($message)||!empty($errors)) { ?>
          <div class='error-div'>
            <?php   if (!empty($message)) {
              echo "<div class = 'message'>" . htmlentities($message) . "</div>";
            }; $message = null;?>
            <?php echo form_errors($errors); ?>
          </div>
          <?php } ?>
          <p class = 'form-instruction'>Sign in to view your website</p>

          <form id='login-form' class = "form" action="login.php" method="post">
            <div class = "form-group">
              <label class = "form-label" for="username">Email</label>
              <input class = "form-control" id='username' type="email" name="username" value="<?php  echo $username; ?>" />
            </div>
            <div class = "form-group">
              <label class = "form-label" for="password">Password</label>
              <input class = "form-control" id = "password" type="password" name="password" value="<?php echo $password; ?>" />
            </div>
            <div class="form-group" id="remember-me">
              <input class="form-control" id="remember-me-checkbox" type="checkbox" name="remember_me" value="true" <?php if($remember_me){echo "checked";} ?>>
              <label id="remember-me-checkbox-label" class="form-label checkbox" for="remember-me-checkbox" >Remember me</label>
            </div>
            <input class = "btn" type="submit" name="submit" value="Sign In" />
          </form>
          <p class = "form-instruction"><a href="mailto:admin@dameranchdesigns.com" style="padding-top: 1rem; display: block;">Forget password?</a></p>
          <p class = "form-instruction"><a href="logout.php" >Logout</a></p>

        </div>
      </section>
    </main>

    <?php include("../layouts/footer.php") ?>
