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

?>
<ul class="gridlist">
<?php
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
?>
	<li>
		<a class="fancybox" rel="gallery" href="image3d.php?id='<?php echo $id; ?> ">
			<img src="<?php echo $thumbfile; ?>" />
		</a>
		<p><?php echo $file; ?></p>
		<p><?php echo $name; ?></p>
		<p><?php echo $description; ?></p>
	</li>
<?php } ?>
</ul>
<div class="clearboth"></div>
<?php mysqli_free_result($result);
	}
	mysqli_close($link);
	}
?>
