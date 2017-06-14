<div class ="footer">Copyright <?php echo date('Y'); ?>, The Dame Ranch, LLC all rights reserved</div>
<!-- script calls -->
<script src='../../scripts/lib-scripts.js'></script>
<script src='../../scripts/main.js'></script>
<script>

</script>


</body>
</html>
<?php
 if (isset($connection)) {
   mysqli_close($connection);
 }
  // 5. Close database connection
?>
