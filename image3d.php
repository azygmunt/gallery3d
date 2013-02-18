<?php
include 'common.php';

$id = $_GET['id'];
//$width = $_GET['width'];
$width = $_GET['width'];
$type = $_GET['type'];
$color = $_GET['color'];
$query = "SELECT * FROM images WHERE `idx` = '" . $id . "'";

if ($result = mysqli_query($link, $query)) {
	$row = mysqli_fetch_assoc($result);
	$file = $row['file'];
	//	$filejpg = str_replace(".png", ".jpg", $file);
	$dir = $row['dir'];

	echo '<div id="image3d-' . $id . '" class="image-3d">';
	switch ($type) {
		case 'parallel' :
		case 'flicker' :
			$imageL = makeImage('L', $file, $dir, $width);
			$imageR = makeImage('R', $file, $dir, $width);
			echo '<div class="image-L"><img src="' . $imageL . '" /></div>';
			echo '<div class="image-R"><img src="' . $imageR . '" /></div>';
			echo '<div class="clearboth"></div>';
			break;
		case 'crosseye' :
			$imageL = makeImage('L', $file, $dir, $width);
			$imageR = makeImage('R', $file, $dir, $width);
			echo '<div class="image-R"><img src="' . $imageR . '" /></div>';
			echo '<div class="image-L"><img src="' . $imageL . '" /></div>';
			echo '<div class="clearboth"></div>';
			break;
		case 'L' :
			$imageL = makeImage('L', $file, $dir, $width);
			echo '<div class="image-L"><img src="' . $imageL . '" /></div>';
			echo '<div class="clearboth"></div>';
			break;
		case 'R' :
			$imageR = makeImage('R', $file, $dir, $width);
			echo '<div class="image-R"><img src="' . $imageR . '" /></div>';
			echo '<div class="clearboth"></div>';
			break;
		case 'redblue' :
			$cacheroot = 'images/cache/' . $dir . '/' . $width . 'w';
			$fileroot = 'images/' . $dir;

			$cachepath = $cacheroot . '/rb';
			if ($color == 'color') {
				$cachepath = $cacheroot . '/rbc';
			}

			$filesrcL = $fileroot . '/L/' . $file;
			$filesrcR = $fileroot . '/R/' . $file;
			$imageOut = $cachepath . '/' . $file;
			//			echo $imageOut . '<br />';
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
						$r = $rR;
						$g = $gL;
						$b = $bL;
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
		default :
			//	echo '<div class="image-L"><img src="' . substr($imageL, 3) . '" /></div><div class="image-R"><img src="' . substr($imageR, 3) . '" /></li>';
			break;
	}
	echo '</div>';
}
mysqli_close($link);

function makeImage($eye, $file, $dir, $width) {
	$cacheroot = 'images/cache/' . $dir . '/' . $width . 'w';
	$fileroot = 'images/' . $dir;
	$cachepath = $cacheroot . '/' . $eye;
	$filesrc = $fileroot . '/' . $eye . '/' . $file;
	$imageOut = $cachepath . '/' . $file;
	if (!is_dir($cachepath)) {
		//FIX THIS LATER!!! - permissions are wrong
		if (!mkdir($cachepath, 0777, true)) {
			die('Failed to create folders...');
		}
	}
	if (!file_exists($imageOut)) {
		$img = new SimpleImage();
		$img -> load($filesrc);
		$img -> resizeToWidth($width);
		$img -> save($imageOut);
	}
	return ($imageOut);
}

function rgb($c) {
	//	$c = hexdec($hex);
	$r = ($c>>16) & 0xFF;
	$g = ($c>>8) & 0xFF;
	$b = $c & 0xFF;
	return array($r, $g, $b);
}
?>
