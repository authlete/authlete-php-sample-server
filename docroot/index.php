<html>
  <head>
    <meta charset="utf-8">
    <title>Example Service</title>
    <link rel="stylesheet" type="text/css" href="common.css">
  </head>
  <body class="font">
    <div class="page_title">Example Service</div>
    <div class="content">
      <h2>Authorization Request</h2>
      <div class="indent">
        <pre>http://localhost:<?= $_SERVER['SERVER_PORT'] ?>/authorization.php?client_id=${CLIENT_ID}&response_type=code&scope=fortune+saying</pre>

        <div style="margin-top: 2em;">
          <form method="GET" action="/authorization.php" target="_blank">
            <input type="hidden" name="response_type" value="code" />
            <input type="hidden" name="scope" value="fortune saying" />

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
                      <nobr>Authorization Request</nobr>
                    </button>
                  </td>
              </tbody>
            </table>
          </form>
        </div>
      </div>
      <h2>Protected Resource Request</h2>
      <div class="indent">
        <div style="margin-top: 2em;">
          <form method="GET" action="/fortune.php" target="_blank">
            <table cellpadding="5" border="1">
              <thead>
                <tr><th align="left">fortune</th></tr>
              </thead>
              <tbody>
                <tr>
                  <td align="left" bgcolor="#DDD">
                    <code>http://localhost:<?= $_SERVER['SERVER_PORT'] ?>/fortune.php?access_token=<input type="text" name="access_token" size="50"/></code>
                  </td>
                </tr>
                <tr><td><button type="submit" class="font"><nobr>Protected Resource Request</nobr></button></td></tr>
              </tbody>
            </table>
          </form>
        </div>
        <div style="margin-top: 2em;">
          <form method="GET" action="/saying.php" target="_blank">
            <table cellpadding="5" border="1">
              <thead>
                <tr><th align="left">saying</th></tr>
              </thead>
              <tbody>
                <tr>
                  <td align="left" bgcolor="#DDD">
                    <code>http://localhost:<?= $_SERVER['SERVER_PORT'] ?>saying.php?access_token=<input type="text" name="access_token" size="51"/></code>
                  </td>
                  </tr>
                <tr><td><button type="submit" class="font"><nobr>Protected Resource Request</nobr></button></td></tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
