<?php
session_start();
session_unset();
session_destroy();
header("Location: acesso/login");
exit;
?>