<?php  

// Logger ut en bruker fra applikasjonen

session_start();
session_destroy();
print("<meta http-equiv='refresh' content='0;url=index.php'>");
?>