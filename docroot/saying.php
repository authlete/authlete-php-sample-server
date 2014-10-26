<?php
require('../include/introspection-functions.php');

// Extract an access token from the request.
$token = extract_access_token();

// Introspect the access token by /auth/introspection API.
$response = do_introspection($token, ['saying'], null);

// Saying list.
$elements = [
    [ 'Albert Einstein',
      'A person who never made a mistake never tried anything new.' ],
    [ 'John F. Kennedy',
      'My fellow Americans, ask not what your country can do for you, ask what you can do for your country.' ],
    [ 'Steve Jobs',
      'Stay hungry, stay foolish.' ],
    [ 'Walt Disney',
      'If you can dream it, you can do it.' ],
    [ 'Peter Drucker',
      'Whenever you see a successful business, someone once made a courageous decision.' ],
    [ 'Thomas A. Edison',
      'Genius is one percent inspiration and ninety-nine percent perspiration.' ]
];

// Pick up a saying.
$element = $elements[ rand(0, count($elements) - 1) ];

// Content as JSON.
$content = json_encode(['person' => $element[0], 'saying' => $element[1]]);

// 200 OK; application/json
(new WebResponse(200, $content))->json()->finish();
?>
