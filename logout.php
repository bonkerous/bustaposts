<?php
session_start();
session_unset();
session_destroy();
echo('Session destroyed, <a href="index.php">go back to index</a>')
?>