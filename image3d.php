<?php
//echo 'image3d ok<br>';
include 'common.php';

$id = $_GET['id'];

$options = array();
$options['type'] = $_COOKIE['type'];
$options['width'] = $_COOKIE['width'];
$options['anaglyph'] = $_COOKIE['anaglyph'];

//set defaults
if ($options['width'] == "") {
	$options['width'] = 1000;
}
if ($options['anaglyph'] == "") {
	$options['anaglyph'] = grey;
}

$query = "SELECT * FROM images WHERE `idx` = '" . $id . "'";

if ($result = mysqli_query($link, $query)) {
	$row = mysqli_fetch_assoc($result);
	$file = $row['file'];
	$dir = $row['dir'];

	$cache = array();
	$src = array();

	$cache['L'] = 'cache/' . $dir . '/L/' . $options['width'] . 'w/' . $file;
	$cache['R'] = 'cache/' . $dir . '/R/' . $options['width'] . 'w/' . $file;
	switch($options['anaglyph']) {
		case 'true' :
			$cache['an'] = 'cache/' . $dir . '/an_true/' . $options['width'] . 'w/' . $file;
			break;
		case 'grey' :
			$cache['an'] = 'cache/' . $dir . '/an_grey/' . $options['width'] . 'w/' . $file;
			break;
		case 'color' :
			$cache['an'] = 'cache/' . $dir . '/an_col/' . $options['width'] . 'w/' . $file;
			break;
		case 'halfcolor' :
			$cache['an'] = 'cache/' . $dir . '/an_hcol/' . $options['width'] . 'w/' . $file;
			break;
		case 'optimized' :
			$cache['an'] = 'cache/' . $dir . '/an_opt/' . $options['width'] . 'w/' . $file;
			break;
		case 'dubois' :
			$cache['an'] = 'cache/' . $dir . '/an_dub/' . $options['width'] . 'w/' . $file;
			break;
	}

	$src['L'] = 'images/' . $dir . '/L/' . $file;
	$src['R'] = 'images/' . $dir . '/R/' . $file;

	make3dImage($src, $cache, $options);
	//	echo 'returning...<br>';
	echo '<ul id="image3d-' . $id . '" class="image-3d">';
	switch ($options['type']) {
		case 'crosseye' :
			echo '<li class="image-R"><img src="' . $cache['R'] . '" /><h3>Right</h3></li>';
			echo '<li class="image-L"><img src="' . $cache['L'] . '" /><h3>Left</h3></li>';
			break;
		case 'universal' :
			echo '<div class="image-L"><img src="' . $cache['L'] . '" /></div>';
			echo '<div class="image-R"><img src="' . $cache['R'] . '" /></div>';
			echo '<div class="image-L"><img src="' . $cache['L'] . '" /></div>';
			break;
		case 'L' :
			echo '<div class="image-L"><img src="' . $cache['L'] . '" /></div>';
			break;
		case 'R' :
			echo '<div class="image-R"><img src="' . $cache['R'] . '" /></div>';
			break;
		case 'anaglyph' :
			echo '<div class="image-ana"><img src="' . $cache['an'] . '" /></div>';
			break;
		default :
			echo '<li class="image-L"><img src="' . $cache['L'] . '" /><h3>Left</h3></li>';
			echo '<li class="image-R"><img src="' . $cache['R'] . '" /><h3>Right</h3></li>';
			break;
	}
	echo '</ul>';
}

mysqli_close($link);

function make3dImage($src, $dest, $options) {
	$dim['w'] = $options['width'];
	makeImage($src['L'], $dest['L'], $dim['w']);
	makeImage($src['R'], $dest['R'], $dim['w']);

	if (!file_exists($dest['an'])) {
		$destpath = substr($dest['an'], 0, strrpos($dest['an'], '/'));
		//	echo $destpath;
		if (!is_dir($destpath)) {
			//FIX THIS LATER!!! - permissions are wrong
			if (!mkdir($destpath, 0777, true)) {
				die('Failed to create folders...');
			}
		}

		$src_img['L'] = new SimpleImage();
		$src_img['R'] = new SimpleImage();
		$src_img['L'] -> load($src['L']);
		$src_img['R'] -> load($src['R']);
		$src_img['L'] -> resizeToWidth($dim['w']);
		$src_img['R'] -> resizeToWidth($dim['w']);
		$dim['h'] = $src_img['L'] -> getHeight();
		$ana_img = new SimpleImage();
		$ana_img -> create($dim['w'], $dim['h']);

		//create anaglyph matrix
		$an = array();
		switch($options['anaglyph']) {
			case 'true' :
				$an['L'] = array( array(0.299, 0.587, 0.114), array(0, 0, 0), array(0, 0, 0));
				$an['R'] = array( array(0, 0, 0), array(0, 0, 0), array(0.299, 0.587, 0.114));
				break;
			case 'grey' :
				$an['L'] = array( array(0.299, 0.587, 0.114), array(0, 0, 0), array(0, 0, 0));
				$an['R'] = array( array(0, 0, 0), array(0.299, 0.587, 0.114), array(0.299, 0.587, 0.114));
				break;
			case 'color' :
				$an['L'] = array( array(1, 0, 0), array(0, 0, 0), array(0, 0, 0));
				$an['R'] = array( array(0, 0, 0), array(0, 1, 0), array(0, 0, 1));
				break;
			case 'halfcolor' :
				$an['L'] = array( array(0.299, 0.587, 0.114), array(0, 0, 0), array(0, 0, 0));
				$an['R'] = array( array(0, 0, 0), array(0, 1, 0), array(0, 0, 1));
				break;
			case 'optimized' :
				$an['L'] = array( array(0, 0.7, 0.3), array(0, 0, 0), array(0, 0, 0));
				$an['R'] = array( array(0, 0, 0), array(0, 1, 0), array(0, 0, 1));
				break;
			case 'dubois' :
				$an['L'] = array( array(0.437, 0.449, 0.164), array(-0.062, -0.062, -0.024), array(-0.048, -0.05, -0.017));
				$an['R'] = array( array(-0.011, -0.032, -0.007), array(0.377, 0.761, 0.009), array(-0.026, -0.093, 1.234));
				break;
		}
		for ($x = 0; $x < $dim['w']; $x++) {
			for ($y = 0; $y < $dim['h']; $y++) {
				$pix['L'] = rgb($src_img['L'] -> getPixel($x, $y));
				$pix['R'] = rgb($src_img['R'] -> getPixel($x, $y));
				$pix['an'] = array(0, 0, 0);
				$v1 = matrix_vec($an['L'], $pix['L']);
				$v2 = matrix_vec($an['R'], $pix['R']);
				$pix['an'] = vec_add($v1, $v2);
				$ana_img -> setPixel($x, $y, $pix['an'][0], $pix['an'][1], $pix['an'][2]);
			}
		}
		$ana_img -> save($dest['an']);
	}
}

function matrix_vec($mat, $vec) {
	$out = array(0, 0, 0);
	for ($i = 0; $i < 3; ++$i) {
		for ($j = 0; $j < 3; ++$j) {
			$out[$i] = $out[$i] + ($vec[$j] * $mat[$i][$j]);
		}
	}
	return ($out);
}

function vec_add($vec1, $vec2) {
	$out = array(0, 0, 0);
	for ($i = 0; $i < 3; ++$i) {
		$out[$i] = $vec1[$i] + $vec2[$i];
	}
	return ($out);
}
?>
