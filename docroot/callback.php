<?php
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Redirection Endpoint</title>
    <link rel="stylesheet" type="text/css" href="common.css">
  </head>
  <body class="font">
    <div class="page_title">Redirection Endpoint</div>
    <div class="content">
      <h2>Query Parameters</h2>
      <div class="indent">
        <table cellpadding="5" border="1">
          <thead>
            <tr>
              <th>Name</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
          <?php
              if ($_GET)
              {
                  foreach ($_GET as $key => $value)
                  {
                      echo("<tr><td>{$key}</td><td>{$value}</td></tr>");
                  }
              }
          ?>
          </tbody>
        </table>
      </div>

      <?php
          if ($_GET == null || $_GET['code'] == null)
          {
              echo('</div></body></html>');
              exit();
          }
      ?>

      <h2>Token Request</h2>
      <div class="indent">
        <pre>
curl http://localhost:<?= $_SERVER['SERVER_PORT'] ?>/token.php \
     -d client_id=${CLIENT_ID} \
     -d grant_type=authorization_code \
     -d code=<?= $_GET['code'] ?></pre>

        <div style="margin-top: 2em;">
          <form method="POST" action="/token.php">
            <input type="hidden" name="grant_type" value="authorization_code" />
            <input type="hidden" name="code" value="<?= $_GET['code'] ?>" />

            <table cellpadding="5" border="1">
              <thead>
                <tr>
                  <th>Client ID</th>
                  <th><input type="text" name="client_id" size="20" /></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2">
                    <button type="submit" class="font">
                      <nobr>Token Request</nobr>
                    </button>
                  </td>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
