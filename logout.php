<?php
session_start();
session_unset();
session_destroy();
$base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$page = $base_url . "/TCC/Procafeinacao/acesso/login";
header("Location: ". $page);
exit;
?>