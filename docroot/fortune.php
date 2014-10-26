<?php
require('../include/introspection-functions.php');

// Extract an access token from the request.
$token = extract_access_token();

// Introspect the access token by /auth/introspection API.
$response = do_introspection($token, ['saying'], null);

// Fortune list.
$elements = [
    'You will meet your fate today. Be dressed better than usual.',
    'Someone will bring you what can change your destiny. Be on the lookout.',
    'Good news will arrive. Prepare a party.'
];

// Pick up a fortune.
$element = $elements[ rand(0, count($elements) - 1) ];

// Content as JSON.
$content = json_encode(['fortune' => $element]);

// 200 OK; application/json
(new WebResponse(200, $content))->json()->finish();
?>
