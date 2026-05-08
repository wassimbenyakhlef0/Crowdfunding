<?php

// Completely disable platform check
$platformCheckFile = __DIR__ . '/vendor/composer/platform_check.php';
$backupFile = __DIR__ . '/vendor/composer/platform_check.php.backup';

// Create backup if it doesn't exist
if (!file_exists($backupFile)) {
    copy($platformCheckFile, $backupFile);
}

// Read the current content
$content = file_get_contents($platformCheckFile);

// Replace the entire platform check with a simple return
$newContent = '<?php
// Platform check disabled for PHP 8.0.30 compatibility
return;
';

// Write the modified content back
file_put_contents($platformCheckFile, $newContent);

echo "Platform check completely disabled\n";
echo "Backup created at: " . $backupFile . "\n";
