<?php
include_once 'db.php';
include_once 'SimpleImagePix.php';

function getDir($section, $link) {//find the path
	$query = "SELECT * FROM sections WHERE `section` = '" . $section . "'";
	if ($result = mysqli_query($link, $query)) {
		$row = mysqli_fetch_assoc($result);
		$dir = $row['dir'];
		mysqli_free_result($result);
		return $dir;
	}
}

function getSection($dir, $link) {//find the path
	$query = "SELECT * FROM sections WHERE `dir` = '" . $dir . "'";
	if ($result = mysqli_query($link, $query)) {
		$row = mysqli_fetch_assoc($result);
		$section = $row['section'];
		mysqli_free_result($result);
		return $section;
	}
}
?>

