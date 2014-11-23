<?php
require('../include/authlete-api.php');


/**
 * Call Authlete's /auth/token API and dispatch the
 * processing according to the action.
 */
function do_token($client_id, $client_secret)
{
    // Request parameters.
    $parameters = pack_parameters();

    // Call Authlete's /auth/token API.
    $response = call_token_api($parameters, $client_id, $client_secret);

    // The content of the response to the client.
    $content = $response['responseContent'];

    // "action" denotes the next action.
    switch ($response['action'])
    {
        case 'INVALID_CLIENT':
            // 401 Unauthorized
            //   Client authentication failed.
            (new WebResponse(401, $content))->json()
                ->wwwAuthenticate('Basic realm="/token"')->finish();
            return null; // Not reach here.

        case 'INTERNAL_SERVER_ERROR':
            // 500 Internal Server Error
            //   The API request from this implementation was wrong
            //   or an error occurred in Authlete.
            (new WebResponse(500, $content))->json()->finish();
            return null; // Not reach here.

        case 'BAD_REQUEST':
            // 400 Bad Request
            //   The token request from the client was wrong.
            (new WebResponse(400, $content))->json()->finish();
            return null; // Not reach here.

        case 'PASSWORD':
            // Process the token request whose flow is
            // "Resource Owner Password Credentials".
            return handle_password($response);

        case 'OK':
            // 200 OK
            //   The token request from the client was valid. An access
            //   token is issued to the client application.
            (new WebResponse(200, $content))->json()->finish();
            return null; // Not reach here.

        default:
            // This never happens.
            (new WebResponse(500, 'Unknown action'))->plain()->finish();
            return null; // Not reach here.
    }
}


/**
 * Call Authlete's /auth/token/fail API and dispatch
 * the processing according to the action.
 */
function do_token_fail($ticket, $reason)
{
    // Call Authlete's /auth/token/fail API.
    $response = call_token_fail_api($ticket, $reason);

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
            //   Authlete successfully generated an error response
            //   for the client application.
            (new WebResponse(400, $content))->json()->finish();
            return null; // Not reach here.

        default:
            // This never happens.
            (new WebResponse(500, 'Unknown action'))->plain()->finish();
            return null; // Not reach here.
    }
}


function handle_password($response)
{
    // This implementation does not support "Resource Owner
    // Password Credentials". So, handle_password always fails.
    do_token_fail($response['ticket'], 'UNKNOWN');

    return null; // Not reach here.
}