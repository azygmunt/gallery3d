<?php
include 'common.php';

if (isset($_GET['dir'])) {
	$dir = $_GET['dir'];
	$thumbwidth = $_GET['width'];
	if ($thumbwidth == "") {
		$thumbwidth = 200;
	}
	$section = getSection($dir, $link);

	//create the thumbnails
	$thumbdir = 'cache/' . $dir . '/L/' . $thumbwidth . 'w';

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
	$query = "SELECT * FROM images WHERE `dir` = '" . $dir . "'";
	if ($result = mysqli_query($link, $query)) {
		echo '<ul class="gridlist">';
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
			//			echo '<div class="vcent">';
			echo '<a class="fancybox" href="image3d.php?id=' . $id . '">';
			echo '<img src="' . $thumbfile . '" />';
			echo '</a>';
			//		echo '</div>';
			echo '<p>' . $file . '</p>';
			echo '<p>' . $name . '</p>';
			echo '<p>' . $description . '</p>';
			echo '</li>';
		}
		echo '</ul>';
		echo '<div class="clearboth"></div>';
		mysqli_free_result($result);
	}
	mysqli_close($link);
}
?>
