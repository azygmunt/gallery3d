<?php
$host = 'ajz.dyndns.info';
$user = 'gallery3d';
$password = 'gallery3d';
$db = 'gallery3d';
$link = mysqli_connect($host, $user, $password, $db);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

?>
