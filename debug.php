<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once HANDLERS_PATH . '/mongodbChecker.handler.php';
require_once HANDLERS_PATH . '/postgreChecker.handler.php';

// Start output buffering to prevent any accidental output
ob_start();

echo "<!DOCTYPE html>\n";
echo "<html lang='en'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Debug - Mechanicus Health Emporium</title>\n";
echo "    <style>\n";
echo "        body { font-family: monospace; margin: 20px; background: #1a1a1a; color: #00ff00; }\n";
echo "        .debug-section { border: 1px solid #333; padding: 15px; margin: 10px 0; background: #2a2a2a; }\n";
echo "        .success { color: #00ff00; }\n";
echo "        .error { color: #ff4444; }\n";
echo "        .warning { color: #ffaa00; }\n";
echo "        pre { background: #000; padding: 10px; overflow: auto; }\n";
echo "    </style>\n";
echo "</head>\n";
echo "<body>\n";

echo "<div class='debug-section'>\n";
echo "<h1>🔧 Mechanicus Debug Terminal</h1>\n";
echo "<p>Current file: " . __FILE__ . "</p>\n";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>\n";
echo "<p>PHP Version: " . PHP_VERSION . "</p>\n";
echo "</div>\n";

try {
    echo "<div class='debug-section'>\n";
    echo "<h2>📋 Bootstrap Test</h2>\n";
    require_once 'bootstrap.php';
    echo "<p class='success'>✅ Bootstrap loaded successfully</p>\n";
    echo "</div>\n";
    
    echo "<div class='debug-section'>\n";
    echo "<h2>🔐 Authentication Test</h2>\n";
    require_once 'utils/auth.util.php';
    echo "<p class='success'>✅ Auth utils loaded successfully</p>\n";
    
    $loggedIn = isAuthenticated();
    echo "<p>Authentication status: " . ($loggedIn ? "<span class='success'>✅ LOGGED IN</span>" : "<span class='warning'>❌ NOT LOGGED IN</span>") . "</p>\n";
    
    if ($loggedIn) {
        $user = getAuthenticatedUser();
        echo "<p>User data:</p>\n";
        echo "<pre>" . htmlspecialchars(print_r($user, true)) . "</pre>\n";
    } else {
        echo "<p class='warning'>User should be redirected to login page</p>\n";
    }
    echo "</div>\n";
    
    echo "<div class='debug-section'>\n";
    echo "<h2>🗂️ Session Data</h2>\n";
    echo "<pre>" . htmlspecialchars(print_r($_SESSION, true)) . "</pre>\n";
    echo "</div>\n";
    
} catch (Exception $e) {
    echo "<div class='debug-section'>\n";
    echo "<h2 class='error'>❌ ERROR DETECTED</h2>\n";
    echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>\n";
    echo "</div>\n";
}

echo "<div class='debug-section'>\n";
echo "<h2>🧭 Navigation</h2>\n";
echo "<p><a href='index.php' style='color: #00aaff;'>🔐 Go to Login Page</a></p>\n";
echo "<p><a href='pages/home/' style='color: #00aaff;'>🏠 Go to Homepage</a></p>\n";
echo "<p><a href='signup.php' style='color: #00aaff;'>📝 Go to Signup Page</a></p>\n";
echo "<p><a href='logout.php' style='color: #00aaff;'>🚪 Logout</a></p>\n";
echo "</div>\n";

echo "</body>\n";
echo "</html>\n";

// Clean up output buffer
$output = ob_get_clean();
echo $output;
?>
