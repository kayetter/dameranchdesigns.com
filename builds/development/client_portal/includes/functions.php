<?php

function redirect_to($new_location) {
  header("Location: {$new_location}");
  exit;
}

function mysql_prep($string) {
  global $connection;
  $escaped_string = mysqli_real_escape_string($connection, $string);
  return $escaped_string;
}

function confirm_query($result_set) {
  // Test if there was a query error
  if (!$result_set) {
    die("Database query failed.");
  }
}

function confirm_query_query($result_set, $query) {
  // Test if there was a query error
  if (!$result_set) {
    die("Database query failed. {$query}");
  }
}

function form_errors($errors=array()) {
	$output = "";
	if (!empty($errors)) {
	  $output .= "<div class=\"error\">";
	  $output .= "Please fix the following errors:";
	  $output .= "<ul>";
	  foreach ($errors as $key => $error) {
	    $output .= "<li>";
      $output .= htmlentities($error);
      $output .= "</li>";
	  }
	  $output .= "</ul>";
	  $output .= "</div>";
	}
	return $output;
}

//requires current_subject array or null or current_page array or null
function navigation($subject_array, $page_array, $public){
  $output = "<ul class='subjects'>";
  if($public) {
    $subject_set = find_all_subjects(true);
  }
  if(!$public){
    $subject_set = find_all_subjects(false);
  }
  while($subject = mysqli_fetch_assoc($subject_set)) {
  $output .= "<li ";
    if ($subject_array && $subject['id']== $subject_array['id']){
     $output .= "class = 'selected'";
     }
  $output .= ">";
  if($public){
    $output .= "<a href='index.php?subject=";
  }
  if(!$public){
    $output .= "<a href='manage_content.php?subject=";
  }
  $output .= urlencode($subject['id']);
  $output .= "'>";
  $output .= htmlentities($subject["menu_name"]);
  $output .= "</a>";
  //pages
    if($public){
      $page_set = pages_for_subject($subject["id"],true);}
    if(!$public){
      $page_set = pages_for_subject($subject["id"],false);}

    if(!empty($page_set) && $public == true){
        if($subject_array["id"] == $subject["id"] || $page_array["subject_id"] == $subject["id"]) {
                  $output .=     "<ul class='pages'>";
            while($pages= mysqli_fetch_assoc($page_set)) {
            $output .= "<li ";
              if ($page_array && $pages['id']== $page_array["id"]){
                   $output .= "class = 'selected'";
                   }
            $output .= "><a href='index.php?page=";
            $output .= urlencode($pages['id']);
            $output .= "'>";
            $output .= htmlentities($pages["menu_name"]);
            $output .= "</li></a>";
            }//end of pages while loop
            $output .= "</ul></li>";
          } //end of if there is a page_array conditional
         }//end of public if statement
       elseif(!empty($page_set) && $public == false) {
      $output .= "<ul class='pages'>";
          while($pages= mysqli_fetch_assoc($page_set)) {
          $output .= "<li ";
          if ($page_array && $pages['id']== $page_array["id"]){
               $output .= "class = 'selected'";
               }
          $output .= "><a href='manage_content.php?page=";
          $output .= urlencode($pages['id']);
          $output .= "'>";
          $output .= htmlentities($pages["menu_name"]);
          $output .= "</li></a>";
          }//end of pages while loop
          $output .= "</ul></li>";
    } else {}//nothing if $page_set is null}//end of pages if pubic = false conditional

  } //end of subject while loop

  return $output;
}//end of function

//=================user authentication=============================
function find_all_users($active_only) {
  global $connection;
  $query = "SELECT * ";
  $query .= "from user ";
  if($active_only){
    $query .= "WHERE is_active = true ";
  }
  $query .= "ORDER by username ASC";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  return $result;
}

function find_all_user_array($active_only){
  $user_array = array();
  $user_set =find_all_users($active_only);
  while ($user = mysqli_fetch_assoc($user_set)){
    $user_array[] = $website["user_id"];
  }
  return $user_array;
}

function find_all_usernames() {
  global $connection;
  $query = "SELECT username ";
  $query .= "from user ";
  $query .= "ORDER by username ASC";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  return $result;
}

function find_user_by_id($user_id) {
  global $connection;
  $query = "SELECT * ";
  $query .= "from user ";
  $query .= "WHERE user_id = {$user_id} ";
  $query .= " LIMIT 1";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  $user_record = mysqli_fetch_assoc($result);
  if($user_record){
    return $user_record;
  } else {return null;}
}

function find_user_by_username($username) {
  global $connection;
  $query = "SELECT * ";
  $query .= "from user ";
  $query .= "WHERE username = '{$username}' ";
  $query .= " LIMIT 1";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
  $user_record = mysqli_fetch_assoc($result);
  if($user_record){
    return $user_record;
  }else {
    return null;
  }
}

function find_users_by_role($active_only, $role_id){
  global $connection;
  $query = "SELECT * ";
  $query .= "from user ";
  $query .= "WHERE role_id = {$role_id} ";
  if($active_only){
    $query .= "AND is_active = 1 ";
  }
  $query .= "order by username";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  if($result){
    return $result;

    } else {
      return null;
    }
}

function user_array_by_role($active_only, $role_id){
  $user_array = array();
  $user_set = find_users_by_role($active_only, $role_id);
  while ($result = mysqli_fetch_assoc($user_set)){
    $user_array[]=$result["user_id"];
  };
  if($user_array){
    return $user_array;
  }else {
    return null;
  }
}

function find_role_by_role_id($role_id) {
  global $connection;
  $query = "SELECT * ";
  $query .= "from role ";
  $query .= "WHERE ";
  $query .= "role_id = {$role_id} ";
  $query .= "LIMIT 1";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  $user_role = mysqli_fetch_assoc($result);
  if($user_role){
    return $user_role;
  } else {return null;}
}

//generate unique and random string
function generate_salt($length){
  $unique_random_string = md5(uniqid(mt_rand(),true));
  //valid characters for salt are [0-9a-zA-Z./]
  $base_64_string = base64_encode($unique_random_string);
  // exclude +
  $modified_base_64_string = str_replace('+','.',$base_64_string);
  //truncate string to the correct length
  $salt = substr($modified_base_64_string,0,$length);
  return $salt;
}

function authenticate_user($username, $password){
  $user_record = find_user_by_username($username);
  if($user_record){
    $password_verify = password_verify($password, $user_record["hashed_password"]);
    if($password_verify){
      return $user_record;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

function logged_in(){
  return isset($_SESSION["user_id"]);
  }
function admin(){
  return isset($_SESSION["role_id"]);
}

function confirm_login() {
   if(!logged_in()){
    redirect_to("login.php"); }
}

function confirm_admin_login(){
  if(!logged_in()){
   redirect_to("login.php");
 }
 if(admin()!=1){
   redirect_to("login.php");
 }
}


//record status functions and delete functions
function find_active_status($record_id, $table){
  global $connection;
  $query = "SELECT is_active from ";
  $query .= "{$table} ";
  $query .= "WHERE {$table}_id=";
  $query .= "{$record_id}";
  $query .= " LIMIT 1";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
  $result_set = mysqli_fetch_assoc($result);
  $active = $result_set["is_active"];
  if($active==true){
        return $active;
  } else {
      return false;}
}

function toggle_active($record_id, $table){
  global $connection;
  $active = "";
  $active = find_active_status($record_id, $table);
  $query = "UPDATE {$table} set ";
  $query .= "is_active =";
  if($active){
    $query .= "false ";
  } else {
    $query .= "true ";
  }
  $query .= "WHERE ";
  $query .= "{$table}_id=";
  $query .= "{$record_id}";
  $query .= " LIMIT 1";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  if($result && mysqli_affected_rows($connection) == 1){
    $_SESSION["message"] = "update was successful";
      redirect_to("client.php");
    } else {
    $_SESSION["message"] = "database could not update page record";
      redirect_to("client.php");
    }
}

function delete($record_id, $table) {
    global $connection;
    $query = "DELETE from {$table} WHERE {$table}_id = {$record_id}";
    $result = mysqli_query($connection, $query);
    confirm_query_query($result, $query);
    return $result;
}

function delete_by_foreign_key($record_id, $col_name, $table) {
    global $connection;
    $query = "DELETE from {$table} WHERE {$col_name} = {$record_id}";
    $result = mysqli_query($connection, $query);
    confirm_query_query($result, $query);
    return $result;
}

function delete_user($user_id){
  global $connection;
    $result1 = delete_by_foreign_key($user_id, "user_id","user_url");
    $result2 = delete($user_id, "user");
      if($result2 && mysqli_affected_rows($connection) >= 1){
      $_SESSION["message"] = "delete was successful";
        redirect_to("client.php");
      } else {
      $_SESSION["message"] = "database could not delete record";
      redirect_to("client.php");
      }
}

function delete_url($url_id){
  global $connection;
    $result1 = delete_by_foreign_key($url_id, "url_id","user_url");
    $result2 = delete($url_id, "url");
      if($result2 && mysqli_affected_rows($connection) >= 1){
      $_SESSION["message"] = "delete was successful";
        redirect_to("client.php");
      } else {
      $_SESSION["message"] = "database could not delete record";
      redirect_to("client.php");
      }
}

//find websites and url-user associations
function find_website_by_id($url_id) {
  global $connection;
  $query = "SELECT * ";
  $query .= "from url ";
  $query .= "WHERE url_id = {$url_id} ";
  $query .= " LIMIT 1";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  $url_record = mysqli_fetch_assoc($result);
  if($url_record){
    return $url_record;
  } else {return null;}
}


function find_all_websites($active_only) {
  global $connection;

  $query = "Select * from url ";
  if($active_only){
    $query .= "WHERE is_active = 1 ";
  }
  $query .= "order by url_name asc";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  if($result){
    return $result;
  } else {
    return null;
  }
}

function website_id_array($active_only){
  $website_array = array();
  $website_set = find_all_websites($active_only);
  while ($website = mysqli_fetch_assoc($website_set)){
    $website_array[] = $website["url_id"];
  }
  return $website_array;

}
//is the array an array of urls or users? need to know which way to make association. $array_type is either user or url
function create_user_url_assoc($id, $array, $array_type) {
  global $connection;
  if($array_type == 'url'){
    $col1 = 'url_id';
    $col2 = 'user_id';
  } elseif($array_type == 'user'){
    $col1 = 'user_id';
    $col2 = 'url_id';
  }
  foreach($array as $array_field){
    $query = "INSERT into user_url ($col1, $col2) VALUES ($array_field, {$id})";

    $result = mysqli_query($connection, $query);
    confirm_query_query($result, $query);
  }
  return $result;
}


function find_url_array_by_user($user_id){
  global $connection;
  $output = array();
  $query = "SELECT * from user_url WHERE user_id = {$user_id}";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  while($user_urls = mysqli_fetch_assoc($result)){
    $output[] = $user_urls["url_id"];
  }
  return $output;
}

function find_websites_by_user($user_id){
  global $connection;
  $query = "Select u.* from url u, user_url uu ";
  $query .= "WHERE u.url_id = uu.url_id AND ";
  $query .= "uu.user_id = {$user_id} ";
  $query .= "order by url_name asc";
  $result = mysqli_query($connection, $query);
  confirm_query_query($result, $query);
  if($result){
    return $result;
  } else {
    return null;
  }
}

function is_item_in_list($item_id, $array){
  $result = has_inclusion_in($current_user, $user_array);
}
?>
