<?php
$file = 'resources/views/patient/account.blade.php';
$content = file_get_contents($file);

$content = str_replace('->doctor->name', '->doctor->user->name', $content);
$content = str_replace('$doc->name', '$doc->user->name', $content);

file_put_contents($file, $content);
echo "Replaced successfully!\n";
