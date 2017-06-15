<?php require_once("../includes/session.php"); ?>
<?php
  // 1. Create a database connection

  define('DB_SERVER', 'localhost' );
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'drdclient');

  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
  // Test if connection succeeded
  if(mysqli_connect_errno()) {
    die("Database connection failed: " .
         mysqli_connect_error() .
         " (" . mysqli_connect_errno() . ")"
    );
  }
?>

<!-- ====================page content ===================================-->

<pre>
<?php
function confirm_query($result_set) {
  // Test if there was a query error
  if (!$result_set) {
    die("Database query failed.");
  }
}


function find_all_users() {
  global $connection;
  $query = "SELECT * ";
  $query .= "from users ";
  $query .= "ORDER by username ASC";
  $result = mysqli_query($connection, $query);
  confirm_query($result);
  return $result;
}


$test = find_all_users();
$output = array();
  while($test_set = mysqli_fetch_array($test)){
    $output[] = $test_set["username"];
  }
  print_r($output);

?>
</pre>


<?php include("../layouts/footer.php") ?>
