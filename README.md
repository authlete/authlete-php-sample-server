
> NOTE: This repo has been archived and is no longer maintained or supported. For current active projects visit [authlete.com/developers](https://www.authlete.com/developers).
authlete-php-sample-server
==========================

Overview
--------

A sample implementation of OAuth 2.0 server in PHP using Authlete. `server.sh`
is the script to start the server which implements OAuth 2.0 endpoints (the
authorization endpoint and the token endpoint) and two protected resource
endpoints (`/fortune` and `/saying`) as examples.

See [Authlete Getting Started](https://www.authlete.com/authlete_getting_started.html)
for details.

Note that the value of `"redirectUris"` in `client.json` contained in this
source tree
([authlete-php-sample-server](https://github.com/authlete/authlete-php-sample-server.git))
is slightly different from that of
[authlete-ruby-sample-server](https://github.com/authlete/authlete-ruby-sample-server.git).


License
-------

Apache License, Version 2.0


Source Download
---------------

```
git clone https://github.com/authlete/authlete-php-sample-server.git
```


Set Up
------

Open `php.ini` with a text editor and change the value of `extension_dir` for
your environment.


Configuration
-------------

After downloading the source code, open `include/config.php` with a text editor
and change the values of the following variables.

* `$SERVICE_API_KEY`
* `$SERVICE_API_SECRET`

`$SERVICE_API_KEY` and `$SERVICE_API_SECRET` are the credentials of a service
which you have created by calling Authlete's `/service/create` API.

As necessary, change the value of the following global variable, too.

* `$AUTHLETE_BASE_URL`

`$AUTHLETE_BASE_URL` is the URL of the Authlete server you use. For evaluation,
set `https://evaluation-dot-authlete.appspot.com` to the variable.


Endpoints
---------

The following endpoints are implemented.

* The top page
  - [http://localhost:8000/](http://localhost:8000/)

* The authorization endpoint
  - [http://localhost:8000/authorization.php](http://localhost:8000/authorization.php)

* The token endpoint
  - [http://localhost:8000/token.php](http://localhost:8000/token.php)

* The protected resource endpoints
  - [http://localhost:8000/fortune.php](http://localhost:8000/fortune.php)
  - [http://localhost:8000/saying.php](http://localhost:8000/saying.php)

* The redirection endpoint (for client)
  - [http://localhost:8000/callback.php](http://localhost:8000/callback.php)

Note that it is not an OAuth 2.0 server that should implement a redirection
endpoint. Instead, it is the developer of the client application who has to
prepare the redirection endpoint. However, this sample server implements an
redirection endpoint (= the last one in the list above) just to show what
a redirection endpoint receives. Please don't be confused.


Test Steps
----------

1. Run `server.sh`.

2. Access the top page ([http://localhost:8000/](http://localhost:8000/))

3. At the top page, input the client ID of your client application (which
   you have registered by calling Authlete's `/client/create` API) and
   press "Authorization Request" button, and the web browser is redirected
   to the authorization endpoint (http://localhost:8000/authorization.php).

4. At the authorization endpoint, press "Authorize" button, and the web
   browser is redirected to the client's redirection endpoint
   (http://localhost:8000/callback.php). On success, an authorization code
   is displayed in the endpoint.

5. At the redirection endpoint, input the client ID of your client
   application and press "Token Request" button, and you receive a JSON
   containing an access token.

6. Access a protected resource endpoint with the access token issued at
   the step above. For example,
   - http://localhost:8000/fortune.php?access_token=${ACCESS_TOKEN}
   - http://localhost:8000/saying.php?access_token=${ACCESS_TOKEN}

   
Note
----

The quality of this source code does not satisfy the commercial level.
Especially:

* The endpoints are not protected by TLS.

* The authorization endpoint does not support HTTP POST method
  (OpenID Connect requires it).

* The authorization endpoint does not authenticate the end-user.
  End-user authentication always succeeds as if `joe` logged in the
  service every time. Authentication Context Class Reference, Maximum
  Authentication Age and others that should be taken into consideration
  are ignored.

* The authorization endpoint always fails when the request contains
  `prompt=none`.

* 'Claims' and 'ACR' are not set in the request for
  `/auth/authorization/issue` API. They are needed when the authorization
  endpoint supports any of `response_type`s which issue an ID token.

* The token endpoint does not support "Resource Owner Password Credentials",
  so it always fails when the token request contains `grant_type=password`.


Links
-----

* [Authlete Home Page](https://www.authlete.com/)
* [Authlete Documents](https://www.authlete.com/documents.html)
* [Authlete Getting Started](https://www.authlete.com/authlete_getting_started.html)
* [Authlete Web APIs](https://www.authlete.com/authlete_web_apis.html)
