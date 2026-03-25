<?php
$host = 'localhost';
$user = 'aivet';
$pass = 'aivet';
$dbname = 'raspberry_iot';

$db = new mysqli($host, $user, $pass, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
