<?php
/* INSTRUCTIONS
 * 1) Edit the following paragraph to match your database setup,
 *    credentials and desired settings
 * 2) Upload to your webserver (preferably in a password-protected directory)
 * 3) Open in a web browser
*/

define('DB_HOST', 'localhost');
define('DB_NAME', 'database_name');
define('DB_TABLE', 'mybb_invitecodes');
define('DB_USER', 'username');
define('DB_PASS', 'password');

define('MAXUSES', 1);
define('EMAIL', '');
define('EXPIRE', 0);
define('PRIMARYGROUP', 11);
define('OTHERGROUPS', '24');
define('CREATEDBY', 1);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Invitation Generator</title>
</head>
<body>

<?php if(!isset($_POST['quantity'])) { ?>
  <h1>Invitation Generator</h1>
  <form method="post">
    <label>
      Quantity
      <input type="number" value="60" name="quantity" />
    </label>

    <input type="submit" value="Generate" />
  </form>
<?php } else { ?>
  <h1>Generated Codes</h1>
  <p>
    All these codes have been added to the database and are now available for use.
    <a href="">Generate more codes</a>
  </p>

  <ol style="font-family: monospace">
  <?php
  $db = new PDO(
    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
    DB_USER, DB_PASS
  );

  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $quantity = intval($_POST['quantity']);

  for($i = 0; $i < $quantity; $i++) {
    $length = 8; // minimum length

    for($j = 0; true; $j++) {
      $code = substr(sha1(mt_rand()), 0, $length);

      $check = $db->prepare('SELECT 0 FROM ' . DB_TABLE . ' WHERE code = ?');
      $check->execute(array($code));

      if(count($check->fetchAll()) == 0)
        break; // Ok, the invitation code is unique
      else if($j > 100) {
        if($length >= 10) { // invitation codes cannot be longer than 10 chars
          echo '<h2>Aborted: To much collisions.</h2>';
          exit;
        }

        // let's try again!
        $j = 0;
        $length++;
      }
    }

    $stmt = $db->prepare('INSERT INTO ' . DB_TABLE . " (code, maxuses, email,
      expire, primarygroup, othergroups, createdby) VALUES (
      :code, :maxuses, :email, :expire, :primarygroup, :othergroups, :createdby
    )");

    $stmt->execute(array(
      'code' => $code,
      'maxuses' => MAXUSES,
      'email' => EMAIL,
      'expire' => EXPIRE,
      'primarygroup' => PRIMARYGROUP,
      'othergroups' => OTHERGROUPS,
      'createdby' => CREATEDBY,
    ));

    echo "<li>$code</li>";
  }
  ?>
  </ol>

  Done.
<?php } ?>
</body>
</html>
