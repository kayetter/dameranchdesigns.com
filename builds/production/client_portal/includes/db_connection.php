<?php
  // 1. Create a database connection

  define('DB_SERVER', 'localhost' );
  define('DB_USER', 'kayetter_admin');
  define('DB_PASS', 'a2D^^,9D8BBb');
  define('DB_NAME', 'kayetter_drdclient');

  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
  // Test if connection succeeded
  if(mysqli_connect_errno()) {
    die("Database connection failed: " .
         mysqli_connect_error() .
         " (" . mysqli_connect_errno() . ")"
    );
  }
?>
