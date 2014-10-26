<?php
require('../include/authlete-api.php');


/**
 * Call Authlete's /auth/introspection API.
 * A response from the API is returned when the
 * access token is valid. Otherwise, a WebException
 * is raised.
 */
function do_introspection($token, $scopes, $subject)
{
    // Call Authlete's /auth/introspection API.
    $response = call_introspection_api($token, $scopes, $subject);

    // The content of the response to the client.
    $content = $response['responseContent'];

    // "action" denotes the next action.
    switch ($response['action'])
    {
        case 'INTERNAL_SERVER_ERROR':
            // 500 Internal Server Error
            //   The API request from this implementation was wrong
            //   or an error occurred in Authlete.
            (new WebResponse(500))->wwwAuthenticate($content)->finish();
            return null; // Not reach here.

        case 'BAD_REQUEST':
            // 400 Bad Request
            //   The request from the client application does not
            //   contain an access token.
            (new WebResponse(400))->wwwAuthenticate($content)->finish();
            return null; // Not reach here.

        case 'UNAUTHORIZED':
            // 401 Unauthorized
            //   The presented access token does not exist or has expired.
            (new WebResponse(401))->wwwAuthenticate($content)->finish();
            return null; // Not reach here.

        case 'FORBIDDEN':
            // 403 Forbidden
            //   The access token does not cover the required scopes
            //   or the subject associated with the access token is
            //   different.
            (new WebResponse(403))->wwwAuthenticate($content)->finish();
            return null; // Not reach here.

        case 'OK':
            // The access token is valid (= exists and has not expired).
            return $response;

        default:
            // This never happens.
            (new WebResponse(500, "Unknown action"))->plain()->finish();
            return null; // Not reach here.
    }
}


/**
 * Extract an access token (RFC 6750)
 */
function extract_access_token()
{
    $header = $_SERVER['HTTP_AUTHORIZATION'];

    if ($header != null && preg_match('/^Bearer[ ]+(.+)/i', $header, $captured))
    {
        return base64_decode($captured);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        return $_GET['access_token'];
    }
    else
    {
        return $_POST['access_token'];
    }
}
?>
