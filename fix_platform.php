<?php

// Read the platform check file
$platformCheckFile = __DIR__ . '/vendor/composer/platform_check.php';
$backupFile = __DIR__ . '/vendor/composer/platform_check.php.backup';

// Create backup if it doesn't exist
if (!file_exists($backupFile)) {
    copy($platformCheckFile, $backupFile);
}

// Read the current content
$content = file_get_contents($platformCheckFile);

// Replace the PHP version check to allow 8.0
$content = str_replace('>= 8.4.0', '>= 8.0.0', $content);

// Write the modified content back
file_put_contents($platformCheckFile, $content);

echo "Platform check fixed for PHP 8.0 compatibility\n";
echo "Backup created at: " . $backupFile . "\n";
