<?php
$errors = array();

function field_name_as_text($fieldname) {
  $fieldname = str_replace("_", " ",$fieldname);
  $fieldname = ucfirst($fieldname);
  return $fieldname;
}
// * presence
// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) {
	return isset($value) && $value !== "";
}

function validate_presences($required_fields){
  global $errors;
  foreach ($required_fields as $field){
    $value = trim($_POST[$field]);
    //if has presence value returns 'false'
    if(!has_presence($value)) {
      $errors[$field] = field_name_as_text($field) . " can't be blank";
    }
  }
}

// * string length
// max length
function has_max_length($value, $max) {
	return strlen($value) <= $max;
}

function validate_max_lengths($fields_with_max_lengths) {
	global $errors;
	// Expects an assoc. array
	foreach($fields_with_max_lengths as $field => $max) {
		$value = trim($_POST[$field]);
	  if (!has_max_length($value, $max)) {
	    $errors[$field] = field_name_as_text($field) . " is too long";
	  }
	}
}

// * string length
// min length
function has_min_length($value, $min) {
	return strlen($value) >= $min;
}

function validate_min_lengths($fields_with_min_lengths) {
	global $errors;
	// Expects an assoc. array
	foreach($fields_with_min_lengths as $field => $min) {
		$value = trim($_POST[$field]);
	  if (!empty($value) && !has_min_length($value, $min)) {
	    $errors[$field] = field_name_as_text($field) . " requires at least " . $min . " characters";
	  }
	}
}

// * inclusion in a set
function has_inclusion_in($value, $set) {
	return in_array($value, $set);
}

function user_not_in_set(){
  global $errors;
  $current_user = trim($_POST["username"]);
  $user_array = array();
  $user_set = find_all_usernames();
  while($user_list = mysqli_fetch_array($user_set)){
    $user_array[] = $user_list["username"];
  }
  $result = has_inclusion_in($current_user, $user_array);
  if($result){
    $errors["exists"] = "user name already exists";
      }

}

function field_has_no_whitespace($fields_with_no_whitespace){
  global $errors;
    // Expects an assoc. array
  foreach($fields_with_no_whitespace as $field) {
  $text = trim($_POST[$field]);
    if(preg_match('/\s/',$text)){
      $errors[$field] = field_name_as_text($field) . " cannot contain spaces";
    }
  }
}

function fields_are_equal($value1, $value2){
  global $errors;
	// Expects an assoc. array
	 $comp1 = trim($_POST[$value1]);
   $comp2 = trim($_POST[$value2]);
    if($comp1 != $comp2){
      $errors["new password confirm"] = "password fields are not equal";
    }
}





?>
