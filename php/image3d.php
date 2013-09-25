<?php
include 'db.php';
include 'SimpleImage.php';
include 'common.php';

$id = $_GET['id'];
//$width = $_GET['width'];
$width = 400;
$query = "SELECT * FROM images WHERE `idx` = '" . $id . "'";

if ($result = mysqli_query($link, $query)) {
	$row = mysqli_fetch_assoc($result);
	$file = $row['file'];
	$section = $row['section'];
	$filejpg = str_replace(".png", ".jpg", $file);
	$dir = getDir($section, $link);
	$cacheroot = '../images/cache/' . $dir . '/' . $width . 'w';
	$fileroot = '../images/' . $dir;

	$cachepath = $cacheroot . '/L';
	$filesrc = $fileroot . '/L/' . $file;
	$imageL = $cachepath . '/' . $filejpg;
	//		echo $filesrc . '<br>';
	//	echo $imageL . '<br>';
	if (!is_dir($cachepath)) {
		//FIX THIS LATER!!! - permissions are wrong
		if (!mkdir($cachepath, 0777, true)) {
			die('Failed to create folders...');
		}
	}
	if (!file_exists($imageL)) {
		$img = new SimpleImage();
		$img -> load($filesrc);
		$img -> resizeToWidth($width);
		$img -> save($imageL);
	}

	$cachepath = $cacheroot . '/R';
	$filesrc = $fileroot . '/R/' . $file;
	$imageR = $cachepath . '/' . $filejpg;
	if (!is_dir($cachepath)) {
		//FIX THIS LATER!!! - permissions are wrong
		if (!mkdir($cachepath, 0777, true)) {
			die('Failed to create folders...');
		}
	}
	if (!file_exists($imageR)) {
		$img = new SimpleImage();
		$img -> load($filesrc);
		$img -> resizeToWidth($width);
		$img -> save($imageR);
	}

	echo '<div id="image3d-' . $id . '" class="image-3d">';
	echo '<div class="image-L"><img src="' . substr($imageL, 3) . '" /></div><div class="image-R"><img src="' . substr($imageR, 3) . '" /></li>';
	echo '</div>';
}
mysqli_close($link);
?>
