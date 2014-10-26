<?php
require("../include/authorization-functions.php");
$response = do_authorization();
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Authorization Endpoint</title>
    <link rel="stylesheet" type="text/css" href="common.css">
  </head>
  <body class="font">
    <div class="page_title">Authorization Endpoint</div>
    <div class="content">
      <h2>Client Application</h2>
      <div class="indent">
        <?= $response['client']['clientName'] ?>
      </div>

      <h2>Requested Permissions</h2>
      <ol>
        <?php
            if ($response['scopes'])
            {
                foreach ($response['scopes'] as $scope)
                {
                    echo("<li>{$scope['name']}");
                }
            }
        ?>
      </ol>

      <h2>Authorize?</h2>
      <div class="indent">
        <form method="post" action="/authorization_submit.php">
          <button type="submit" name="authorized" value="true" class="font"
          >Authorize</button>
          <button type="submit" name="denied" value="true" class="font"
          >Deny</button>
        </form>
      </div>
    </div>
  </body>
</html>
