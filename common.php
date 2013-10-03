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

function makeImage($src, $dest, $width) {
	$cachepath = substr($dest, 0, strrpos($dest, '/'));
	if (!is_dir($cachepath)) {
		//FIX THIS LATER!!! - permissions are wrong
		if (!mkdir($cachepath, 0777, true)) {
			die('Failed to create folders...');
		}
	}
	if (!file_exists($dest)) {
		$img = new SimpleImage();
		$img -> load($src);
		$img -> resizeToWidth($width);
		$img -> save($dest);
	}
	return ($dest);
}

function rgb($c) {
	//	$c = hexdec($hex);
	$r = ($c>>16) & 0xFF;
	$g = ($c>>8) & 0xFF;
	$b = $c & 0xFF;
	return array($r, $g, $b);
}
?>

