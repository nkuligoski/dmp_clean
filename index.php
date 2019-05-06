<?php

// Simple Demo of Duo's Web SDK

require_once './src/Web.php';
$domainuser = $_SERVER['AUTH_USER'];
define('USERNAME', $domainuser);

/*
 * This is something uniquely generated by you for your application
 * and is not shared with Duo. Must be at least 40 characters.
 */
define('AKEY', "CHANGETHISKEYTOSOMETHINGTHATISATLEAST40CHARACTERS");

/*
 * IKEY, SKEY, and HOST should come from the Duo Security admin dashboard
 * on the integrations page. For security reasons, these keys are best stored
 * outside of the webroot in a production implementation.
 */
define('IKEY', "DMP_INTEGRATIONKEY");
define('SKEY', "DMP_SECRET_KEY");
define('HOST', "DMP_API_HOST");

echo "<html>";
echo '<head>';
echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link rel="stylesheet" href="styles.css">';
echo '<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">';
echo '<title>Device Management Portal</title>';
echo '</head>';

/*
 * STEP 3:
 * Once secondary auth has completed you may log in the user
 */
if (isset($_POST['sig_response'])) {
    /*
     * Verify sig response and log in user. Make sure that verifyResponse
     * returns the username we logged in with. You can then set any
     * cookies/session data for that username and complete the login process.
     */
    $resp = Duo\Web::verifyResponse(IKEY, SKEY, AKEY, $_POST['sig_response']);
    if ($resp === USERNAME) {
        // Password protected content would go here.
        echo 'Hi, ' . $resp . '<br>';
        echo 'Your content here!';
    }
}

/*
 * STEP 2:
 * verify username and password
 * if the user and pass are good, then generate a sig_request and
 * load up the Duo iframe for secondary authentication
 */
else {
    $sig_request = Duo\Web::signRequest(IKEY, SKEY, AKEY, $_SERVER['AUTH_USER']);
	?>
    <script type="text/javascript" src="Duo-Web-v2.js"></script>
    <link rel="stylesheet" type="text/css" href="Duo-Frame.css">
    <iframe id="duo_iframe" data-host="<?php echo HOST; ?>" data-sig-request="<?php echo $sig_request; ?>"></iframe>
<?php
}

echo "</html>";

?>
