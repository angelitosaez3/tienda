<?php
$db_hostname = getenv('MYSQL_HOST') ?: 'db';
$db_username = getenv('MYSQL_USER') ?: 'user';
$db_password = getenv('MYSQL_PASSWORD') ?: 'password';
$db_name = getenv('MYSQL_DATABASE') ?: 'tienda_online';

$conn = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>
