<?php
require('../include/config.php');


/**
 * A class representing a Web response.
 */
class WebResponse
{
    private $status;
    private $body;
    private $headers;

    public function __construct($status, $body = null)
    {
        $this->status  = $status;
        $this->body    = $body;
        $this->headers = [
            'Cache-Control' => 'no-store',
            'Pragma'        => 'no-cache'
        ];
    }

    // Set an HTTP header.
    private function set_header($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    // Set Content-Type.
    private function set_content_type($content_type)
    {
        return $this->set_header("Content-Type", "{$content_type};charset=UTF-8");
    }

    // Set "application/json".
    public function json()
    {
        return $this->set_content_type('application/json');
    }

    // Set "text/plain".
    public function plain()
    {
        return $this->set_content_type('text/plain');
    }

    // Set "text/html".
    public function html()
    {
        return $this->set_content_type('text/html');
    }

    // Set Location header.
    public function location($location)
    {
        return $this->set_header('Location', $location);
    }

    // Set WWW-Authenticate header.
    public function wwwAuthenticate($challenge)
    {
        return $this->set_header('WWW-Authenticate', $challenge);
    }

    public function finish()
    {
        $this->write_response();
        exit();
    }

    private function write_response()
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value)
        {
            header("{$name}: {$value}");
        }

        if ($this->body != null)
        {
            print($this->body);
        }
    }
}


/**
 * Call an Authlete's API.
 */
function call_api($path, $parameters)
{
    global $AUTHLETE_BASE_URL;
    global $SERVICE_API_KEY;
    global $SERVICE_API_SECRET;

    $curl = curl_init($AUTHLETE_BASE_URL . $path);

    curl_setopt_array($curl, [
        CURLOPT_POST           => 1,
        CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
        CURLOPT_USERPWD        => $SERVICE_API_KEY . ':' . $SERVICE_API_SECRET,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode($parameters),
        CURLOPT_RETURNTRANSFER => 1
    ]);

    $body  = curl_exec($curl);
    $errno = curl_errno($curl);
    $error = curl_error($curl);

    curl_close($curl);

    if (CURLE_OK !== $errno)
    {
        // The error message.
        $message = "Authlete's {$path} API failed: {$error}";

        // Try to parse the response as JSON.
        $json = json_decode($body, true);

        // If the response was parses as JSON successfully.
        if (JSON_ERROR_NONE === json_last_error())
        {
            // Use 'resultMessage' as the error message.
            $message = $json['resultMessage'];
        }

        error_log($message, 0);

        // The API call failed.
        (new WebResponse(500, $message))->plain()->finish();
        return null; // Not reach here.
    }

    // The response from the API is JSON.
    $json = json_decode($body, true);

    // The result message of the API call.
    //error_log("Authlete's {$path} API result: {$json['resultMessage']}");

    return $json;
}


/**
 * Call Authlete's /auth/authorization API.
 */
function call_authorization_api($parameters)
{
    return call_api('/api/auth/authorization', [
        'parameters' => $parameters
    ]);
}


/**
 * Call Authlete's /auth/authorization/fail API.
 */
function call_authorization_fail_api($ticket, $reason)
{
    return call_api('/api/auth/authorization/fail', [
        'ticket' => $ticket,
        'reason' => $reason
    ]);
}


/**
 * Call Authlete's /auth/authorization/issue API.
 */
function call_authorization_issue_api($ticket, $subject, $auth_time)
{
    return call_api('/api/auth/authorization/issue', [
        'ticket'   => $ticket,
        'subject'  => $subject,
        'authTime' => $auth_time
    ]);
}


/**
 * Call Authlete's /auth/token API.
 */
function call_token_api($parameters, $client_id, $client_secret)
{
    return call_api('/api/auth/token', [
        'parameters'   => $parameters,
        'clientId'     => $client_id,
        'clientSecret' => $client_secret
    ]);
}


/**
 * Call Authlete's /auth/token/fail API.
 */
function call_token_fail_api($ticket, $reason)
{
    return call_api('/api/auth/token/fail', [
        'ticket' => $ticket,
        'reason' => $reason
    ]);
}


/**
 * Cal Authlete's /auth/introspection API.
 */
function call_introspection_api($token, $scopes, $subject)
{
    return call_api('/api/auth/introspection', [
        'token'   => $token,
        'scopes'  => $scopes,
        'subject' => $subject
    ]);
}


function pack_parameters()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        return $_SERVER['QUERY_STRING'];
    }
    elseif ($_POST)
    {
        $packed = "";

        foreach ($_POST as $key => $value)
        {
            $key_encoded   = urlencode($key);
            $value_encoded = urlencode($value);
            $packed .= "{$key_encoded}={$value_encoded}&";
        }

        return $packed;
    }
    else
    {
        return null;
    }
}
?>
