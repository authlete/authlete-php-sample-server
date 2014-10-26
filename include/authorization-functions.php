<?php
require('../include/authlete-api.php');


/**
 * Call Authlete's /auth/authorization API and
 * dispatch the processing according to the action.
 */
function do_authorization()
{
    // Request parameters.
    $parameters = pack_parameters();

    // Call Authlete's /auth/authorization API.
    $response = call_authorization_api($parameters);

    // The content of the response to the client.
    $content = $response['responseContent'];

    // "action" denotes the next action.
    switch ($response['action'])
    {
        case 'INTERNAL_SERVER_ERROR':
            // 500 Internal Server Error
            //   The API request from this implementation was wrong
            //   or an error occurred in Authlete.
            (new WebResponse(500, $content))->json()->finish();
            return null; // Not reach here.

        case 'BAD_REQUEST':
            // 400 Bad Request
            //   The authorization request was invalid.
            (new WebResponse(400, $content))->json()->finish();
            return null; // Not reach here.

        case 'LOCATION':
            // 302 Found
            //   The authorization request was invalid and the error
            //   is reported to the redirect URI using Location header.
            (new WebResponse(302))->location($content)->finish();
            return null; // Not reach here.

        case 'FORM':
            // 200 OK
            //   The authorization request was invalid and the error
            //   is reported to the redirect URI using HTML Form Post.
            (new WebResponse(200, $content))->html()->finish();
            return null; // Not reach here.

        case 'NO_INTERACTION':
            // Process the authorization request w/o user interaction.
            return handle_no_interaction($response);

        case 'INTERACTION':
            // Process the authorization request with user interaction.
            return handle_interaction($response);

        default:
            // This never happens.
            (new WebResponse(500, 'Unknown action'))->plain()->finish();
            return null; // Not reach here.
    }
}


/**
 * Call Authlete's /auth/authorization/fail API and
 * dispatch the processing according to the action.
 */
function do_authorization_fail($ticket, $reason)
{
    // Call Authlete's /auth/authorization/fail API.
    $response = call_authorization_fail_api($ticket, $reason);

    // The content of the response to the client.
    $content = $response['responseContent'];

    // "action" denotes the next action.
    switch ($response['action'])
    {
        case 'INTERNAL_SERVER_ERROR':
            // 500 Internal Server Error
            //   The API request from this implementation was wrong
            //   or an error occurred in Authlete.
            (new WebResponse(500, $content))->json()->finish();
            return null; // Not reach here.

        case 'BAD_REQUEST':
            // 400 Bad Request
            //   The ticket is no longer valid (deleted or expired)
            //   and the reason of the invalidity was probably due
            //   to the end-user's too-delayed response to the
            //   authorization UI.
            (new WebResponse(400, $content))->json()->finish();
            return null; // Not reach here.

        case 'LOCATION':
            // 302 Found
            //   The authorization request was invalid and the error
            //   is reported to the redirect URI using Location header.
            (new WebResponse(302))->location($content)->finish();
            return null; // Not reach here.

        case 'FORM':
            // 200 OK
            //   The authorization request was invalid and the error
            //   is reported to the redirect URI using HTML Form Post.
            (new WebResponse(200, $content))->html()->finish();
            return null; // Not reach here.

        default:
            // This never happens.
            (new WebResponse(500, 'Unknown action'))->plain()->finish();
            return null; // Not reach here.
    }
}


/**
 * Call Authlete's /auth/authorization/issue API and
 * dispatch the processing according to the action.
 */
function do_authorization_issue($ticket, $subject, $auth_time)
{
    // Call Authlete's /auth/authorization/issue API.
    $response = call_authorization_issue_api($ticket, $subject, $auth_time);

    // The content of the response to the client.
    $content = $response['responseContent'];

    // "action" denotes the next action.
    switch ($response['action'])
    {
        case 'INTERNAL_SERVER_ERROR':
            // 500 Internal Server Error
            //   The API request from this implementation was wrong
            //   or an error occurred in Authlete.
            (new WebResponse(500, $content))->json()->finish();
            return null; // Not reach here.

        case 'BAD_REQUEST':
            // 400 Bad Request
            //   The ticket is no longer valid (deleted or expired)
            //   and the reason of the invalidity was probably due
            //   to the end-user's too-delayed response to the
            //   authorization UI.
            (new WebResponse(400, $content))->json()->finish();
            return null; // Not reach here.

        case 'LOCATION':
            // 302 Found
            //   Triggering redirection with either (1) an authorization
            //   code, an ID token and/or an access token (on success)
            //   or (2) an error code (on failure).
            (new WebResponse(302))->location($content)->finish();
            return null; // Not reach here.

        case 'FORM':
            // 200 OK
            //   Triggering redirection with either (1) an authorization
            //   code, an ID token and/or an access token (on success)
            //   or (2) an error code (on failure).
            (new WebResponse(200, $content))->html()->finish();
            return null; // Not reach here.

        default:
            // This never happens.
            (new WebResponse(500, 'Unknown action'))->plain()->finish();
            return null; // Not reach here.
    }
}


function handle_no_interaction($response)
{
    // This implementation does not support "prompt=none".
    // So, handle_no_interaction always fails.
    do_authorization_fail($response['ticket'], 'UNKNOWN');

    // Not reach here.
    return null;
}


function handle_interaction($response)
{
    // Put the response from the /auth/authorization API into
    // the session because it is needed later at '/authorization_submit.php'.
    session_start();

    $_SESSION['res'] = json_encode($response);

    return $response;
}
?>
