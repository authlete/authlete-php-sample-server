<?php
require('../include/authorization-functions.php');


// Extract the authorization response from the session.
session_start();
$response = json_decode($_SESSION['res']);

// Extract the ticket.
$ticket = $response->ticket;

// Clear the session data.
$_SESSION['res'] = null;

// If the end-user authorized the client application.
if ($_POST['authorized'] == 'true')
{
    // Issue an authorization code to the client application.
    $subject   = 'joe';
    $auth_time = time();

    do_authorization_issue($ticket, $subject, $auth_time);
}
else
{
    // Notify the client application that the end-user denied
    // the authorization request.
    do_authorization_fail($ticket, 'DENIED');
}
?>
