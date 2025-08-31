<?php
echo "<h1>Root Path:</h1>";
echo "<p>Current dir: " . __DIR__ . "</p>";
echo "<p>Parent dir: " . dirname(__DIR__) . "</p>";
echo "<p>Config exists? " . (file_exists('config.php') ? 'Sì' : 'No') . "</p>";
?>
