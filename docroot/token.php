<?php
require("../include/token-functions.php");

// The request may specify its client credentials
// using Basic Authentication.
$client_id     = $_SERVER['PHP_AUTH_USER'];
$client_secret = $_SERVER['PHP_AUTH_PW'];

do_token($client_id, $client_secret);
?>
