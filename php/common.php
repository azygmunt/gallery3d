<?php
function getDir($section, $link) {//find the path
	$query = "SELECT * FROM sections WHERE `section` = '" . $section . "'";
	if ($result = mysqli_query($link, $query)) {
		$row = mysqli_fetch_assoc($result);
		$path = $row['path'];
		mysqli_free_result($result);
		return $path;
	}
}
?>
