<?php
include 'common.php';

if (isset($_GET['dir'])) {
	$dir = $_GET['dir'];
	$thumbwidth = $_GET['width'];

	$section = getSection($dir, $link);

	//	echo $dir . '<br />';
	//create the thumbnails
	$thumbdir = 'images/cache/' . $dir . '/' . $thumbwidth . 'w/L';
	//	echo $thumbdir . '<br />';
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
	//	echo $section . '<br />';
	$query = "SELECT * FROM images WHERE `dir` = '" . $dir . "'";
	if ($result = mysqli_query($link, $query)) {
//		echo '<h1>3d Photo Gallery -> ';
//		echo $section . '</h1>';
		echo '<ul>';
		while ($row = mysqli_fetch_assoc($result)) {
			$file = $row['file'];
			//echo $file . '<br />';

			$thumb = str_replace(".png", ".jpg", $file);
			$name = $row['name'];
			$id = $row['idx'];
			$description = $row['description'];
			$srcfile = 'images/' . $dir . '/L/' . $file;
			$thumbfile = $thumbdir . '/' . $thumb;
			if (!file_exists($thumbfile)) {
				$image = new SimpleImage();
				$image -> load($srcfile);
				$image -> resizeToWidth($thumbwidth);
				$image -> save($thumbfile);
			}
			echo '<li>';
			echo '<a class="gal" href="image3d.php?id=' . $id . '"><img src="' . $thumbfile . '" /></a>';
			echo '<p>' . $file . '</p>';
			echo '<p>' . $name . '</p>';
			echo '<p>' . $description . '</p>';
			echo '</li>';
		}
		echo '</ul>';
		//		echo '<div id="ratio">' . $maxratio . '</div>';
		echo '<div class="clearboth"></div>';
		mysqli_free_result($result);
	}
	mysqli_close($link);
}
?>
