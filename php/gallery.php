<?php
include 'db.php';
include_once 'SimpleImage.php';
include_once 'common.php';

if (isset($_GET['section'])) {
	$section = $_GET['section'];
	$thumbwidth = $_GET['width'];

	$dir = getDir($section, $link);

	//create the thumbnails
	$thumbdir = '../images/cache/' . $dir . '/' . $thumbwidth . 'w/L';
	if (!is_dir($thumbdir)) {
		//FIX THIS LATER!!!
		if (!mkdir($thumbdir, 0777, true)) {
			die('Failed to create folders...');
		}
		//		$old = umask(0);
		//		mkdir($thumbdir, 0777, true);
		//	umask($old);
	}

	//draw the gallery
	$query = "SELECT * FROM images WHERE `section` = '" . $section . "'";
	if ($result = mysqli_query($link, $query)) {
		echo '<ul>';
		while ($row = mysqli_fetch_assoc($result)) {
			$file = $row['file'];
			$thumb = str_replace(".png", ".jpg", $file);
			$name = $row['name'];
			$id = $row['idx'];
			$description = $row['description'];
			$srcimage = '../images/' . $dir . '/L/' . $file;
			$thumbimage = $thumbdir . '/' . $thumb;
			if (!file_exists($thumbimage)) {
				//				echo $srcimage.'<br>';
				//				echo $thumbimage.'<br>';
				$image = new SimpleImage();
				$image -> load($srcimage);
				$image -> resizeToWidth($thumbwidth);
				$image -> save($thumbimage);
			}
			echo '<li><a href="php/image3d.php?id=' . $id . '"><img src="' . substr($thumbimage, 3) . '" /></a><p>' . $name . '</p><p>' . $description . '</p></li>';
		}
		echo '</ul>';
		mysqli_free_result($result);
	}
	mysqli_close($link);
}
?>
