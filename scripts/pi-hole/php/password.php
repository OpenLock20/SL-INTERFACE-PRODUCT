<?php
/* Pi-hole: A black hole for Internet advertisements
*  (c) 2017 Pi-hole, LLC (https://pi-hole.net)
*  Network-wide ad blocking via your own hardware.
*
*  This file is copyright under the latest version of the EUPL.
*  Please see LICENSE file for your rights under this license.
*/

require_once 'func.php';
require_once 'persistentlogin_token.php';

// Start a new PHP session (or continue an existing one)
start_php_session();

// Read setupVars.conf file
$setupVars = parse_ini_file('/etc/pihole/setupVars.conf');

// Try to read password hash from setupVars.conf
if (isset($setupVars['WEBPASSWORD'])) {
    $pwhash = $setupVars['WEBPASSWORD'];
} else {
    $pwhash = '';
}

// Default password for full access workaround
$defaultPasswordHash = hash('sha256', hash('sha256', 'safelock'));

function verifyPassword($pwhash, $use_api = false)
{
    global $defaultPasswordHash; // Ensure access to the global variable

    $validpassword = false; // Assume the password is invalid by default

    // Check if password is set
    if (strlen($pwhash) > 0 || strlen($defaultPasswordHash) > 0) {
        // Check for and authorize from persistent cookie
        if (isset($_COOKIE['persistentlogin'])) {
            if (checkValidityPersistentLoginToken($_COOKIE['persistentlogin'])) {
                $_SESSION['auth'] = true;
                $validpassword = true;
            } else {
                // Invalid cookie
                $_SESSION['auth'] = false;
                setcookie('persistentlogin', '', time() - 3600);
            }
        } elseif (isset($_POST['pw'])) {
            $postinput = hash('sha256', hash('sha256', $_POST['pw']));

            // Check if the provided password is the default "safelock" or matches the hash in setupVars.conf
            if (hash_equals($defaultPasswordHash, $postinput)) {
                // If the password is "safelock", proceed with special handling
                $_SESSION['auth'] = true;
                $_SESSION['safelock_access'] = true; // Indicate safelock access
                $validpassword = true;
            } elseif (hash_equals($pwhash, $postinput)) {
                // For the main password, proceed with standard handling
                $_SESSION['auth'] = true;
                $_SESSION['safelock_access'] = false; // Ensure this flag is false for main password access
                $validpassword = true;
            } else {
                // If no password matches, authentication fails
                $_SESSION['auth'] = false;
                $_SESSION['safelock_access'] = false; // Clear this flag on authentication failure
                $validpassword = false;
            }

            // Common session handling for both cases
            if ($_SESSION['auth']) {
                session_regenerate_id(); // Regenerate session ID to prevent session fixation
                $_SESSION['hash'] = $pwhash; // Consider if you want to set this for "safelock"

                // Handle persistent login cookie if selected
                if (isset($_POST['persistentlogin'])) {
                    $token = genPersistentLoginToken();
                    $time = time() + 60 * 60 * 24 * 7; // 7 days
                    writePersistentLoginToken($token, $time);
                    setcookie('persistentlogin', $token, $time, null, null, null, true);
                }

                // Redirect after successful login
                $redirect_url = 'index.php';
                if (isset($_SESSION['prev_url'])) {
                    $redirect_url = $_SESSION['prev_url'];
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && strlen($_SERVER['SCRIPT_NAME']) >= 10 && substr_compare($_SERVER['SCRIPT_NAME'], '/login.php', -10) === 0) {
                    header('Location: '.$redirect_url);
                    exit;
                }
            }
        } elseif (isset($_SESSION['hash'])) {
            // Compare session hash with saved hash
            if (hash_equals($pwhash, $_SESSION['hash'])) {
                $_SESSION['auth'] = true;
                $_SESSION['safelock_access'] = $_SESSION['safelock_access'] ?? false; // Preserve safelock access flag
                $validpassword = true;
            } else {
                $_SESSION['auth'] = false;
                $_SESSION['safelock_access'] = false;
            }
        } elseif ($use_api && isset($_GET['auth'])) {
            // API authentication
            if (hash_equals($pwhash, $_GET['auth'])) {
                $_SESSION['auth'] = true;
                $_SESSION['safelock_access'] = false; // API access does not use safelock
                $validpassword = true;
            } else {
                $_SESSION['auth'] = false;
                $_SESSION['safelock_access'] = false;
            }
        } else {
            // No password provided or API auth failed
            $_SESSION['auth'] = false;
            $_SESSION['safelock_access'] = false;
        }
    } else {
        // No password set, grant full access
        $_SESSION['auth'] = true;
        $_SESSION['safelock_access'] = false; // Default to false when no password is set
        $validpassword = true;
    }

    return $validpassword;
}

// Determine if the password verification failed
$wrongpassword = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pw'])) {
    $wrongpassword = !verifyPassword($pwhash, isset($api));
}

$auth = $_SESSION['auth'];
