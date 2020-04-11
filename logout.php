<?php
  session_start();
  session_unset();
  session_destroy();
  header("Location: index.php");   //redirect your page to login page
?>
