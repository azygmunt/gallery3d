<?php
include 'common.php';

$id = $_GET['id'];
//$width = $_GET['width'];
$width = $_GET['width'];
if ($width == "") {
	$width = 1000;
}
$type = $_GET['type'];
$color = $_GET['color'];
$query = "SELECT * FROM images WHERE `idx` = '" . $id . "'";

if ($result = mysqli_query($link, $query)) {
	$row = mysqli_fetch_assoc($result);
	$file = $row['file'];
	//	$filejpg = str_replace(".png", ".jpg", $file);
	$dir = $row['dir'];

	$cache = array();
	$src = array();
	$image = array();

	$cache['L'] = 'cache/' . $dir . '/L/' . $width . 'w/' . $file;
	$cache['R'] = 'cache/' . $dir . '/R/' . $width . 'w/' . $file;
	$cache['an_bw'] = 'cache/' . $dir . '/an_bw/' . $width . 'w/' . $file;
	$cache['an_col'] = 'cache/' . $dir . '/an_col/' . $width . 'w/' . $file;
	$src['L'] = 'images/' . $dir . '/L/' . $file;
	$src['R'] = 'images/' . $dir . '/R/' . $file;

	echo '<ul id="image3d-' . $id . '" class="image-3d">';
	switch ($type) {
		case 'crosseye' :
			$image['L'] = makeImage($src['L'], $cache['L'], $width);
			$image['R'] = makeImage($src['R'], $cache['R'], $width);
			echo '<li class="image-R"><img src="' . $image['L'] . '" /></li>';
			echo '<li class="image-L"><img src="' . $image['R'] . '" /></li>';
			echo '<div class="clearboth"></div>';
			break;
		case 'universal' :
			$image['L'] = makeImage($src['L'], $cache['L'], $width);
			$image['R'] = makeImage($src['R'], $cache['R'], $width);
			echo '<div class="image-L"><img src="' . $image['L'] . '" /></div>';
			echo '<div class="image-R"><img src="' . $image['R'] . '" /></div>';
			echo '<div class="image-L"><img src="' . $image['L'] . '" /></div>';
			echo '<div class="clearboth"></div>';
			break;
		case 'L' :
			$image['L'] = makeImage($src['L'], $cache['L'], $width);
			echo '<div class="image-L"><img src="' . $image['L'] . '" /></div>';
			echo '<div class="clearboth"></div>';
			break;
		case 'R' :
			$image['R'] = makeImage($src['R'], $cache['R'], $width);
			echo '<div class="image-R"><img src="' . $image['R'] . '" /></div>';
			echo '<div class="clearboth"></div>';
			break;
		/*		case 'redblue' :
		 if ($color == 'color') {
		 $cachepath = $cacheroot . '/rbc';
		 }

		 if (!is_dir($cachepath)) {
		 //FIX THIS LATER!!! - permissions are wrong
		 if (!mkdir($cachepath, 0777, true)) {
		 die('Failed to create folders...');
		 }
		 }
		 if (!file_exists($imageOut)) {
		 $srcL = new SimpleImage();
		 $srcR = new SimpleImage();
		 $srcL -> load($filesrcL);
		 $srcR -> load($filesrcR);
		 //				echo($srcL -> getWidth() . ', ');
		 //				echo($srcL -> getHeight() . '<br />');
		 $srcL -> resizeToWidth($width);
		 $srcR -> resizeToWidth($width);
		 if ($color == 'mono') {
		 $srcL -> grayscale();
		 $srcR -> grayscale();
		 }
		 //				echo($srcL -> getWidth() . ', ');
		 //				echo($srcL -> getHeight() . '<br />');
		 $height = $srcL -> getHeight();
		 $rb_image = new SimpleImage();
		 $rb_image -> create($width, $height);
		 for ($x = 0; $x < $width; $x++) {
		 for ($y = 0; $y < $height; $y++) {
		 $rgbL = $srcL -> getPixel($x, $y);
		 $rgbR = $srcR -> getPixel($x, $y);
		 list($rL, $gL, $bL) = rgb($rgbL);
		 list($rR, $gR, $bR) = rgb($rgbR);
		 //	echo('(' . $rL . ', ' . $gL . ', ' . $bL . '), ');
		 $r = $rL;
		 $g = $gR;
		 $b = $bR;
		 $rb_image -> setPixel($x, $y, $r, $g, $b);
		 //						echo($x . ', ');
		 //						echo($y . '<br />');
		 }
		 //				echo '<br>';
		 }
		 $rb_image -> save($imageOut);
		 }
		 //			imagepng($im, 'dave.png');
		 echo '<div class="image-rb"><img src="' . $imageOut . '" /></div>';
		 echo '<div class="clearboth"></div>';
		 break;
		 */
		default :
			$image['L'] = makeImage($src['L'], $cache['L'], $width);
			$image['R'] = makeImage($src['R'], $cache['R'], $width);
			echo '<li class="image-L"><img src="' . $image['L'] . '" /></li>';
			echo '<li class="image-R"><img src="' . $image['R'] . '" /></li>';
			break;
	}
	echo '</ul>';
}

mysqli_close($link);

?>
